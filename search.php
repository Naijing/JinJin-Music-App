<?php
require 'vendor/autoload.php';
require 'model.php';

use Symfony\Component\HttpFoundation\Request;

$api = new SpotifyWebAPI\SpotifyWebAPI();

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

//$app['asset_path'] = 'C:\xampp\htdocs\gtunes';



//obtenir des infos de track par item 'track' et des mots clés
$app->get('/api/search/track/{name}', function ($name) use ($app, $api) {
    session_start();
    if (empty($_SESSION['valid_userid'])) {
        header("location:loginerror.php");
        exit();
    }
    $name_user = $_SESSION['valid_username'];
    $id_user = $_SESSION['valid_userid'];


    $results = $api->search($name, 'track');

    return $app['twig']->render('result_by_track.html.twig', array(
        'results' => $results,
        'name_user' => $name_user,
        'id_user' => $id_user,

    ));

});


////obtenir des infos d'artist par item 'artist' et des mots clés
$app->get('/api/search/artist/{name}', function ($name) use ($api, $app) {

    session_start();
    if (empty($_SESSION['valid_userid'])) {
        header("location:loginerror.php");
        exit();
    }
    $name_user = $_SESSION['valid_username'];
    $id_user = $_SESSION['valid_userid'];

    $results = $api->search($name, 'artist');

    return $app['twig']->render('info_artist.html.twig', array(
        'results' => $results,
        'name_user' => $name_user,

    ));


});


//get artists top tracks by artist's id
$app->get('/api/artist/{id}/top-tracks', function ($id) use ($api, $app) {

    session_start();
    if (empty($_SESSION['valid_userid'])) {
        header("location:loginerror.php");
        exit();
    }
    $name_user = $_SESSION['valid_username'];
    $id_user = $_SESSION['valid_userid'];


    $tracks = $api->getArtistTopTracks($id, array(
        'country' => 'fr'
    ));

    $artist = $api->getArtist($id);


    return $app['twig']->render('top_tracks_artist.html.twig', array(
        'tracks' => $tracks, 'artist' => $artist,
        'name_user' => $name_user,

    ));

})->bind('getartiststoptracksbyartistid');


//Get an Artist's Albums by artist ID
$app->get('/api/artist/{id}/albums', function ($id) use ($api, $app) {

    session_start();
    if (empty($_SESSION['valid_userid'])) {
        header("location:loginerror.php");
        exit();
    }
    $name_user = $_SESSION['valid_username'];
    $id_user = $_SESSION['valid_userid'];

    $albums = $api->getArtistAlbums($id);

    $artist = $api->getArtist($id);

    return $app['twig']->render('albums_artist.html.twig', array(
        'albums' => $albums, 'artist' => $artist,
        'name_user' => $name_user,

    ));


})->bind('getartistsalbumsbyartistid');


////obtenir des infos d'album par item 'album' et des mots clés
$app->get('/api/search/album/{name}', function ($name) use ($api, $app) {

    session_start();
    if (empty($_SESSION['valid_userid'])) {
        header("location:loginerror.php");
        exit();
    }
    $name_user = $_SESSION['valid_username'];
    $id_user = $_SESSION['valid_userid'];

    $results = $api->search($name, 'album');

    return $app['twig']->render('info_album.html.twig', array(
        'results' => $results,
        'name_user' => $name_user,
    ));


});


//get album's tracks by album's id
$app->get('/api/album/{id}/tracks', function ($id) use ($api, $app) {
    session_start();
    if (empty($_SESSION['valid_userid'])) {
        header("location:loginerror.php");
        exit();
    }
    $name_user = $_SESSION['valid_username'];
    $id_user = $_SESSION['valid_userid'];

    $album = $api->getAlbum($id);


    return $app['twig']->render('tracks_album.html.twig', array(
        'album' => $album,
        'name_user' => $name_user,

    ));
})->bind('gettracksbyalbumid');


$app->get('/api/add/{id}', function ($id) use ($api,$app) {

    session_start();
    if (empty($_SESSION['valid_userid'])) {
        header("location:loginerror.php");
        exit();
    }
    $name_user = $_SESSION['valid_username'];
    $id_user = $_SESSION['valid_userid'];

    /* $id_user = $_REQUEST['user_id'];
     $pwd = $_REQUEST['user_pwd'];*/

    $res = $api->getTrack($id);
    $id_album = $res->album->id;
    $album = $api->getAlbum($id_album);

    add_music($res, $album, $id_user);

    return $app->redirect($app["url_generator"]->generate("playlist"));

})->bind('add');


