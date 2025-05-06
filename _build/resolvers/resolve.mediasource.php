<?php
/**
 * Gallery
 *
 * Copyright 2010-2012 by Shaun McCormick <shaun@modx.com>
 *
 * Gallery is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Gallery is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Gallery; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package gallery
 */
/**
 * Resolve changes to structure that files are stored in
 *
 * @package gallery
 * @subpackage build
 */
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
        /**
         * @var $modx \modX
         */
            $modx =& $object->xpdo;
            if (empty($modx->version)) {
                $modx->getVersionData();
            }
            $version = (int) $modx->version['version'];
            if ($version > 2) {
                $c = $modx->newQuery(\MODX\Revolution\Sources\modMediaSource::class);
                $c->where(['class_key' => 'GalleryAlbumsMediaSource']);
                $albums = $modx->getCollection(\MODX\Revolution\Sources\modMediaSource::class, $c);
                foreach($albums as $album) {
                    $album->set('class_key', 'Gallery\\Model\\GalleryAlbumsMediaSource');
                    $album->save();
                }
            }
            break;
    }
}
