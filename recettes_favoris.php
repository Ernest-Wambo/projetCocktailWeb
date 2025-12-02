<?php

include('Donnees.inc.php');
include('favoris_utilisateur.php');

echo "<h2>Mes recettes préférées</h2>\n";

//  affichage du detail d'une recette  
if (isset($_GET['recette']) && $_GET['recette'] !== '') {
    $recetteDemandee = $_GET['recette'];
    foreach ($Recettes as $recette) {
        if ($recette["titre"] === $recetteDemandee && in_array($recetteDemandee, $_SESSION['favoris'])) {
            echo "<h2>" . htmlspecialchars($recette["titre"]) . "</h2>";
            affichageImage(researchImage($recette["titre"]));

            echo "<p><a href='?page=recettes_favoris&recette=" . urlencode($recette["titre"])
                 . "&Favoris=" . urlencode($recette["titre"]) . "'>❤️</a></p>";

            echo "<h3>Ingrédients :</h3><ul>\n";
            foreach (explode('|', $recette["ingredients"]) as $ingredient) echo "<li>" . htmlspecialchars(trim($ingredient)) . "</li>\n";
            echo "</ul>\n";

            echo "<h3>Préparation :</h3><p\n>" . htmlspecialchars($recette["preparation"]) . "</p>\n<br>";
            echo "<p><a href='?page=recettes_favoris'> Retour à mes recettes préférées</a></p>\n<br>";
            return;
        }
    }
}

//  liste des favoris
if (empty($_SESSION['favoris'])) {
    echo "<p>Aucune recette préférée pour le moment ❤️</p>\n<br>";
    echo "<p><a href='?page=navigation'>← Retour à la navigation</a></p>\n";
    return;
}

echo '<div class="container">';
foreach ($Recettes as $recette) {
    if (in_array($recette["titre"], $_SESSION['favoris'])) {
        echo "\n";
        echo '<div class="cocktails">';
        echo "\n<strong><a href='?page=recettes_favoris&recette=" . urlencode($recette["titre"]) . "'>"
            . htmlspecialchars($recette["titre"]) . "</a></strong>";

        affichageImage(researchImage($recette["titre"]));

        echo "<p><a href='?page=recettes_favoris&Favoris=" . urlencode($recette["titre"]) . "'>❤️</a></p>\n";
        foreach ($recette["index"] as $ing) echo htmlspecialchars($ing) . "<br>\n";

        echo '</div>';
    }
}
echo '</div>';
echo "<p><a href='?page=page_navigation'>← Retour à la navigation</a></p>";
?>
