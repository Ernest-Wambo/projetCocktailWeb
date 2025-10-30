<!DOCTYPE html>
<html>

<head>
      <title>Vos données</title>
	  <meta charset="utf-8" />
</head>

<body>
    <?php
        $send = TRUE;
        $login = NULL; 
        $motDePasse = NULL;
        $sexe=NULL;
        $nom=NULL;
        $prenom=NULL;
        $naissance=NULL;
        $information = array(
                'login'=>FALSE,
                'motDePasse'=>FALSE,
                'sexe'=>FALSE,
                'nom'=>FALSE,
                'prenom'=>FALSE,
                'naissance'=>FALSE,
            );
        if(isset($_POST["submit"])){
            if(isset($_POST["login"])){
                if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['login'])) {
                    $login = $_POST['login'];
                }
            }
            if(isset($_POST["motDePasse"])){
                if (!empty($_POST["motDePasse"])) {
                    $motDePasse = password_hash($_POST['motDePasse'],PASSWORD_DEFAULT);
                }
            }
            if(isset($_POST["sexe"])){
                if($_POST["sexe"]=="h"||$_POST["sexe"]=="f"){
                    $sexe=$_POST['sexe'];
                }else if(!empty($_POST["sexe"])){
                    $sexe = FALSE;
                }
            }
            if(isset($_POST["nom"])){
                if(preg_match("/^[a-zA-ZàâäéèêëîïôöùûüÿçÀÂÄÉÈÊËÎÏÔÖÙÛÜŸÇ]+(?:['-][a-zA-ZàâäéèêëîïôöùûüÿçÀÂÄÉÈÊËÎÏÔÖÙÛÜŸÇ]+|\s+)*$/u",$_POST["nom"])){
                    $nom=$_POST["nom"];
                }else if(!empty($_POST["nom"])){
                    $nom = FALSE;
                }
            }
            if(isset($_POST["prenom"])){
                if(preg_match("/^[a-zA-ZàâäéèêëîïôöùûüÿçÀÂÄÉÈÊËÎÏÔÖÙÛÜŸÇ]+(?:['-][a-zA-ZàâäéèêëîïôöùûüÿçÀÂÄÉÈÊËÎÏÔÖÙÛÜŸÇ]+|\s+)*$/u",$_POST["prenom"])){
                    $prenom=$_POST["prenom"];
                }else if(!empty($_POST["prenom"])){
                    $prenom = FALSE;
                }
            }
            if(isset($_POST["naissance"])&&!empty($_POST["naissance"])){
                $dateToday = date("d/m/Y");
                $Date=explode("-",$_POST["naissance"]);
                if(checkdate($Date[1],$Date[2],$Date[0])){
                    $dateNaissance = new DateTime($_POST["naissance"]);
                    $aujourdhui = new DateTime();
                    $dateNaissance->add(new DateInterval('P18Y'));
                    if($dateNaissance <= $aujourdhui){
                        $naissance = $_POST["naissance"];
                    }else if(!empty($_POST["naissance"])){
                        $naissance = FALSE;
                    }
                }else if(!empty($_POST["naissance"])){
                    $naissance = FALSE;
                }
            }
            $information = array(
                'login'=>$login,
                'motDePasse'=>$motDePasse,
                'sexe'=>$sexe,
                'nom'=>$nom,
                'prenom'=>$prenom,
                'naissance'=>$naissance,
            );
            if($information["login"]!=NULL&&$information["motDePasse"]!=NULL&&$information["prenom"]!==FALSE&&$information["nom"]!==FALSE&&$information["sexe"]!==FALSE&&$information["naissance"]!==FALSE){
                $send=false;
                $_SESSION["login"] = $login;
                $_SESSION["motDePasse"] = $motDePasse;
                $_SESSION["prenom"] = $prenom;
                $_SESSION["nom"] = $nom;
                $_SESSION["sexe"] = $sexe;
                $_SESSION["naissance"] = $naissance;
                $nameFile = $_SESSION["login"].".inc".".php";
                $fichier = fopen($nameFile,'w');
                file_put_contents($nameFile,"<?php \$session = ".var_export($_SESSION,true).";?>");
                header("Location: page.php");
            }
        }if($send==TRUE){

    ?>
    

<h1>Vos données</h1>

<form method="post" action=# >
<fieldset>
    <legend>Informations personnelles</legend>
	Vous êtes :  
	<input type="radio" <?php if($information["sexe"]=="f"){echo"checked=checked";}?> name="sexe" value="f"/> une femme 	
	<input type="radio" <?php if($information["sexe"]=="h"){echo"checked=checked";}?> name="sexe" value="h"/> un homme
	<br />
    login :    
	<input type="text" value ="<?php echo $information["login"] ?>" name="login" required="required" /><br />  
    mot de passe :    
	<input type="text" name="motDePasse" required="required" /><br />  
    Nom :    
	<input type="text" value ="<?php echo $information["nom"] ?>" name="nom" /><br />   
    Prénom : 
	<input type="text" value ="<?php echo $information["prenom"] ?>" name="prenom" /><br /> 	
    Date de naissance : 
	<input type="date" value ="<?php echo $information["naissance"] ?>" name="naissance" /><br /> 	
</fieldset>


	<br />
<input type="submit" name="submit" value="Valider" />
         
</form>
<?php
    }
?>
</body>
</html>
