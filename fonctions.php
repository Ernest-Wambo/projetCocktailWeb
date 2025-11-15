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

    echo "<p><strong>Aliment courant </strong><br><br>";
    foreach ($chemin as $i => $nom) {
        if ($i > 0) echo " / ";
        echo '<a href="?page=navigation&aliment=' . urlencode($nom) . '">' . htmlspecialchars($nom) . '</a>';
    }
    echo "</p>";
}

// affichage des sous catergories d'une categories d'aliment
function afficherSousCategories($aliment) {
    global $Hierarchie;
    if (!isset($Hierarchie[$aliment])) return;
    $sous = isset($Hierarchie[$aliment]["sous-categorie"]) ? $Hierarchie[$aliment]["sous-categorie"] : array();
    if (empty($sous)) return;

    echo "<p><strong>Sous-catégories :</strong></p><ul>";
    foreach ($sous as $s) {
        echo "<li><a href='?page=navigation&aliment=" . urlencode($s) . "'>" . htmlspecialchars($s) . "</a></li>";
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
?>
