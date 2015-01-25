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
 * Translated by Jakub Kalina (modx@jakubkalina.pl)
 *
 * @var array $_lang
 * @package gallery
 * @subpackage lexicon
 */
/* Gallery Snippet */
$_lang['gallery.activecls_desc'] = 'Klasa CSS dodawana jeśli aktywny obraz (określony w snippecie GalleryItem) jest wyświetlany.';
$_lang['gallery.album_desc'] = 'Załaduje jedynie obrazy ze wskazanego albumu. Może być to zarówno ID jak i nazwa albumu.';
$_lang['gallery.albumrequestvar_desc'] = 'Jeżeli checkForRequestAlbumVar jest prawdą, będzie sprawdzać zmienną REQUEST o
wskazanej nazwie w poszukiwaniu identyfikatora albumu do wyświetlenia.';
$_lang['gallery.checkforrequestalbumvar_desc'] = 'Jeżeli 1, to jeżeli znaleziono zmienną "album" w tablicy REQUEST, użyje jej wartości jako
własności album w snippecie.';
$_lang['gallery.checkforrequesttagvar_desc'] = 'Jeżeli 1, to jeżeli znaleziono zmienną "tag" w tablicy REQUEST, użyje jej wartości jako
własności tag w snippecie.';
$_lang['gallery.containertpl_desc'] = 'Opcjonalny fragment, w który opakowane będą dane wyjściowe.';
$_lang['gallery.dir_desc'] = 'Kierunek sortowania obrazów.';
$_lang['gallery.imagefar_desc'] = 'Wartość "far" dla phpThumb przetwarzającego obrazek, służy ustawieniu powiększania z zachowaniem
proporcji (force aspect ratio).';
$_lang['gallery.imageheight_desc'] = 'Określa wysokość aktualnie wyświetlanego obrazu, jeśli jest obsługiwane przez wtyczkę.';
$_lang['gallery.imagegetparam_desc'] = 'Parametr GET używany jeżeli obrazy nie są linkowane bezpośrednio. Upewnij się, że odpowiada on
właściwości getParam w wywołaniu snippetu GalleryItem.';
$_lang['gallery.imageproperties_desc'] = 'Obiekt JSON zawierający parametry przekazywane do phpThumb jako ustawienia obrazu.';
$_lang['gallery.imagequality_desc'] = 'Wartość "q" dla phpThumb oznaczająca jakość obrazu.';
$_lang['gallery.imagewidth_desc'] = 'Określa szerokość aktualnie wyświetlanego obrazu, jeśli jest obsługiwane przez wtyczkę.';
$_lang['gallery.imagezoomcrop_desc'] = 'Określa czy aktualnie wyświetlany obraz powinien być powiększony z przycięciem (zoom crop), jeśli
 jest to obsługiwane przez wtyczkę.';
$_lang['gallery.itemcls_desc'] = 'Klasa CSS dla każdej z miniatur.';
$_lang['gallery.limit_desc'] = 'Jeśli różne od zera, pokaże jedynie określoną przez limit liczbę obrazów.';
$_lang['gallery.linktoimage_desc'] = 'Jeżeli prawda, będzie tworzył łącza bezpośrednio do obrazu, w przeciwnym wypadku doda parametr GET do
 URL w celu ładowania obrazu przez snippet GalleryItem.';
