
    <?php
    if(isset($_SESSION["login"])){
        $send = TRUE;
        $sexe=$_SESSION["sexe"];
        $nom=$_SESSION["nom"];
        $prenom=$_SESSION["prenom"];
        $naissance=$_SESSION["naissance"];

        //tableau permettant le suivie de la validité des donnée 
        $information = array(
                'sexe'=>FALSE,
                'nom'=>FALSE,
                'prenom'=>FALSE,
                'naissance'=>FALSE,
            );

        //verification de quand envoyer le formulaire
        if(isset($_POST["submit"])){

            //verification validité du sexe
            if(isset($_POST["sexe"])){
                if($_POST["sexe"]=="h"||$_POST["sexe"]=="f"){
                    $sexe=$_POST['sexe'];
                }else if(!empty($_POST["sexe"])){
                    $sexe=$_POST['sexe'];
                    $information["sexe"] = TRUE; 
                }
            }else{
                $sexe = NULL;
            }

            //verification validité nom 
            if(isset($_POST["nom"])){
                if(preg_match("/^[a-zA-ZàâäéèêëîïôöùûüÿçÀÂÄÉÈÊËÎÏÔÖÙÛÜŸÇ]+((['-_]{1}|[ ]+){1}[a-zA-ZàâäéèêëîïôöùûüÿçÀÂÄÉÈÊËÎÏÔÖÙÛÜŸÇ]+)*$/u",$_POST["nom"])){
                    $nom=$_POST["nom"];
                }else if(!empty($_POST["nom"])){
                    $nom=$_POST['nom'];
                    $information["nom"] = TRUE; 
                }else if(empty($_POST["nom"]))
                    $nom=NULL;
            }else{
                $nom = NULL;
            }

            //verification validité prenom
            if(isset($_POST["prenom"])){
                if(preg_match("/^[a-zA-ZàâäéèêëîïôöùûüÿçÀÂÄÉÈÊËÎÏÔÖÙÛÜŸÇ]+((['-_]{1}|[ ]+){1}[a-zA-ZàâäéèêëîïôöùûüÿçÀÂÄÉÈÊËÎÏÔÖÙÛÜŸÇ]+)*$/u",$_POST["prenom"])){
                    $prenom=$_POST["prenom"];
                }else if(!empty($_POST["prenom"])){
                    $prenom=$_POST['prenom'];
                    $information["prenom"] = TRUE; 
                }else if(empty($_POST["prenom"]))
                    $prenom=NULL;
            }else{
                $prenom = NULL;
            }

            //verification validité naissance
            if(isset($_POST["naissance"])&&!empty($_POST["naissance"])){
                $dateToday = date("d/m/Y");
                $Date=explode("-",$_POST["naissance"]);
                if(checkdate($Date[1],$Date[2],$Date[0])){
                    //verifie que l'utilisateur a plus de 18 ans
                    $dateNaissance = new DateTime($_POST["naissance"]);
                    $aujourdhui = new DateTime();
                    $dateNaissance->add(new DateInterval('P18Y'));
                    if($dateNaissance <= $aujourdhui){
                        $naissance = $_POST["naissance"];
                    }else if(!empty($_POST["naissance"])){
                        $nom=$_POST['naissance'];
                        $information["naissance"] = TRUE; 
                    }
                }else if(!empty($_POST["naissance"])){
                    $nom=$_POST['naissance'];
                    $information["naissance"] = TRUE; 
                }
            }else {
                $naissance= NULL;
            }
            //verification qu'au moin une donnée est changer 
            if($sexe==$_SESSION["sexe"]&&$nom==$_SESSION["nom"]&&$prenom==$_SESSION["prenom"]&&$naissance==$_SESSION["naissance"]){
                $identique=TRUE;
            }
            //modification des donnée si elles sont correcte
            if($information["prenom"]!==TRUE&&$information["nom"]!==TRUE&&$information["sexe"]!==TRUE&&$information["naissance"]!==TRUE&&$identique!==TRUE){
                $send=false;
                $_SESSION["prenom"] = $prenom;
                $_SESSION["nom"] = $nom;
                $_SESSION["sexe"] = $sexe;
                $_SESSION["naissance"] = $naissance;
                $session=$_SESSION;
                unset($session["favoris"]);
                $nameFile = $_SESSION["login"].".inc".".php";
                $fichier = fopen($nameFile,'w');
                file_put_contents($nameFile,"<?php \$session = ".var_export($session,true).";?>");
                fclose($fichier);
                header("Location: index.php");
            }
        }if($send==TRUE){

    ?>
    

<h1>Vos données</h1>

 <!-- Formulaire permettant la modification du profil -->
<form method="post" action=# >
<fieldset>
    <?php if($identique==TRUE)
        echo '<span style="color:red;">aucune information modifier</span><br>';
    ?>  
    <legend>Informations personnelles</legend>
    <?php if($information["sexe"]==TRUE)
        echo '<span style="color:red;">Le sexe doit obligatoirement etre homme ou femme</span><br>';
    ?>  
	Vous êtes :  
	<input type="radio" <?php if($sexe=="f"){echo"checked=checked";}?> name="sexe" value="f"/> une femme 	
	<input type="radio" <?php if($sexe=="h"){echo"checked=checked";}?> name="sexe" value="h"/> un homme
	<br />
    <?php if($information["nom"]==TRUE)
        echo'<span style="color:red;"> le nom est composés de lettres minuscules et/ou de lettres MAJUSCULES, ainsi que les caractères « - », « » (espace) et « ’ ». Les lettres peuvent être accentuées. Tiret et apostrophe sont forcément encadré par deux lettres, par contre plusieurs espaces sont possibles entre deux parties de nom.</span></br>'
    ?> 
    Nom :    
	<input type="text" value ="<?php echo $nom ?>" name="nom" style="<?php if($information['nom'] === TRUE) echo 'border: 2px solid red; background-color: #ffe5e5;' ?>"/><br /> 
    <?php if($information["prenom"]==TRUE)
        echo'<span style="color:red;">le prenom est composés de lettres minuscules et/ou de lettres MAJUSCULES, ainsi que les caractères « - », « » (espace) et « ’ ». Les lettres peuvent être accentuées. Tiret et apostrophe sont forcément encadré par deux lettres, par contre plusieurs espaces sont possibles entre deux parties de prenom.</span></br>'
    ?>    
    Prénom : 
	<input type="text" value ="<?php echo $prenom ?>" name="prenom" style="<?php if($information['prenom'] === TRUE) echo 'border: 2px solid red; background-color: #ffe5e5;' ?>"/><br /> 	
    Date de naissance :
    <?php if($information["naissance"]==TRUE)
        echo'<span style="color:red;">la date de naissance doit être antérieure de 18 ans à la date du jour et doit être dans le format jj/mm/aaaa</span></br>'
    ?>   
	<input type="date" value ="<?php echo $naissance ?>" name="naissance" style="<?php if($information['naissance'] === TRUE) echo 'border: 2px solid red; background-color: #ffe5e5;' ?>"/><br /> 	
</fieldset>


	<br />
<input type="submit" name="submit" value="Valider" />
         
</form>
<?php
    }
}
?>
