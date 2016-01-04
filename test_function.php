


<?php

require 'vendor/autoload.php';

$api = new SpotifyWebAPI\SpotifyWebAPI();



    $tracks = $api->getArtist('0OdUWJ0sBjDrqHygGUXeCF');

    print_r($tracks);
?>