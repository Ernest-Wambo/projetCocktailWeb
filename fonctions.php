<?php
// permet l'affichage de l'image d'un cocktail
function affichageImage($nomImage) {
    echo '<img src="Photos/' . htmlspecialchars($nomImage) . '" 
              width="70" height="90"
              onerror="this.onerror=null; this.src=\'Photos/default.jpg\';"
              alt="Image de ' . htmlspecialchars($nomImage) . '">';
}

// recherche crée le nom de l'image a partir de sont titre
function researchImage($nom) {
    $nomImage = str_replace(" ", "_", $nom);
    $nomImage .= ".jpg";
    return $nomImage;
}

// fabrique le fils d'ariane a afficher a chaque niveau de profondeur des cocktail
function afficherFilAriane($aliment) {
    global $Hierarchie;
    $chemin = [$aliment];
    $courant = $aliment;
    while ($courant != "Aliment") {
        $found = false;
        foreach ($Hierarchie as $parent => $infos) {
            if (in_array($courant, isset($infos["sous-categorie"]) ? $infos["sous-categorie"] : array())) {
                array_unshift($chemin, $parent);
                $courant = $parent;
                $found = true;
                break;
            }
        }
        if (!$found) break;
    }

    echo "<p><strong>Aliment courant </strong><br><br>\n";
    foreach ($chemin as $i => $nom) {
        if ($i > 0) echo " / ";
        echo '<a href="?page=navigation&aliment=' . urlencode($nom) . '">' . htmlspecialchars($nom) . '</a>'."\n";
    }
    echo "</p>\n";
}

// affichage des sous catergories d'une categories d'aliment
function afficherSousCategories($aliment) {
    global $Hierarchie;
    if (!isset($Hierarchie[$aliment])) return;
    $sous = isset($Hierarchie[$aliment]["sous-categorie"]) ? $Hierarchie[$aliment]["sous-categorie"] : array();
    if (empty($sous)) return;

    echo "<p><strong>Sous-catégories :</strong></p><ul>\n";
    foreach ($sous as $s) {
        echo "<li><a href='?page=navigation&aliment=" . urlencode($s) . "'>" . htmlspecialchars($s) . "</a></li>\n";
    }
    echo "</ul>";
}

// parcours recursivement le tableau pour recupère toutes les descendants d'un aliment
function getDescendants($aliment) {
    global $Hierarchie;
    $descendants = [$aliment];
    if (isset($Hierarchie[$aliment]["sous-categorie"])) {
        foreach ($Hierarchie[$aliment]["sous-categorie"] as $sous) {
            $descendants = array_merge($descendants, getDescendants($sous));
        }
    }
    return $descendants;
}

// Vérifie si un aliment/catégorie est connu/contenu dans Donnees.inc.php
function estConnu($aliment, $hierarchie) {
    $aliment = strtolower($aliment);

    foreach ($hierarchie as $nom => $infos) {
        if (strtolower($nom) === $aliment) return true;

        if (isset($infos['sous-categorie'])) {
            foreach ($infos['sous-categorie'] as $sousCat) {
                if (strtolower($sousCat) === $aliment) return true;
            }
        }

        if (isset($infos['super-categorie'])) {
            foreach ($infos['super-categorie'] as $superCat) {
                if (strtolower($superCat) === $aliment) return true;
            }
        }
    }

    return false;
}

// Récupère tous les sous-éléments d'une catégorie (récursif)
function tousLesSousElements($categorie, $hierarchie) {
    $categorie = strtolower($categorie);
    $resultat = [];

    foreach ($hierarchie as $nom => $infos) {
        if (strtolower($nom) === $categorie) {
            if (isset($infos['sous-categorie'])) {
                foreach ($infos['sous-categorie'] as $sousCat) {
                    $resultat[] = strtolower($sousCat);
                    $resultat = array_merge($resultat, tousLesSousElements($sousCat, $hierarchie));
                }
            }
            $resultat[] = strtolower($nom);
            break;
        }
    }

    return array_unique($resultat);
}
?>
