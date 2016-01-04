<?php
$name = $_POST['pseudo'];
$password = $_POST['password'];


require("connect.php");
$dns = "mysql:dbname=" . BASE . ";host=" . SERVER;
try {
    $connexion = new PDO($dns, USER, PASSWD);

} catch (PDOException $e) {
    printf('Echec de la connexion : %s\n', $e->getMessage());
    exit();
}
/*$sql="SELECT * from user";
if(!$connexion->query($sql))
    echo"Pas d'accès au users";
else{
    $lines=Array();
    foreach($connexion->query($sql) as $row)
    {$lines[]=$row;}
    require'listusers.php';
}*/
$sql = "SELECT * from user where name = ? ";
$stmt = $connexion->prepare($sql);
$stmt->execute(array($name));


while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if ($row['password'] == $password) {


        session_start();
        $_SESSION['valid_userid'] = $row['id'];
        $_SESSION['valid_username'] = $name;

        header("location: musicmanage.php");
        exit();
    }
}


header("location:connexion.html");
exit();


?>