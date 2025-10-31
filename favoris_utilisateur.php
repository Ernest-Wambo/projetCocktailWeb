<?php
include('Donnees.inc.php');

// --- reload les favoris pour utilisateur connecté
if (isset($_SESSION['login'])) {
    $login = $_SESSION['login'];
    $fichierFavoris = "favoris_{$login}.php";

    // reload fichier favoris s'il existe (recuperation depuis le fichier)
    if (file_exists($fichierFavoris)) {
        include $fichierFavoris; 
        $_SESSION['favoris'] = $favoris ?? [];
    } else {
        $_SESSION['favoris'] = $_SESSION['favoris'] ?? [];
    }
} else {
    // recupèration depuis la session pour utilisateur non connecté
    $_SESSION['favoris'] = $_SESSION['favoris'] ?? [];
}

// toggle favoris si jamais un clique est fait
if (isset($_GET['Favoris'])) {
    $titre = $_GET['Favoris'];

    if (!isset($_SESSION['favoris'])) $_SESSION['favoris'] = [];

    if (in_array($titre, $_SESSION['favoris'])) {
        $_SESSION['favoris'] = array_diff($_SESSION['favoris'], [$titre]);
    } else {
        $_SESSION['favoris'][] = $titre;
    }

    // Sauvegarde dans fichier si utilisateur est connecté
    if (isset($_SESSION['login'])) {
        $favoris = $_SESSION['favoris'];
        file_put_contents($fichierFavoris, "<?php \$favoris = " . var_export($favoris, true) . "; ?>");
    }

    // Restaurer les favoris depuis fichier si utilisateur connecté
if (isset($_SESSION['login'])) {
    $filename = $_SESSION['login'] . "_favoris.inc.php";
    if (file_exists($filename)) {
        include $filename;
        $_SESSION['favoris'] = $favoris ?? [];
    }
}
}
?>
