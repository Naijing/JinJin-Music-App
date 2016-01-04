<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <style>
        .d1 {
            width: 400px;
            height: 400px;
            position: absolute;
            top: 20%;
            left: 30%;
        }
    </style>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Django-Entreprise</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"
          integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ=="
          crossorigin="anonymous">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css"
          integrity="sha384-aUGj/X2zp5rLCbBxumKTCw2Z50WgIr1vs/PFN4praOTvYXWlVyh2UtNUU0KAUhAX" crossorigin="anonymous">

    <style>
        body {

            padding-top: 70px;
            padding-left: 50px;
            padding-right: 50px;
        }
    </style>


</head>
<body>

<?php
session_start();
if (empty($_SESSION['valid_userid'])) {
    header("location:loginerror.php");
    exit();
}


echo "<h2>Bienvenu " . $_SESSION['valid_username'] . ", Cherchez des musiques!</h2>"
?>

<p><a href="http://localhost/gtunes/search.php/api/playlist">Voir votre playlist</a></p>

<p><a href="connexion.html">Déconnecter</a></p>

<p><a href="musicmanage.php">Retourner à votre page d'accueille</a></p>


<div class="d1">
    <p>

    <h1>Vous pouvez chercher des informations de music par titres, artistes et albums</h1></p>

    <form action="searchProcess.php" method="post">

        <p><label> Mots clés </label> <input type="text" name="cle"/></p>

        <p><label> Chercher par </label></p>

        <p><input type="radio" name="item" value="track"/> Titre de musique </p>

        <p><input type="radio" name="item" value="artist"/> Artiste</p>

        <p><input type="radio" name="item" value="album"/> Album </p>
        </p>


        <input type="submit" value="Chercher"/>

    </form>

</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"
        integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ=="
        crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>


</body>
</html>