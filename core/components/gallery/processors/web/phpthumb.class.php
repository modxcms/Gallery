<?php

/**
 * @package gallery
 */

/* load phpThumb */
class GalleryPhpthumbProcessor extends modProcessor {

    /** Upper limit for any requested pixel dimension */
    const MAX_DIMENSION = 4096;

    /** Maximum number of phpThumb filters per request */
    const MAX_FILTERS = 10;

    /** Maximum text length of a text watermark (wmt filter) */
    const MAX_WATERMARK_TEXT_LENGTH = 200;

    /** Maximum font size of a text watermark (wmt filter) */
    const MAX_WATERMARK_FONT_SIZE = 500;

    public function process(){
        $modx = $this->modx;
        $scriptProperties = $this->properties;

        if (!class_exists('phpthumb', false)) {
            if (!$modx->loadClass('phpthumb', MODX_CORE_PATH . 'model/phpthumb/', true, true)) {
                $modx->log(modX::LOG_LEVEL_ERROR, '[phpThumbOf] Could not load modPhpThumb class.');
                return '';
            }
        }

        /* detailed phpThumb debug messages are only logged, if the MODX log_level is set to debug */
        $verbose = $modx->getLogLevel() >= modX::LOG_LEVEL_DEBUG;

        $src = $modx->getOption('src', $scriptProperties, '');
        if (!is_string($src)) {
            return $this->notFound();
        }
        $src = str_replace('+', '%27', urldecode($src));
        $src = str_replace("\0", '', $src);

        /* only local files are allowed as source to prevent server-side request forgery.
           URLs pointing to this site (i.e. with gallery.thumbs_prepend_site_url) are converted
           to a local path, every other URL scheme (http, ftp, php, data, ...) is rejected */
        if (preg_match('#^[a-z][a-z0-9+.\-]*:#i', $src) || preg_match('#^(//|\\\\\\\\)#', $src)) {
            $src = $this->localPathFromUrl($src);
        }
        if ($src === false || $src === '' || strpos($src, '://') !== false) {
            $modx->log(modX::LOG_LEVEL_WARN, '[Gallery] Rejected phpThumb source that is not a local file.');
            return $this->notFound();
        }

        /* explode tag options */
        $ptOptions = array();

        // Only public parameters of phpThumb, that are useful for this processor, are allowed to pass from user input.
        // See the properties between START PARAMETERS and END PARAMETERS in core/model/phpthumb/phpthumb.class.php
        // Not allowed: new (creates an image without a source), err (redirect on error), goto, phpThumbDebug,
        // down, sia, md5s, xto (unused by this processor or only by the standalone phpThumb.php script)
        $allowed = array(
            'src', 'w', 'h', 'wp', 'hp', 'wl', 'hl', 'ws', 'hs',
            'f', 'q', 'sx', 'sy', 'sw', 'sh', 'zc', 'bc', 'bg', 'fltr',
            'ra', 'ar', 'aoe', 'far', 'iar', 'maxb', 'sfn', 'dpi'
        );

        /* iterate through properties */
        /* request parameters that are always sent by the connector and are no phpThumb parameters */
        $connectorParams = array('action', 'ctx', 'HTTP_MODAUTH');

        foreach ($scriptProperties as $property => $value) {
            if (!in_array($property, $allowed, true)) {
                if (!in_array($property, $connectorParams, true)) {
                    $this->modx->log(modX::LOG_LEVEL_DEBUG, '[Gallery] Ignored phpThumb parameter that is not allowed: ' . preg_replace('#[^A-Za-z0-9_\-\[\]]#', '_', substr((string)$property, 0, 50)));
                }
                continue;
            }
            $ptOptions[$property] = $value;
        }
        unset($ptOptions['src']);

        /* limit the output dimensions to prevent memory/CPU exhaustion */
        $maxDimension = self::MAX_DIMENSION;
        foreach (array('w', 'h', 'wp', 'hp', 'wl', 'hl', 'ws', 'hs') as $dimension) {
            if (!isset($ptOptions[$dimension])) {
                continue;
            }
            if (!is_scalar($ptOptions[$dimension]) || !is_numeric($ptOptions[$dimension])) {
                unset($ptOptions[$dimension]);
                continue;
            }
            $ptOptions[$dimension] = min(abs((int)$ptOptions[$dimension]), $maxDimension);
        }

        /* validate the filters, some of them load files or change the output size */
        if (isset($ptOptions['fltr'])) {
            $ptOptions['fltr'] = $this->sanitizeFilters($ptOptions['fltr'], $maxDimension);
            if (empty($ptOptions['fltr'])) {
                unset($ptOptions['fltr']);
            }
        }

        if (empty($ptOptions['f']) || !is_string($ptOptions['f'])) {
            $ext = pathinfo($src, PATHINFO_EXTENSION);
            $ext = strtolower($ext);
            switch ($ext) {
                case 'png':
                case 'gif':
                case 'bmp':
                case 'webp':
                case 'avif':
                case 'text':
                case 'ico':
                    $ptOptions['f'] = $ext;
                    break;
                default:
                    $ptOptions['f'] = 'jpeg';
                    break;
            }
        }

        $availableFormats = array('jpeg', 'png', 'gif', 'bmp', 'webp', 'avif', 'text', 'ico');
        if (!in_array($ptOptions['f'], $availableFormats, true)) {
            $ptOptions['f'] = 'jpeg';
        }

        /* load phpthumb */
        $assetsPath = $modx->getOption('gallery.assets_path', null, $modx->getOption('assets_path') . 'components/gallery/');
        $cacheDir = $assetsPath . 'cache/';

        /* check to make sure cache dir is writable */
        if (!is_writable($cacheDir)) {
            if (!$modx->cacheManager->writeTree($cacheDir)) {
                $modx->log(modX::LOG_LEVEL_ERROR, '[phpThumbOf] Cache dir not writable: ' . $cacheDir);
                return '';
            }
        }

        /* get absolute url of image */
        /* convert the source to an absolute file path, the source is always a local file:
           - an absolute file path inside the base path is used as is
           - a path starting with the base url (i.e. /subdir/assets/...) is resolved from the base path
           - every other path is relative to the base path */
        $basePath = rtrim($modx->getOption('base_path', null, MODX_BASE_PATH), '/') . '/';
        $baseUrl = trim($modx->getOption('base_url', null, MODX_BASE_URL), '/');
        $relativeSrc = ltrim($src, '/');
        if (strpos($src, $basePath) === 0) {
            /* absolute file path inside the base path */
        } elseif ($baseUrl !== '' && strpos($relativeSrc, $baseUrl . '/') === 0) {
            $src = $basePath . substr($relativeSrc, strlen($baseUrl) + 1);
        } else {
            $src = $basePath . $relativeSrc;
        }

        $config = array();
        $config['modphpthumb'] = array();
        $config['modphpthumb']['config_allow_src_above_docroot'] = (boolean)$modx->getOption('phpthumb_allow_src_above_docroot', null, false);
        $config['modphpthumb']['zc'] = $modx->getOption('phpthumb_zoomcrop', null, 0);
        $config['modphpthumb']['far'] = $modx->getOption('phpthumb_far', null, 'C');
        $config['modphpthumb']['config_ttf_directory'] = MODX_CORE_PATH . 'model/phpthumb/fonts/';
        $config['modphpthumb']['config_document_root'] = $modx->getOption('phpthumb_document_root', null, '');
        /* cache limits, so that CleanUpCacheDirectory() is able to purge the cache */
        $config['modphpthumb']['config_cache_maxage'] = (float)$modx->getOption('phpthumb_cache_maxage', null, 30) * 86400;
        $config['modphpthumb']['config_cache_maxsize'] = (float)$modx->getOption('phpthumb_cache_maxsize', null, 100) * 1024 * 1024;
        $config['modphpthumb']['config_cache_maxfiles'] = (int)$modx->getOption('phpthumb_cache_maxfiles', null, 10000);
        /* defense in depth: never fetch remote sources */
        $config['modphpthumb']['config_nohotlink_enabled'] = true;
        $config['modphpthumb']['config_nohotlink_valid_domains'] = array();

        $phpThumb = new phpthumb(); // unfortunately we have to create a new object for each image!
        foreach ($config['modphpthumb'] as $param => $value) { // add MODX system settings
            $phpThumb->$param = $value;
        }
        foreach ($ptOptions as $param => $value) { // add options passed to the snippet
            $phpThumb->setParameter($param, $value);
        }
        // try to avert problems when $_SERVER['DOCUMENT_ROOT'] is different than MODX_BASE_PATH
        if (!$phpThumb->config_document_root) {
            $phpThumb->config_document_root = MODX_BASE_PATH; // default if nothing set from system settings
        }
        $phpThumb->config_cache_directory = $cacheDir; // doesn't matter, but saves phpThumb some frustration
        $phpThumb->setSourceFilename($src);

        /* setup cache filename that is unique to this tag, restricted to a safe character set
           and prefixed with the phpThumb cache prefix, so CleanUpCacheDirectory() handles it */
        $readableName = preg_replace('#[^A-Za-z0-9_-]+#', '_', pathinfo($src, PATHINFO_FILENAME));
        $readableName = substr($readableName, 0, 100);
        $cachePrefix = preg_replace('#[^A-Za-z0-9_.-]+#', '_', (string)$phpThumb->config_cache_prefix);
        $cacheFilename = $cachePrefix . $readableName;
        $cacheFilename .= '.' . md5($src . serialize($ptOptions));
        $cacheFilename .= '.' . $ptOptions['f'];
        $cacheKey = $cacheDir . $cacheFilename;

        /* ensure we have an accurate and clean cache directory */
        $phpThumb->CleanUpCacheDirectory();

        /* check to see if there's a cached file of this already */
        if (file_exists($cacheKey)) {
            $modx->log(modX::LOG_LEVEL_DEBUG, '[phpThumbOf] Using cached file found for thumb: ' . $cacheKey);
        } else {
            /* actually make the thumbnail */
            if ($phpThumb->GenerateThumbnail()) { // this line is VERY important, do not remove it!
                if ($phpThumb->RenderToFile($cacheKey)) {
                    /* ensure file has proper permissions */
                    $filePerm = (string)$modx->getOption('new_file_permissions', null, '0664');
                    @chmod($cacheKey, octdec($filePerm));
                } else {
                    $modx->log(modX::LOG_LEVEL_ERROR, '[phpThumbOf] Could not cache thumb to file at: ' . $cacheKey . ($verbose ? ' - Debug: ' . print_r($phpThumb->debugmessages, true) : ''));
                }
            } else {
                /* usually caused by invalid request input, so only logged in debug mode */
                $modx->log(modX::LOG_LEVEL_DEBUG, '[phpThumbOf] Could not generate thumbnail: ' . $src . ' - Debug: ' . print_r($phpThumb->debugmessages, true));
            }
        }

        if (!is_file($cacheKey) || !is_readable($cacheKey)) {
            return $this->notFound();
        }

        if (!headers_sent()) {
            $headers = $modx->request->getHeaders();
            $mtime = filemtime($cacheKey);
            if (isset($headers['If-Modified-Since']) && strtotime($headers['If-Modified-Since']) == $mtime) {
                // cache is good, send 304
                header('Last-Modified: '.gmdate('D, d M Y H:i:s', $mtime).' GMT', true, 304);
                exit();
            }
            header('Last-Modified: '.gmdate('D, d M Y H:i:s', $mtime).' GMT', true, 200);
            $phpThumb->setOutputFormat();
            header('Content-Type: '.phpthumb_functions::ImageTypeToMIMEtype($phpThumb->thumbnailFormat));
            header('Content-Disposition: inline; filename="'.phpthumb_functions::SanitizeFilename(basename($src)).'"');
        }

        return file_get_contents($cacheKey);
    }

