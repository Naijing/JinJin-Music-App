<?php

if (isset($_POST["inscription"]) && $_POST["inscription"] == "Inscription") {
    $user = $_POST["pseudo1"];
    $psw = $_POST["password1"];
    $psw_confirm = $_POST["confirm"];
    if ($user == "" || $psw == "" || $psw_confirm == "") {
        echo "<script>alert('non complet?'); history.go(-1);</script>";
    } else {
        if ($psw == $psw_confirm) {
            require("connect.php");
            $dns = "mysql:dbname=" . BASE . ";host=" . SERVER;
            try {
                $connexion = new PDO($dns, USER, PASSWD);

            } catch (PDOException $e) {
                printf('Echec de la connexion : %s\n', $e->getMessage());
                exit();
            }


            $sql = "SELECT name from user where name = ? ";
            $stmt = $connexion->prepare($sql);
            $stmt->execute(array($user));


            if ($row = $stmt->rowCount()) {
                echo "<script>alert('nom existant'); history.go(-1);</script>";
            } else {

                $sql_insert = "insert into user (name,password) values(?,?)";
                $stmt1 = $connexion->prepare($sql_insert);
                $stmt1->execute(array($user, $psw));
                $res_insert = $stmt1->fetch(PDO::FETCH_NUM);

                //$num_insert = mysql_num_rows($res_insert);
                if ($res_insert = $stmt1->rowCount()) {
                    echo "<script>alert('Compte créé avec succès!'); history.go(-1);</script>";
                } else {
                    echo "<script>alert('Patientez?'); history.go(-1);</script>";
                }
            }


        } else {
            echo "<script>alert('Les mots de passe saisis different?'); history.go(-1);</script>";
        }
    }
}
/* else
 {
     echo "<script>alert('reussi!'); history.go(-1);</script>";
 }*/
?>