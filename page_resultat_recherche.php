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
// Capture : 
// 1) un groupe entre guillemets avec signe optionnel
// 2) un mot simple avec signe optionnel
preg_match_all('/([+-]?)"([^"]*)"|([+-]?)([^\s"]+)/', $requete, $resultatsRecherche);


// Mots entre guillemets signe = [1], mot = [2] (si pas de signe alors $signe ="" donc ça fonctionne quand même)
$guillemets = array_map(function($signe, $mot) {
	global $nonReconnu;
    // Si le mot entre guillemets commence par + ou -, syntaxe incorrecte
    if (isset($mot[0]) && ($mot[0] === '+' || $mot[0] === '-')) {
        $nonReconnu[] = $mot; 
        return null; // on ne le met pas dans les mots reconnus
    }
    return trim($signe . $mot);
}, $resultatsRecherche[1], $resultatsRecherche[2]);


// Mots sans guillemets signe = [3], mot = [4](si pas de signe alors $signe ="" donc ça fonctionne quand même)
$sansGuillemets = array_map(function($signe, $mot) {
    return trim($signe . $mot);
}, $resultatsRecherche[3], $resultatsRecherche[4]);

// Fusion des deux types de mots
$alimentsTrouves = array_filter(array_merge($guillemets, $sansGuillemets));

$alimentsNonSouhaitesReconnu= array();
$alimentsSouhaitesReconnu= array();


// On vérifie si chaque mot de la recherche est dans $Hierarchie
foreach ($alimentsTrouves as $mot) {
	if ($mot[1]!= '-' && $mot[1] != '+'){
		$motPur = trim($mot, '+-');  // retire le signe
		if (estConnu($motPur, $Hierarchie)) {
			if ($mot[0] === '-') {
				$alimentsNonSouhaitesReconnu[] = $motPur;
			} else {
				$alimentsSouhaitesReconnu[] = $motPur;
			}
		} else {
        $nonReconnu[] = $motPur;
		}
    }
	else
		$nonReconnu[] = $mot; 	// je laisse les signes pour montrer que le probleme vient de la syntaxe car 
								// si je passais par $motPur --citron deviendrait citron et c'est pas logique pour
								// l'utilisateur car il pourrait croire qu'il n'y a pas de cocktail avec du citron
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

// Vérification de la validité des cocktails et calcul de leur score
$totalCriteres = count($alimentsSouhaitesReconnu) + count($alimentsNonSouhaitesReconnu);
$resultats = [];

foreach ($Recettes as $cocktail) {
    $ingredients = isset($cocktail['index']) ? array_map('strtolower', $cocktail['index']) : [];
    $score = 0;

	// Vérifier d'abord les aliments non souhaités : si un est présent, on ignore le cocktail
    $contientNonSouhaite = false;
    foreach ($alimentsNonSouhaitesReconnu as $a) {
        $aSousElements = tousLesSousElements($a, $Hierarchie);
        if (!empty(array_intersect($aSousElements, $ingredients))) {
            $contientNonSouhaite = true;
            break; // un seul aliment non souhaité suffit pour exclure
        }
    }
    if ($contientNonSouhaite) {
        continue; // On passe au cocktail suivant
    }
	// On vérifie qu'il y'a au moins un élément souhaité du cocktail
   $contientSouhaite = false;
    foreach ($alimentsSouhaitesReconnu as $a) {
        $aSousElements = tousLesSousElements($a, $Hierarchie);
        if (!empty(array_intersect($aSousElements, $ingredients))) {
            $contientSouhaite = true;
            $score++; // On incrémente le score si critère rempli
        }
    }
    if (!$contientSouhaite) {
        continue; // On ignore le cocktail si aucun élément souhaité n'est présent
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
        $isFav = in_array($cocktail["titre"], $_SESSION['favoris'] ?? []);
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