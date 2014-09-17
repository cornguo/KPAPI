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
        if (NULL !== ($posData = $api->getPOS($article->id))) {
            echo "\t  >> names: " . implode(' ', array_keys((array)$posData->names)) . "\n";
            echo "\t  >> places: " . implode(' ', array_keys((array)$posData->places)) . "\n";
        }
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

echo "\n== DUMP MUSICS ==\n";

$playlists = $api->musics;

foreach ($playlists as $key => $playlist) {
    $playlistName = $playlists->getData($key)->name;
    echo ">> {$playlistName}\n";
    foreach ($playlist as $music) {
        echo "\t>> {$music->groupname} - {$music->song_name}\n";
    }
    echo "\n";
}

echo "\n== DUMP FINANCIAL ==\n";

$financials = $api->financial->getData();

foreach ($financials as $financial) {
    $price = intval($financial->price);
    if ('expense' === $financial->type) {
        $price *= -1;
    }
    echo ">> [{$financial->start_date} ~ {$financial->end_date}]\t{$price}\t{$financial->account}: {$financial->label}\n";
}