$_lang['gallery.plugin_desc'] = 'Nazwa wtyczki używanej do wyświetlania galerii. Proszę zobaczyć oficjalną dokumentację w celu uzyskania
listy dostępnych wtyczek.';
$_lang['gallery.pluginpath_desc'] = 'Nie można załadować wtyczki "[[+name]]" ze ścieżki: [[+path]]';
$_lang['gallery.showinactive_desc'] = 'Jeżeli 1, wyświetli także obrazy oznaczone jako nieaktywne.';
$_lang['gallery.sort_desc'] = 'Pole, według którego sortować obrazy.';
$_lang['gallery.start_desc'] = 'Indeks, od którego rozpocząć pobieranie obrazów jeśli jest ustawiony limit ich liczby. Działa
podobnie do klauzuli order by start w języku SQL.';
$_lang['gallery.tag_desc'] = 'Ładuje jedynie obrazy ze wskazanym znacznikiem.';
$_lang['gallery.tagrequestvar_desc'] = 'Jeżeli checkForRequestTagVar jest prawdą, będzie sprawdzać zmienną REQUEST o
wskazanej nazwie w celu ustawienia znacznika.';
$_lang['gallery.toplaceholder_desc'] = 'Jeżeli ustawione, przekaże wyjście do placeholdera o podanej nazwie i wywołanie snippetu nic nie
zwróci.';
$_lang['gallery.thumbfar_desc'] = 'Wartość "far" dla phpThumb przetwarzającego miniaturę, służy ustawieniu powiększania z zachowaniem
proporcji (force aspect ratio).';
$_lang['gallery.thumbheight_desc'] = 'Wysokość generowanych miniatur, wyrażona w pikselach.';
$_lang['gallery.thumbproperties_desc'] = 'Obiekt JSON zawierający parametry przekazywane do phpThumb jako ustawienia miniatury.';
$_lang['gallery.thumbquality_desc'] = 'Wartość "q" dla phpThumb oznaczająca jakość miniatury.';
$_lang['gallery.thumbtpl_desc'] = 'Fragment, który zostanie użyty jako szablon (tpl) dla każdej z miniatur.';
$_lang['gallery.thumbwidth_desc'] = 'Szerokość generowanych miniatur, wyrażona w pikselach.';
$_lang['gallery.thumbzoomcrop_desc'] = 'Czy miniatura ma być powiększona z przycięciem (zoom crop).';
$_lang['gallery.usecss_desc'] = 'Czy używać predefiniowane klasy CSS dla snippetu.';

/* GalleryAlbums Snippet */
$_lang['galleryalbums.albumrequestvar_desc'] = 'Jeżeli checkForRequestAlbumVar jest prawdą, będzie sprawdzać zmienną REQUEST o wskazanej nazwie w poszukiwaniu identyfikatora albumu do
wyświetlenia.';
$_lang['galleryalbums.albumcoversort_desc'] = 'Pole, według którego sortować w celu wybrania okładki albumu. Aby wybrać pierwszy obraz,
 użyj "rank". Aby wybrać losowy obraz, użyj "random".';
$_lang['galleryalbums.albumcoversortdir_desc'] = 'Kierunek sortowania przy wyborze okładki albumu. Przyjmowane wartości to "ASC" lub
"DESC".';
$_lang['galleryalbums.dir_desc'] = 'Kierunek sortowania wyników.';
$_lang['galleryalbums.limit_desc'] = 'Jeśli różne od zera, pokaże jedynie określoną przez limit liczbę wyników.';
$_lang['galleryalbums.parent_desc'] = 'Pobierz tylko albumy, których albumem nadrzędnym jest określony przez podane ID.';
$_lang['galleryalbums.prominentonly_desc'] = 'Jeśli 1, wyświetli tylko albumy oznaczone jako "widoczne".';
$_lang['galleryalbums.rowcls_desc'] = 'Klasa CSS dodawana do każdej krotki albumu.';
$_lang['galleryalbums.rowtpl_desc'] = 'Fragment użyty przez każdą krotkę albumu.';
$_lang['galleryalbums.showall_desc'] = 'Jeśli 1, pokaże wszystkie albumy, niezależnie od albumu nadrzędnego.';
$_lang['galleryalbums.showinactive_desc'] = 'Jeśli 1, pokaże także albumy ustawione jako nieaktywne.';
$_lang['galleryalbums.showall_desc'] = 'Jeśli 0, ukryje nazwę albumu w szablonie krotki (row tpl).';
$_lang['galleryalbums.start_desc'] = 'Indeks, od którego rozpocząć wyniki.';
$_lang['galleryalbums.sort_desc'] = 'Pole, według którego sortować wyniki.';
$_lang['galleryalbums.thumbfar_desc'] = 'Wartość "far" dla phpThumb przetwarzającego miniaturę okładki albumu, służy ustawieniu
powiększania z zachowaniem proporcji (force aspect ratio).';
$_lang['galleryalbums.thumbheight_desc'] = 'Wysokość generowanej miniatury okładki albumu, wyrażona w pikselach.';
$_lang['galleryalbums.thumbproperties_desc'] = 'Obiekt JSON zawierający parametry przekazywane do phpThumb jako ustawienia miniatury
okładki albumu.';
$_lang['galleryalbums.thumbquality_desc'] = 'Wartość "q" dla phpThumb oznaczająca jakość miniatury okładki albumu.';
$_lang['galleryalbums.thumbwidth_desc'] = 'Długość wygenerowanej miniatury okładki albumu, wyrażona w pikselach.';
$_lang['galleryalbums.thumbzoomcrop_desc'] = 'Czy powiększać z przycięciem (zoom crop) miniaturę okładki albumu.';
$_lang['galleryalbums.toplaceholder_desc'] = 'Jeśli nie jest pusty, ustawia wyjście do placeholdera z tą wartością.';

