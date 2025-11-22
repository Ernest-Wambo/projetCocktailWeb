<?php

include('Donnees.inc.php');
include('favoris_utilisateur.php'); 

$requete = trim(isset($_GET['recherche']) ? $_GET['recherche'] : '');

// Vérification du nombre de guillemets
if (substr_count($requete, '"') % 2 != 0) {
    echo "<p><b>Problème de syntaxe :</b> nombre impair de double-quotes.</p>";
    exit;
}

// Extraction avec regex
preg_match_all('/([+-]?)"([^"]+)"|([+-]?)([^\s"]+)/', $requete, $resultatsRecherche);

// Mots entre guillemets signe = [1], mot = [2] (si pas de signe alors $signe ="" donc ça fonctionne quand même)
$guillemets = array_map(function($signe, $mot) {
    return trim($signe . $mot);
}, $resultatsRecherche[1], $resultatsRecherche[2]);

// Mots sans guillemets signe = [3], mot = [4](si pas de signe alors $signe ="" donc ça fonctionne quand même)
$sansGuillemets = array_map(function($signe, $mot) {
    return trim($signe . $mot);
}, $resultatsRecherche[3], $resultatsRecherche[4]);

// Fusion des deux types de mots
$alimentsTrouves = array_filter(array_merge($guillemets, $sansGuillemets));


$alimentsSouhaites = [];
$alimentsNonSouhaites = [];

// On sépare les mots en deux catégories, souhaites ou non 
foreach ($alimentsTrouves as $a) {
    if ($a === '') continue;
    if ($a[0] === '-') {
        $alimentsNonSouhaites[] = trim(substr($a, 1));
    } else {
        $alimentsSouhaites[] = ltrim($a, '+');
    }
}



// Vérification des aliments reconnus
$alimentsSouhaitesReconnu = [];
$alimentsNonSouhaitesReconnu = [];
$nonReconnu = [];

foreach ($alimentsSouhaites as $a) {
    if (estConnu($a, $Hierarchie)) $alimentsSouhaitesReconnu[] = $a;
    else $nonReconnu[] = $a;
}

foreach ($alimentsNonSouhaites as $a) {
    if (estConnu($a, $Hierarchie)) $alimentsNonSouhaitesReconnu[] = $a;
    else $nonReconnu[] = $a;
}

// Si aucun aliment reconnu on signale un probleme a l'utilisateur
if (empty($alimentsSouhaitesReconnu) && empty($alimentsNonSouhaitesReconnu)) {
    echo "<p>Problème dans votre requête: recherche impossible.</p>";
    exit;
} 
// Sinon on liste les aliments reconnus et non reconnu
else {
    if (!empty($alimentsSouhaitesReconnu))
        echo "<p>Liste des aliments souhaités : " . implode(', ', $alimentsSouhaitesReconnu) . "</p>";
    if (!empty($alimentsNonSouhaitesReconnu))
        echo "<p>Liste des aliments non souhaités : " . implode(', ', $alimentsNonSouhaitesReconnu) . "</p>";
    if (!empty($nonReconnu))
        echo "<p>Éléments non reconnus dans la requête : " . implode(', ', $nonReconnu) . "</p>";
}

// Calcul du score des cocktails
$totalCriteres = count($alimentsSouhaitesReconnu) + count($alimentsNonSouhaitesReconnu);
$resultats = [];

foreach ($Recettes as $cocktail) {
    $ingredients = isset($cocktail['index']) ? array_map('strtolower', $cocktail['index']) : [];
    $score = 0;

    // Aliments souhaités (1 point max par critère)
    foreach ($alimentsSouhaitesReconnu as $a) {
        $aSousElements = tousLesSousElements($a, $Hierarchie);
        if (!empty(array_intersect($aSousElements, $ingredients))) {
            $score++;
        }
    }

    // Aliments non souhaités (1 point max par critère)
    foreach ($alimentsNonSouhaitesReconnu as $a) {
        $aSousElements = tousLesSousElements($a, $Hierarchie);
        if (empty(array_intersect($aSousElements, $ingredients))) {
            $score++;
        }
    }

    if ($totalCriteres > 0) {
        $pourcentage = round(($score / $totalCriteres) * 100);
        if ($pourcentage > 0) {
            $cocktail['score'] = $pourcentage;
            $resultats[] = $cocktail;
        }
    }
}

// Tri décroissant des resultats en fonction du score
if ($totalCriteres >= 2) {
    usort($resultats, function($a, $b) {
        if ($a['score'] == $b['score']) {
			return 0;
		}
		return ($a['score'] < $b['score']) ? 1 : -1;
    });
}

// Affichage des cocktails
if (!empty($resultats)) {
	//On compte le nombre de recettes qui remplissent tout les critères
	$totalPlein = count(array_filter($resultats, function($r) { return $r['score'] == 100; }));
    echo "<hr>";
    echo "<p><b>$totalPlein</b> recette(s) satisfont entièrement la recherche.</p>";

		if ($totalCriteres >= 2) {
			//On compte le nombre de recettes qui remplissent au moins un critère
			$totalPartiel = count(array_filter($resultats, function($r) { return $r['score'] < 100; }));
			echo "<p><b>$totalPartiel</b> recette(s) satisfont partiellement la recherche.</p>";
			}

	echo "<ul>";
	echo '<div class="container">';

    foreach ($resultats as $cocktail) {

        echo '<div class="cocktails">';

        // Titre avec lien vers la fiche détaillée
        echo "<strong><a href='?page=navigation"
			. "&recherche=".  urldecode($requete)
            . "&recette=" . urlencode($cocktail["titre"]) . "'>"
            . htmlspecialchars($cocktail["titre"])
            . "</a></strong>";

        // Image du cocktails
        affichageImage(researchImage($cocktail["titre"]));

        // Bouton favoris
        $isFav = in_array($cocktail["titre"], isset($_SESSION['favoris']) ? $_SESSION['favoris'] : []);
        $coeur = $isFav ? "❤️" : "🤍";

        echo "<p><a href='?page=page_resultat_recherche"
			. "&recherche=".  urldecode($requete)
            . "&Favoris=" . urlencode($cocktail["titre"]) . "'>$coeur</a></p>";

        // Score visible
        echo "<p><b>Score : " . $cocktail['score'] . "%</b></p>";

        // Ingrédients listés
        echo "<p><u>Ingrédients :</u><br>";
        foreach ($cocktail["index"] as $ingre)
            echo htmlspecialchars($ingre) . "<br>";
        echo "</p>";

        echo '</div>'; //Ferme cocktails
    }

    echo '</div>'; // Ferme container
    
}

else {
    echo "<p>Aucune recette ne correspond à votre recherche.</p>";
}

?>