$app->get('/api/playlist', function () use ($app) {

    session_start();
    if (empty($_SESSION['valid_userid'])) {
        header("location:loginerror.php");
        exit();
    }
    $name_user = $_SESSION['valid_username'];
    $id_user = $_SESSION['valid_userid'];

    require("connect.php");
    $dns = "mysql:dbname=" . BASE . ";host=" . SERVER;
    try {
        $connexion = new PDO($dns, USER, PASSWD);

    } catch (PDOException $e) {
        printf('Echec de la connexion : %s\n', $e->getMessage());
        exit();

    }

    $sql = "select distinct *  from tracks where id_user=$id_user";
    $stmt = $connexion->prepare($sql);
    $stmt->execute();
    $resulat = $stmt->fetchAll(PDO::FETCH_ASSOC);


    return $app['twig']->render('playlist.html.twig', array(
        'resultat' => $resulat,
        'name_user' => $name_user,
    ));

})->bind('playlist');

$app->get('/api/playlist/exportxml', function () use ($app) {

    session_start();
    if (empty($_SESSION['valid_userid'])) {
        header("location:loginerror.php");
        exit();
    }
    $name_user = $_SESSION['valid_username'];
    $id_user = $_SESSION['valid_userid'];

    require("connect.php");
    $dns = "mysql:dbname=" . BASE . ";host=" . SERVER;
    try {
        $connexion = new PDO($dns, USER, PASSWD);

    } catch (PDOException $e) {
        printf('Echec de la connexion : %s\n', $e->getMessage());
        exit();

    }

    $sql = "select distinct *  from tracks where id_user=$id_user";
    $stmt = $connexion->prepare($sql);
    $stmt->execute();
    $data_array = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $implementation = new DOMImplementation();
    $dtd = $implementation->createDocumentType('playlist', "", 'playlist.dtd');
    $dom = $implementation->createDocument('','', $dtd);
    $dom->encoding = 'utf-8';

    //$dom=new DomDocument('1.0', 'utf-8');
    $playlist = $dom->createElement('playlist');
    $dom->appendchild($playlist);
    foreach ($data_array as $data) {
        $item = $dom->createElement('item');
        $playlist->appendchild($item);

        create_item($dom, $item, $data);
    }
    $dom->save("playlist.xml");

    if($dom->validate()){
        $file_name="playlist.xml";
        if(file_exists($file_name)){
            $fp=fopen($file_name,'r');
            $file_size=filesize($file_name);
            header("Content-type: application/octect-stream");
            header("Accept-Ranges: bytes");
            header("Accept-Length: $file_size");
            header("Content-Disposition: attachment; filename=".$file_name);

            $buffer=1024;
            while(!feof($fp)){
                $file_data=fread($fp, $buffer);
                echo $file_data;
            }
            fclose($fp);

        }

        return '';

    }
})->bind('exportxml');


$app->post('/api/playlist/importxml', function () use ($app) {

    session_start();
    if (empty($_SESSION['valid_userid'])) {
        header("location:loginerror.php");
        exit();
    }
    $name_user = $_SESSION['valid_username'];
    $id_user = $_SESSION['valid_userid'];

    $uploaded_file=$_FILES['myfile']['tmp_name'];
    //print_r($_FILES) ;

    //echo file_get_contents($uploaded_file);
    $xmldoc=new DOMDocument();
    $xmldoc->load($uploaded_file);
    $items=$xmldoc->getElementsByTagName("item");

    get_item_add($items, $id_user);

    /*$item1=$items->item(0);
    $item1_track=$item1->getElementsByTagName('track');
    echo $item1_track->item(0)->nodeValue;*/



    return $app->redirect($app["url_generator"]->generate("playlist"));

})->bind('importxml');



$app->get('/api/delete/{id}', function ($id) use ($api, $app) {

    /*session_start();
    if(empty($_SESSION['valid_userid'])){

        return new \Symfony\Component\HttpFoundation\Response('FORBIDDEN', 403);
    }
    $name_user=$_SESSION['valid_username'];
    $id_user=$_SESSION['valid_userid']; */

    delete_music($id);

    return $app->redirect($app["url_generator"]->generate("playlist"));

})->bind('delete');


$app->run();