    /**
     * Validate the phpThumb filters (fltr[] parameter).
     *
     * - wmi, over, mask: only local image files are allowed (no URL schemes, protocol-relative or UNC paths)
     * - wmt: the text length and font size are limited, the font has to be a local file
     * - size and the wmi max width/height: limited to the maximum dimension
     *
     * @param mixed $filters
     * @param int $maxDimension
     * @return array
     */
    private function sanitizeFilters($filters, $maxDimension) {
        if (!is_array($filters)) {
            $filters = array($filters);
        }
        $sanitized = array();
        foreach ($filters as $filter) {
            if (count($sanitized) >= self::MAX_FILTERS) {
                break;
            }
            if (!is_string($filter) || $filter === '' || strpos($filter, "\0") !== false) {
                continue;
            }
            $parts = explode('|', $filter);
            switch ($parts[0]) {
                case 'wmi':
                case 'over':
                case 'mask':
                    /* filter|filename|... */
                    if (!isset($parts[1]) || $parts[1] === '' || !$this->isLocalPath($parts[1])) {
                        continue 2;
                    }
                    if ($parts[0] === 'wmi') {
                        /* wmi|filename|alignment|opacity|margin_x|margin_y|angle - the margins are the
                           max width/height of the watermark, if the alignment is a size like 50x75 */
                        foreach (array(4, 5) as $i) {
                            if (isset($parts[$i]) && $parts[$i] !== '') {
                                $parts[$i] = $this->limitSize($parts[$i], $maxDimension);
                            }
                        }
                    }
                    break;
                case 'wmt':
                    /* wmt|text|size|alignment|color|font|opacity|margin|angle|bg_color|bg_opacity|fillextend|lineheight */
                    $text = isset($parts[1]) ? $parts[1] : '';
                    $parts[1] = function_exists('mb_substr') ? mb_substr($text, 0, self::MAX_WATERMARK_TEXT_LENGTH, 'UTF-8') : substr($text, 0, self::MAX_WATERMARK_TEXT_LENGTH);
                    if (isset($parts[2]) && $parts[2] !== '') {
                        $parts[2] = is_numeric($parts[2]) ? min(abs((float)$parts[2]), self::MAX_WATERMARK_FONT_SIZE) : '';
                    }
                    if (isset($parts[5]) && $parts[5] !== '' && !$this->isLocalPath($parts[5])) {
                        continue 2;
                    }
                    break;
                case 'size':
                    /* size|width|height|stretch */
                    foreach (array(1, 2) as $i) {
                        if (isset($parts[$i]) && $parts[$i] !== '') {
                            $parts[$i] = $this->limitSize($parts[$i], $maxDimension);
                        }
                    }
                    break;
            }
            $sanitized[] = implode('|', $parts);
        }
        return $sanitized;
    }

