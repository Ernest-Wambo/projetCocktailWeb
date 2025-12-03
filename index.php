<?php 
    //demarage d'une session
    session_start();
    include('fonctions.php');
    include('Donnees.inc.php');

    //supprime le cookie si la session a expiré mais que le cookie est encore la
    if (!isset($_SESSION["login"]) && isset($_COOKIE["login"])) {
        setcookie("login", "", time() - 3600, "/");
        unset($_COOKIE["login"]);
    }

    //gere la déconnection d'un utilisateur en lui laissant temporairement ses favorie quand il est deconnecter 
    if((isset($_GET["deconnection"]))&&($_GET["deconnection"]=="deconnection")){
        $favoris = [];
        if(isset($_SESSION["favoris"]))
            $favoris = $_SESSION["favoris"];
        session_unset();
        session_destroy();
        setcookie("login", "", time() - 3600, "/"); 
        session_start();
        $_SESSION["favoris"]= $favoris;
    }

    //permet a l'utilisateur de se conecter 
    $connectionFausse = FALSE;
    if(isset($_POST["Login"])&&isset($_POST["MotDePasse"])){

        if(file_exists("UserData/".$_POST["Login"].".inc".".php")){
            $fileName = "UserData/".$_POST["Login"].".inc".".php";
            include $fileName;
            if(($_POST["Login"]==$session["login"])&&password_verify($_POST["MotDePasse"],$session["motDePasse"])){
                $fileNameFavorie = "UserFavorite/"."favoris_".$_POST["Login"].".php";
                include $fileNameFavorie;
                $_SESSION=$session;
                $_SESSION["favoris"] = $favoris;
                if(isset($_SESSION["login"]))
                    setcookie("login",$_SESSION["login"], time() + 3600, "/");
            }else{
                $connectionFausse=TRUE;
            }
        }else{
            $connectionFausse=TRUE;
        }
    }

// permet de limiter les pages accessible, si jamais une pages est fausse on redirige sur la page de navigation
$pageAuthoriser = ["page_navigation", "recettes_favoris", "formulaireInscription", "profil","page_resultat_recherche"];
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