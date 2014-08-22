<?php
require 'KPAPI.php';

$key = NULL;

if (NULL === $key) {
    echo "You should set your key before use it.\nI'll continue however.\n\n";
}

$api = new CornGuo\KPAPI($key);

echo "\n== DUMP ARTICLES ==\n";

$categories = $api->category;

foreach ($categories as $key => $articles) {
    $categoryName = $categories->getData($key)->name;
    echo ">> {$categoryName}\n";
    foreach ($articles as $article) {
        $articleName = $article->title;
        echo "\t>> {$articleName}\n";
    }
    echo "\n";
}

echo "\n== DUMP ALBUMS ==\n";

$albums = $api->albums;

foreach ($albums as $key => $album) {
    $albumName = $albums->getData($key)->title;
    echo ">> {$albumName}\n";
    foreach ($album->photos as $photo) {
        $image = $photo->images->original;
        echo "\t>> {$image}\n";
    }
    echo "\n";
}

echo "\n== DUMP VIDEOS ==\n";

$playlists = $api->videos;

foreach ($playlists as $key => $playlist) {
    $playlistName = $playlists->getData($key)->title;
    echo ">> {$playlistName}\n";
    foreach ($playlist as $video) {
        echo "\t>> {$video->title}\n";
    }
    echo "\n";
}
