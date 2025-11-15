<?php 
    session_start();
    include('fonctions.php');
    include('Donnees.inc.php');
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

    // remet à jours le cookie
if (isset($_SESSION["login"])) {
    setcookie("login", $_SESSION["login"], time() + 3600*24*30, "/");
}
// permet de limiter les pages accessible, si jamais une pages est fausse on redirige sur la page de navigation
$pageAuthoriser = ["page_navigation", "recettes_favoris", "formulaireConnexion", "profil","page_resultat_recherche"];
$page = isset($_GET["page"]) ? $_GET["page"] : "page_navigation";
if (!in_array($page, $pageAuthoriser)) $page = "page_navigation";
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Cocktail</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include $page . '.php';?>
</body>
</html>