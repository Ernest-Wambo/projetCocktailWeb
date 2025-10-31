
    <header>
        <form method="get" action=# >
        <a href="?page=page_navigation"><button type="button">Navigation</button></a>
        <a href="?page=recettes_favoris"><button type="button">Recette ❤️</button></a>
        Recherche:
        <input type="text" name="recherche"/>
        <input type="submit" name="submit" value="loupe 🔍" />
        <?php if(isset($_SESSION["login"])){
            echo "".$_SESSION["login"];?>
            <a href="?page=profil"><button type="button">Profil</button></a>
            <a href="?deconnection=deconnection"><button type="button">se déconnecter</button></a>
        <?php }else{ ?>
        Login:
        <input type="text" name="Login"/>
        Mot de passe:
        <input type="text" name="MotDePasse"/>
        <input type="submit" name="submit" value="Connexion" />
        <a href="?page=formulaireConnexion"><button type="button">S'inscrire</button></a>
        <?php } ?>
        </form>
    </header>
    