/* GalleryItem Snippet */
$_lang['galleryitem.albumrequestvar_desc'] = 'Zmienna REQUEST używana przy łączach albumów.';
$_lang['galleryitem.albumseparator_desc'] = 'Separator ciągu znaków dla każdego albumu określonego dla obrazu.';
$_lang['galleryitem.albumtpl_desc'] = 'Nazwa fragmentu, który będzie użyty dla każdego albumu określonego dla obrazu.';
$_lang['galleryitem.id_desc'] = 'ID obrazu do wyświetlenia.';
$_lang['galleryitem.imagefar_desc'] = 'Wartość "far" dla phpThumb przetwarzającego obraz, służy ustawieniu powiększania z zachowaniem
proporcji (force aspect ratio).';
$_lang['galleryitem.imageheight_desc'] = 'Określa maksymalną wysokość aktualnie wyświetlanego obrazu, jeśli jest obsługiwane przez wtyczkę.';
$_lang['galleryitem.imageproperties_desc'] = 'Obiekt JSON zawierający parametry przekazywane do phpThumb jako ustawienia generowanego
obrazu.';
$_lang['galleryitem.imagequality_desc'] = 'Wartość "q" dla phpThumb oznaczająca jakość obrazu.';
$_lang['galleryitem.imagewidth_desc'] = 'Określa maksymalną szerokość aktualnie wyświetlanego obrazu, jeśli jest obsługiwane przez
wtyczkę.';
$_lang['galleryitem.imagezoomcrop_desc'] = 'Czy powiększać z przycięciem (zoom crop) obraz.';
$_lang['galleryitem.tagrequestvar_desc'] = 'Zmienna REQUEST używana przy łączach znaczników.';
$_lang['galleryitem.tagseparator_desc'] = 'Separator ciągów znaków listy znaczników dla obrazu.';
$_lang['galleryitem.tagsortdir_desc'] = 'Kierunek sortowania tagów określonych dla obrazu.';
$_lang['galleryitem.tagtpl_desc'] = 'Nazwa fragmentu, który powinien być użyty dla każdego znacznika określonego dla obrazu.';
$_lang['galleryitem.toplaceholders_desc'] = 'Jeśli prawda, ustawi właściwości obrazu do placeholderów, w przeciwnym wypadku użyje
parametru tpl aby wygenerować fragment.';
$_lang['galleryitem.toplaceholdersprefix_desc'] = 'Opcjonalny. Prefiks który będzie dodawany do placeholderów ustawionych przez ten
snipper. Działa tylko jeśli toPlaceholders jest ustawiona na true.';
$_lang['galleryitem.tpl_desc'] = 'Nazwa fragmentu używanego jeśli wartość toPlaceholders jest ustawiony jako false.';
$_lang['galleryitem.thumbfar_desc'] = 'Wartość "far" dla phpThumb oznaczająca powiększanie z zachowaniem proporcji.';
$_lang['galleryitem.thumbheight_desc'] = 'Maksymalna wysokość generowanej miniatury, wyrażona w pikselach.';
$_lang['galleryitem.thumbproperties_desc'] = 'Obiekt JSON zawierający parametry przekazywane do phpThumb jako ustawienia miniatury.';
$_lang['galleryitem.thumbquality_desc'] = 'Wartość "q" dla phpThumb oznaczająca jakość miniatury.';
$_lang['galleryitem.thumbwidth_desc'] = 'Maksymalna szerokość generowanej miniatury, wyrażona w pikselach.';
$_lang['galleryitem.thumbzoomcrop_desc'] = 'Czy używać przycięcie z powiększeniem dla miniatury.';
