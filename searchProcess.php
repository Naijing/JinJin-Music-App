<?php
/**
 * Created by PhpStorm.
 * User: jinjin
 * Date: 11/11/2015
 * Time: 10:38
 */

$cle = $_POST['cle'];
$item = $_POST['item'];


switch ($item) {
    case 'track':
        header("location: http://localhost/gtunes/api/search/track/$cle ");
        break;
    case 'artist':
        header("location: http://localhost/gtunes/api/search/artist/$cle ");
        break;
    case 'album':
        header("location: http://localhost/gtunes/api/search/album/$cle ");
        break;

}

?>