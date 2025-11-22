
    <?php
        $send = TRUE;
        $login = NULL; 
        $loginExist = NULL;
        $motDePasse = NULL;
        $sexe=NULL;
        $nom=NULL;
        $prenom=NULL;
        $naissance=NULL;
        //tableau permetant le suivie de la validité des donnée
        $information = array(
                'loginExist' =>$loginExist,
                'login'=>$login,
                'motDePasse'=>$motDePasse,
                'sexe'=>$sexe,
                'nom'=>$nom,
                'prenom'=>$prenom,
                'naissance'=>$naissance,
            );

        //verification de quand envoyer le formulaire
        if(isset($_POST["submit"])){

            //verification du login
            if(isset($_POST["login"])){
                if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['login'])) {
                    if(file_exists($_POST["login"].".inc".".php"))
                        $information["loginExist"] = TRUE;
                    $login = $_POST['login'];
                }else if(!empty($_POST["login"])){
                    $login=$_POST['login'];
                    $information["login"] = TRUE; 
                }
            }else{
                $information["login"]=TRUE;
            }

            //verification du mot de passe
            if(isset($_POST["motDePasse"])){
                if (!empty(trim($_POST["motDePasse"]))) {
                    $motDePasse = password_hash($_POST['motDePasse'],PASSWORD_DEFAULT);
                }else{
                $information["motDePasse"]=TRUE;
                }
            }else{
                $information["motDePasse"]=TRUE;
            }

            //verification du sexe
            if(isset($_POST["sexe"])){
                if($_POST["sexe"]=="h"||$_POST["sexe"]=="f"){
                    $sexe=$_POST['sexe'];
                }else if(!empty($_POST["sexe"])){
                    $sexe=$_POST['sexe'];
                    $information["sexe"] = TRUE;
                }
            }

            //verification du nom
            if(isset($_POST["nom"])){
                if(preg_match("/^[a-zA-ZàâäéèêëîïôöùûüÿçÀÂÄÉÈÊËÎÏÔÖÙÛÜŸÇ]+((['-_]{1}|[ ]+){1}[a-zA-ZàâäéèêëîïôöùûüÿçÀÂÄÉÈÊËÎÏÔÖÙÛÜŸÇ]+)*$/u",$_POST["nom"])){
                    $nom=$_POST["nom"];
                }else if(!empty($_POST["nom"])){
                    $nom=$_POST['nom'];
                    $information["nom"] = TRUE;
                }
            }

            //verification du prenom
            if(isset($_POST["prenom"])){
                if(preg_match("/^[a-zA-ZàâäéèêëîïôöùûüÿçÀÂÄÉÈÊËÎÏÔÖÙÛÜŸÇ]+((['-_]{1}|[ ]+){1}[a-zA-ZàâäéèêëîïôöùûüÿçÀÂÄÉÈÊËÎÏÔÖÙÛÜŸÇ]+)*$/u",$_POST["prenom"])){
                    $prenom=$_POST["prenom"];
                }else if(!empty($_POST["prenom"])){
                    $prenom=$_POST['prenom'];
                    $information["prenom"] = TRUE;
                }
            }

            //verification de la date de naissance
            if(isset($_POST["naissance"])&&!empty($_POST["naissance"])){
                $dateToday = date("d/m/Y");
                $Date=explode("-",$_POST["naissance"]);
                if(checkdate($Date[1],$Date[2],$Date[0])){
                    //verification utilisateur a plus de 18ans
                    $dateNaissance = new DateTime($_POST["naissance"]);
                    $aujourdhui = new DateTime();
                    $dateNaissance->add(new DateInterval('P18Y'));
                    if($dateNaissance <= $aujourdhui){
                        $naissance = $_POST["naissance"];
                    }else if(!empty($_POST["naissance"])){
                        $naissance=$_POST['naissance'];
                        $information["naissance"] = TRUE;
                    }
                }else if(!empty($_POST["naissance"])){
                    $naissance=$_POST['naissance'];
                    $information["naissance"] = TRUE;
                }
            }
            //creation du conte si toutes les donner necessaire sont fournie et que toute les donnée fournie sont correct
            if($login!=NULL&&$motDePasse!=NULL&&$information["prenom"]!==TRUE&&$information["nom"]!==TRUE&&$information["sexe"]!==TRUE&&$information["naissance"]!==TRUE&&$information["login"]!==TRUE&&$information["motDePasse"]!==TRUE&&$information["loginExist"]!==TRUE){
                $send=false;
                $_SESSION["login"] = $login;
                $_SESSION["motDePasse"] = $motDePasse;
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

 <!-- Formulaire permettant la creation d'un conte -->
<form method="post" action=# >
<fieldset>
    <legend>Informations personnelles</legend>
    <?php if($information["sexe"]==TRUE)
        echo '<span style="color:red;">Le sexe doit obligatoirement etre homme ou femme</span><br>';
    ?>  
	Vous êtes :  
	<input type="radio" <?php if($sexe=="f"){echo"checked=checked";}?> name="sexe" value="f"/> une femme 	
	<input type="radio" <?php if($sexe=="h"){echo"checked=checked";}?> name="sexe" value="h"/> un homme
	<br />
    <?php if($information["login"]==TRUE)
        echo '<span style="color:red;">Le login est obligatoire et doit être composé de lettres non accentuées, minuscules ou MAJUSCULES, et/ou de chiffres</span><br>';
    ?>  
    <?php if($information["loginExist"]==TRUE)
        echo '<span style="color:red;">Le login exist déja essayer en un autre</span><br>';
    ?>  
    login (obligatoire) :  
	<input type="text" value ="<?php echo $login ?>" name="login" required="required" style="<?php if($information['login'] === TRUE) echo 'border: 2px solid red; background-color: #ffe5e5;'; if($information['loginExist'] === TRUE) echo 'border: 2px solid red; background-color: #ffe5e5;'?>" /><br /> 
    <?php if($information["motDePasse"]==TRUE)
        echo'<span style="color:red;">Le mot de passe est obligatoire</span></br>'
    ?>   
    mot de passe (obligatoire) :    
	<input type="text" name="motDePasse" required="required" style="<?php if($information['motDePasse'] === TRUE) echo 'border: 2px solid red; background-color: #ffe5e5;' ?>"/><br /> 
    <?php if($information["nom"]==TRUE)
        echo'<span style="color:red;"> le nom est composés de lettres minuscules et/ou de lettres MAJUSCULES, ainsi que les caractères « - », « » (espace) et « ’ ». Les lettres peuvent être accentuées. Tiret et apostrophe sont forcément encadré par deux lettres, par contre plusieurs espaces sont possibles entre deux parties de nom.</span></br>'
    ?>  
    Nom (facultatif) :     
	<input type="text" value ="<?php echo $nom ?>" name="nom" style="<?php if($information['nom'] === TRUE) echo 'border: 2px solid red; background-color: #ffe5e5;' ?>"/><br />   
    <?php if($information["prenom"]==TRUE)
        echo'<span style="color:red;">le prenom est composés de lettres minuscules et/ou de lettres MAJUSCULES, ainsi que les caractères « - », « » (espace) et « ’ ». Les lettres peuvent être accentuées. Tiret et apostrophe sont forcément encadré par deux lettres, par contre plusieurs espaces sont possibles entre deux parties de prenom.</span></br>'
    ?>  
    Prénom (facultatif) : 
	<input type="text" value ="<?php echo $prenom ?>" name="prenom" style="<?php if($information['prenom'] === TRUE) echo 'border: 2px solid red; background-color: #ffe5e5;' ?>" /><br />
     <?php if($information["naissance"]==TRUE)
        echo'<span style="color:red;">la date de naissance doit être antérieure de 18 ans à la date du jour et doit être dans le format jj/mm/aaaa</span></br>'
    ?>   	
    Date de naissance (facultatif) : 
	<input type="date" value ="<?php echo $naissance ?>" name="naissance" style="<?php if($information['naissance'] === TRUE) echo 'border: 2px solid red; background-color: #ffe5e5;' ?>"/><br /> 	
</fieldset>


	<br />
<input type="submit" name="submit" value="Valider" />
         
</form>
<?php
    }
?>
