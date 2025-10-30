<?php 
    session_start();
    if((isset($_GET["deconnection"]))&&($_GET["deconnection"]=="deconnection")){
        session_unset();
        session_destroy();
    }
    if(isset($_SESSION["login"]))
        setcookie("login",$_SESSION["login"]);
    if(isset($_COOKIE["login"])){
        $fileName = $_COOKIE["login"].".inc".".php";
        include $fileName;
        if(isset($_GET["Login"])&&isset($_GET["MotDePasse"])){
            if(($_GET["Login"]==$session["login"])&&password_verify($_GET["MotDePasse"],$session["motDePasse"]))
                $_SESSION=$session;
        }
    }
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php 
    if(isset($_GET["page"]))
        include "".$_GET["page"].".php";
    ?>
</body>
</html>