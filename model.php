<?php

function add_music($data, $data1, $id_user)
{

    require("connect.php");
    $dns = "mysql:dbname=" . BASE . ";host=" . SERVER;
    try {
        $connexion = new PDO($dns, USER, PASSWD);

    } catch (PDOException $e) {
        printf('Echec de la connexion : %s\n', $e->getMessage());
        exit();

    }

    $track = $data->name;
    $artiste = $data->artists[0]->name;
    $album = $data->album->name;
    $image = $data->album->images[1]->url;

    $year = $data1->release_date;
    $preview = $data->preview_url;


    $sql = "INSERT INTO tracks(id_user,image,track,artiste,album,annee,preview) values (?,?,?,?,?,?,?)";
    $stmt = $connexion->prepare($sql);
    return $stmt->execute(array($id_user, $image, $track, $artiste, $album, $year, $preview));
}


function delete_music($id)
{

    require("connect.php");
    $dns = "mysql:dbname=" . BASE . ";host=" . SERVER;
    try {
        $connexion = new PDO($dns, USER, PASSWD);

    } catch (PDOException $e) {
        printf('Echec de la connexion : %s\n', $e->getMessage());
        exit();

    }

    $sql = "DELETE FROM tracks WHERE id = ?";
    $stmt = $connexion->prepare($sql);
    return $stmt->execute(array($id));

}

function create_item($dom, $item, $data) {
    if (is_array($data)) {
        foreach ($data as $key => $val) {
            //  ????
            if($key!='id' and $key!='id_user'){

                $element = $dom->createElement($key);
                $item->appendchild($element);

                //  ?????
                $text = $dom->createTextNode($val);
                $element->appendchild($text);
            }

        }
    }   //  end if
}   //  end function

function get_item_add($items, $id_user){

    require("connect.php");
    $dns = "mysql:dbname=" . BASE . ";host=" . SERVER;
    try {
        $connexion = new PDO($dns, USER, PASSWD);

    } catch (PDOException $e) {
        printf('Echec de la connexion : %s\n', $e->getMessage());
        exit();

    }
    foreach($items as $item){

        $image=$item->getElementsByTagName('image')->item(0)->nodeValue;
        $track=$item->getElementsByTagName('track')->item(0)->nodeValue;
        $artiste=$item->getElementsByTagName('artiste')->item(0)->nodeValue;
        $album=$item->getElementsByTagName('album')->item(0)->nodeValue;
        $annee=$item->getElementsByTagName('annee')->item(0)->nodeValue;
        $preview=$item->getElementsByTagName('preview')->item(0)->nodeValue;

        $sql = "INSERT INTO tracks(id_user,image,track,artiste,album,annee,preview) values (?,?,?,?,?,?,?)";
        $stmt = $connexion->prepare($sql);
        $stmt->execute(array($id_user, $image, $track, $artiste, $album, $annee, $preview));
    }
}


