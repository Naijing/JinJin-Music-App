<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title> Interface de gestion de musique </title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"/>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css"/>

</head>
<body>
<div class="container">

    <?php
    session_start();
    if (empty($_SESSION['valid_userid'])) {
        header("location:loginerror.php");
        exit();
    } ?>


    <nav id="nav" class="navbar navbar-default">
        <a href="http://localhost/gtunes/musicmanage.php" class="navbar-brand">Home</a>
        <ul class="nav navbar-nav">
            <li><a href="http://localhost/gtunes/connexion.html">Deconnexion</a></li>

            <li class="dropdown">
                <a href="#" data-toggle="dropdown"><?php echo $_SESSION['valid_username'] ?><span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="http://localhost/gtunes/api/playlist">Playlist</a></li>
                    <li><a href="http://localhost/gtunes/api/playlist/exportxml">Exporter en xml</a></li>
                    <li role="separator" class="divider"></li>
                    <li>
                        <form class="navbar-form navbar-left" action="http://localhost/gtunes/api/playlist/importxml"
                              enctype="multipart/form-data" method="post">

                            <input type="file" name="myfile" required/>

                            <button type="submit" class="btn btn-sm">Importer un fichier xml</button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    

        <div class="jumbotron">

            <?php echo "<h1>Bienvenue " . $_SESSION['valid_username'] . "!</h1>" ?>


            <?php

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


            ?>

            <div class="lead">
                <p><h4>Allez voir sur Spotify! Vous pouvez chercher vos musiques par titre, artiste
                    et album.</h4></p>


                <form class="form-inline" action="searchProcess.php" method="post" role="search">

                    <input type="text" name="cle" class="form-contro" placeholder="Mots clés" required autofocus/>

                    <input type="radio" name="item" value="track" required/> Titre de musique
                    <input type="radio" name="item" value="artist" required/> Artiste
                    <input type="radio" name="item" value="album" required/> Album
                    <button type="submit" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-search"
                                                                               aria-hidden="true"></span>&nbsp
                        Chercher
                    </button>


                </form>


            </div>

        </div>


        </br></br>

        <h1 align="center"> Playlist </h1></br>

        <table class="table table-hover">
            <thead>
            <tr>
                <th> Pochette </th>
                <th> Titre </th>
                <th> Artiste </th>
                <th> Album </th>
                <th> Année </th>
                <th> Préview </th>
                <th> Supprimer </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($resulat as $res): ?>


                <tr>
                    <td><img src="<?php echo $res['image'] ?>" width="60" height="60"/></td>
                    <td> <?php echo $res['track'] ?> </td>
                    <td><?php echo $res['artiste'] ?></td>
                    <td> <?php echo $res['album'] ?></td>
                    <td><?php echo $res['annee'] ?></td>
                    <td>
                        <audio src="<?php echo $res['preview'] ?>" controls="controls"></audio>
                    </td>
                    <td align="center"><a
                            href="http://localhost/gtunes/search.php/api/delete/<?php echo $res['id'] ?>">
                            <span class="glyphicon glyphicon-remove-sign"></span> </a></td>

                </tr>

            <?php endforeach; ?>
            </tbody>
        </table>


</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"
        integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ=="
        crossorigin="anonymous"></script>


</body>
</html>