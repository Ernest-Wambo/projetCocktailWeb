<!-- Header principal de la page -->
<header>
    <div id=left-top>
    <!-- bouton pour la navigation et les recette en favorie -->
    <a href="?page=page_navigation"><button type="button">Navigation</button></a>
    <a href="?page=recettes_favoris"><button id= "fav" type="button">Recette ❤️</button></a>
    </div>

    <div id=buttom-middle>
    <!-- Formulaire permettant la recherche d'un cocktail -->
    <form method="get" action="index.php">
		<input type="hidden" name="page" value="page_resultat_recherche">
        Recherche:
        <input type="text" name="recherche" value="<?php echo htmlspecialchars(isset($_GET['recherche']) ? $_GET['recherche'] : ''); ?>">
        <input type="submit" name="submit" value="loupe 🔍" />
    </form>
    </div>

    <div id=right-top>
    <!-- affichage du login -->
    <?php if(isset($_SESSION["login"])){
        echo "".$_SESSION["login"];?>
        <a href="?page=profil"><button type="button">Profil</button></a>
        <a href="?deconnection=deconnection"><button id= "deco" type="button">se déconnecter</button></a>
    <?php }else{ ?>
            
    <!-- Formulaire permettant la connection d'un utilisateur -->
    <form method="post" action="index.php">
        <?php if(isset($connectionFausse)&&$connectionFausse==TRUE) echo '<span style="color:red;">login ou mot de passe incorrect</span>';?>
        Login:
        <input type="text" name="Login" style="<?php if(isset($connectionFausse)&&$connectionFausse==TRUE) echo 'border: 2px solid red; background-color: #ffe5e5;';?>"/>
        Mot de passe:
        <input type="text" name="MotDePasse" style="<?php if(isset($connectionFausse)&&$connectionFausse==TRUE) echo 'border: 2px solid red; background-color: #ffe5e5;';?>"/>
        <input type="submit" name="submit" value="Connexion" />
        <a href="?page=formulaireInscription"><button type="button">S'inscrire</button></a>
        <?php } ?>
    </form>
    </div>
</header>
    
