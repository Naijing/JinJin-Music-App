<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <title> Liste de musique </title>
</head>
<body>

<?php
require("connect.php");
$dns = "mysql:dbname=" . BASE . ";host=" . SERVER;
try {
    $connexion = new PDO($dns, USER, PASSWD);

} catch (PDOException $e) {
    printf('Echec de la connexion : %s\n', $e->getMessage());
    exit();

}

//$name=$_GET['name'];
//$sql="select * from music where userid=(select id from user where name=$name)";
$sql = "select * from music where userid=1";
$stmt = $connexion->prepare($sql);
$stmt->execute();
echo "<h1>Liste de vos musiques</h1>";
echo "<table border='1px' bordercolor='green' cellspacing='0px' width='500px'>";
echo "<tr><th>Titre de chanson </th><th>Artiste de chanson </th><th>Titre d'album</th><th>Genre </th><th>Année </th></tr>";


while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr><td>{$row['chanson']}</td><td>{$row['artiste']}</td>
    <td>{$row['album']}</td><td>{$row['genre']}</td><td>{$row['annee']}</td>
    <td><a href='#'>Supprimer cette chanson </a></td></tr>";
}
//print_r($res);


echo "</table>";

echo "<br/>";
echo "<h3><a href='musicmanage.php'>Retourner à la page de gestion de musique</a></h3>";


?>


</body>
</html>