    /**
     * Limit a size value to the maximum dimension. Fractions between 0 and 1 are
     * relative sizes in phpThumb and are kept, non-numeric values are emptied.
     *
     * @param string $value
     * @param int $maxDimension
     * @return float|int|string
     */
    private function limitSize($value, $maxDimension) {
        if (!is_numeric($value)) {
            return '';
        }
        $value = abs((float)$value);
        if ($value > 0 && $value < 1) {
            return $value;
        }
        return min((int)round($value), $maxDimension);
    }

    /**
     * Check that a path is a local path and not a URL, protocol-relative or UNC path.
     *
     * @param string $path
     * @return bool
     */
    private function isLocalPath($path) {
        return !preg_match('#^[a-z][a-z0-9+.\-]*:#i', $path) && !preg_match('#^(//|\\\\\\\\)#', $path) && strpos($path, "\0") === false;
    }

    /**
     * Convert a http(s) URL that points to this site into a local path.
     *
     * @param string $url
     * @return string|false The URL path or false if the URL is not allowed
     */
    private function localPathFromUrl($url) {
        if (!preg_match('#^https?://#i', $url) || preg_match('#[@\\\\\s]#', $url)) {
            return false;
        }
        $parts = parse_url($url);
        if (empty($parts['host']) || empty($parts['path']) || isset($parts['user']) || isset($parts['pass'])) {
            return false;
        }
        $host = strtolower($parts['host']);

        $ownHosts = array();
        $httpHost = $this->modx->getOption('http_host', null, '');
        if ($httpHost !== '') {
            $ownHosts[] = strtolower(preg_replace('#:\d+$#', '', $httpHost));
        }
        $siteUrlHost = parse_url($this->modx->getOption('site_url', null, ''), PHP_URL_HOST);
        if ($siteUrlHost) {
            $ownHosts[] = strtolower($siteUrlHost);
        }

        if (!in_array($host, $ownHosts, true)) {
            return false;
        }
        return $parts['path'];
    }

    /**
     * Send a 404 status without leaking any details.
     *
     * @return string
     */
    private function notFound() {
        if (!headers_sent()) {
            header('HTTP/1.1 404 Not Found', true, 404);
        }
        return '';
    }
}
return 'GalleryPhpthumbProcessor';
