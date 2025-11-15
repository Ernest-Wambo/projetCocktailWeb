<?php

include('Donnees.inc.php');
include('favoris_utilisateur.php'); 

// aliment courabt
$alimentCourant = isset($_GET['aliment']) ? $_GET['aliment'] : "Aliment";

// recette a afficher
$recetteDemandee = isset($_GET['recette']) ? $_GET['recette'] : null;

?>

<div class="layout">
    <!-- left nav bar -->
    <div class="sidebar">
        <?php
        afficherFilAriane($alimentCourant);
        afficherSousCategories($alimentCourant);
        ?>
    </div>

    <!-- right tiles display with the recepie -->
    <div class="display">
        <?php
        echo "<div class=\"liste\">";

        // --- affichagr detaille des recettes ---
        if ($recetteDemandee) {
           
            foreach ($Recettes as $recette) {
                if ($recette["titre"] === $recetteDemandee) {
                    echo "<h2>" . htmlspecialchars($recette["titre"]) . "</h2>";
                    affichageImage(researchImage($recette["titre"]));

                    // modification des favori
                    $isFav = in_array($recette["titre"], isset($_SESSION['favoris']) ? $_SESSION['favoris'] : array());
                    $coeur = $isFav ? "❤️ Retirer des favoris" : "🤍 Ajouter aux favoris";
                    echo "<p><a href='?page=navigation&aliment=" . urlencode($alimentCourant)
                        . "&recette=" . urlencode($recette["titre"])
                        . "&Favoris=" . urlencode($recette["titre"]) . "'>$coeur</a></p>";

                    // Ingrédients
                    echo "<h3>Ingrédients :</h3><ul>";
                    foreach (explode('|', $recette["ingredients"]) as $ingredient)
                        echo "<li>" . htmlspecialchars(trim($ingredient)) . "</li>";
                    echo "</ul>";

                    // Préparation
                    echo "<h3>Préparation :</h3><p>" . htmlspecialchars($recette["preparation"]) . "</p>";

                    echo "<p><a href='?page=navigation&aliment=" . urlencode($alimentCourant) . "'>← Retour</a></p>";
                    exit;
                }
            }
        }

        // --- affichages synthatistique ! ---
        $descendants = getDescendants($alimentCourant);
        $nbRecettes = 0;
        foreach ($Recettes as $recette) {
            $contient = false;
            foreach ($recette["index"] as $ingredient) {
                foreach ($descendants as $cat) {
                    if (strcasecmp($ingredient, $cat) == 0) {
                        $contient = true;
                        break 2;
                    }
                }
            }
            if ($contient || $alimentCourant == "Aliment") $nbRecettes++;
        }

        echo "<p><strong>Liste des cocktails contenant : "
            . htmlspecialchars($alimentCourant)
            . " ($nbRecettes recette" . ($nbRecettes > 1 ? "s" : "") . ")</strong></p>";

        echo '<div class="container">';
        foreach ($Recettes as $recette) {
            $contient = false;
            foreach ($recette["index"] as $ingredient) {
                foreach ($descendants as $cat) {
                    if (strcasecmp($ingredient, $cat) == 0) {
                        $contient = true;
                        break 2;
                    }
                }
            }

            if ($contient || $alimentCourant == "Aliment") {
                echo '<div class="cocktails">';
                echo "<strong><a href='?page=navigation&aliment=" . urlencode($alimentCourant)
                     . "&recette=" . urlencode($recette["titre"]) . "'>"
                     . htmlspecialchars($recette["titre"]) . "</a></strong>";

                affichageImage(researchImage($recette["titre"]));

                // favori
                $isFav = in_array($recette["titre"], isset($_SESSION['favoris']) ? $_SESSION['favoris'] : array());
                $coeur = $isFav ? "❤️" : "🤍";
                echo "<p><a href='?page=navigation&aliment=" . urlencode($alimentCourant)
                    . "&Favoris=" . urlencode($recette["titre"]) . "'>$coeur</a></p>";

                // liste ingredient
                foreach ($recette["index"] as $ing)
                    echo htmlspecialchars($ing) . "<br>";

                echo '</div>';
            }
        }
        echo '</div>';
        ?>
    </div>
</div>
