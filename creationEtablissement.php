<head>
<script type="text/javascript">
function focus(champ, erreur)
{
   if(erreur)
      champ.style.backgroundColor = "#fba";
   else
      champ.style.backgroundColor = "";
}
function verifid(champ)
{
   if(champ.value.length == 0)
   {
      focus(champ, true);
	  alert("Champ id vide");
	  return false;
   }
   else
   {
      focus(champ, false);
      return true;
   }
}
function verifnom(champ)
{
   var nom = parseInt(champ.value);
   if(champ.value.length < 2 && champ.value.length > 0 || champ.value.length > 25)
   {
      focus(champ, true);
	  alert("Champ nom incorrect (entre 2 et 25 caractères max)");
      return false;
   }
   else if(!isNaN(nom))
   {
	  focus(champ, true);
	  alert("Champ nom incorrect (ne peut pas contenir de chiffres)");
	  return false;
   }
   else if(champ.value.length == 0)
   {
      focus(champ, true);
	  alert("Champ nom vide");
	  return false;
   }
   else
   {
      focus(champ, false);
      return true;
   }
}
function verifad(champ)
{
   if(champ.value.length < 2 && champ.value.length > 0 || champ.value.length > 25)
   {
      focus(champ, true);
	  alert("Champ adresse incorrect (numéro rue + adresse)");
      return false;
   }
   else if(champ.value.length == 0)
   {
      focus(champ, true);
	  alert("Champ adresse vide");
	  return false;
   }
   else
   {
      focus(champ, false);
      return true;
   }
}
function verifcp(champ)
{
   var cp = parseInt(champ.value);
   if (cp.toString().length != 5 || cp.toString().length == 0)
   {
	  focus(champ,true);
	  alert("Le code postal doit comporter 5 chiffres");
	  return false;
   }
   else
   {
      focus(champ, false);
      return true;
   }
}
function verifville(champ)
{
   var ville = parseInt(champ.value);
   if (ville.toString().length < 2 && ville.toString().length > 0 || ville.toString().length > 25)
   {
	  focus(champ,true);
	  alert("Champ ville incorrect (entre 2 et 25 caractères max)");
	  return false;
   }
   else if(!isNaN(ville))
   {
	  focus(champ, true);
	  alert("Champ ville incorrect (ne peut pas contenir de chiffres)");
	  return false;
   }
   else if(champ.value.length == 0)
   {
      focus(champ, true);
	  alert("Champ ville vide");
	  return false;
   }
   else
   {
      focus(champ, false);
      return true;
   }
}
function veriftel(champ)
{
   var tel = parseInt(champ.value);
   if (tel.toString().length != 10 || tel.toString().length == 0)
   {
	  focus(champ,true);
	  alert("Le numéro de téléphone doit comporter 10 chiffres");
	  return false;
   }
   else
   {
      focus(champ, false);
      return true;
   }
}
function verifmail(champ)
{
   var regex = /^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
   if(!regex.test(champ.value))
   {
      focus(champ, true);
	  alert("L'adresse mail n'est pas valide");
      return false;
   }
   else
   {
      focus(champ, false);
      return true;
   }
}
</script>
</head>
<?php

include("_debut.inc.php");
include("_gestionBase.inc.php"); 
include("_controlesEtGestionErreurs.inc.php");

// CONNEXION AU SERVEUR MYSQL PUIS SÉLECTION DE LA BASE DE DONNÉES festival

$connexion=connect();
if (!$connexion)
{
   ajouterErreur("Echec de la connexion au serveur MySql");
   afficherErreurs();
   exit();
}
if (!selectBase($connexion))
{
   ajouterErreur("La base de données festival est inexistante ou non accessible");
   afficherErreurs();
   exit();
}

// CRÉER UN ÉTABLISSEMENT 

// Déclaration du tableau des civilités
$tabCivilite=array("M.","Mme","Melle");  

$action=$_REQUEST['action'];

// S'il s'agit d'une création et qu'on ne "vient" pas de ce formulaire (on 
// "vient" de ce formulaire uniquement s'il y avait une erreur), il faut définir 
// les champs à vide sinon on affichera les valeurs précédemment saisies
if ($action=='demanderCreEtab') 
{  
   $id='';
   $nom='';
   $adresseRue='';
   $ville='';
   $codePostal='';
   $tel='';
   $adresseElectronique='';
   $type=0;
   $civiliteResponsable='Monsieur';
   $nomResponsable='';
   $prenomResponsable='';
   $nombreChambresOffertes='';
   $informationsPratiques='';
}
else
{
   $id=$_REQUEST['id']; 
   $nom=$_REQUEST['nom']; 
   $adresseRue=$_REQUEST['adresseRue'];
   $codePostal=$_REQUEST['codePostal'];
   $ville=$_REQUEST['ville'];
   $tel=$_REQUEST['tel'];
   $adresseElectronique=$_REQUEST['adresseElectronique'];
   $type=$_REQUEST['type'];
   $civiliteResponsable=$_REQUEST['civiliteResponsable'];
   $nomResponsable=$_REQUEST['nomResponsable'];
   $prenomResponsable=$_REQUEST['prenomResponsable'];
   $nombreChambresOffertes=$_REQUEST['nombreChambresOffertes'];
   $informationsPratiques=$_REQUEST['informationsPratiques'];

   verifierDonneesEtabC($connexion, $id, $nom, $adresseRue, $codePostal, $ville, 
                        $tel,$adresseElectronique, $nomResponsable, $prenomResponsable, $nombreChambresOffertes);      
   if (nbErreurs()==0)
   {        
      creerEtablissement($connexion, $id, $nom, $adresseRue, $codePostal, $ville,  
                         $tel, $adresseElectronique, $type, $civiliteResponsable, 
                         $nomResponsable, $prenomResponsable, $nombreChambresOffertes, $informationsPratiques);
   }
}

echo "
<form method='POST' action='creationEtablissement.php?'>
   <input type='hidden' value='validerCreEtab' name='action'>
   <table width='85%' align='center' cellspacing='0' cellpadding='0' 
   class='tabNonQuadrille'>
   
      <tr class='enTeteTabNonQuad'>
         <td colspan='3'>Nouvel établissement</td>
      </tr>
      <tr class='ligneTabNonQuad'>
         <td> Id*: </td>
         <td><input type='text' onblur='verifid(this)' value='$id' name='id' size ='10' 
         maxlength='8'></td>
      </tr>";
     
      echo '
      <tr class="ligneTabNonQuad">
         <td> Nom*: </td>
         <td><input type="text" onblur="verifnom(this)" value="'.$nom.'" name="nom" size="50" 
         maxlength="45"></td>
      </tr>
      <tr class="ligneTabNonQuad">
         <td> Adresse*: </td>
         <td><input type="text" onblur="verifad(this)" value="'.$adresseRue.'" name="adresseRue" 
         size="50" maxlength="45"></td>
      </tr>
      <tr class="ligneTabNonQuad">
         <td> Code postal*: </td>
         <td><input type="text" onblur="verifcp(this)" value="'.$codePostal.'" name="codePostal" 
         size="4" maxlength="5"></td>
      </tr>
      <tr class="ligneTabNonQuad">
         <td> Ville*: </td>
         <td><input type="text" onblur="verifville(this)" value="'.$ville.'" name="ville" size="40" 
         maxlength="35"></td>
      </tr>
      <tr class="ligneTabNonQuad">
         <td> Téléphone*: </td>
         <td><input type="text" onblur="veriftel(this)" value="'.$tel.'" name="tel" size ="20" 
         maxlength="10"></td>
      </tr>
      <tr class="ligneTabNonQuad">
         <td> E-mail*: </td>
         <td><input type="text" onblur="verifmail(this)" value="'.$adresseElectronique.'" name=
         "adresseElectronique" size ="75" maxlength="70"></td>
      </tr>
      <tr class="ligneTabNonQuad">
         <td> Type*: </td>
         <td>';
            if ($type==1)
            {
               echo " 
               <input type='radio' name='type' value='1' checked>  
               Etablissement Scolaire
               <input type='radio' name='type' value='0'>  Autre";
             }
             else
             {
                echo " 
                <input type='radio' name='type' value='1'> 
                Etablissement Scolaire
                <input type='radio' name='type' value='0' checked> Autre";
              }
           echo "
           </td>
         </tr>
         <tr class='ligneTabNonQuad'>
            <td colspan='2' ><strong>Responsable:</strong></td>
         </tr>
         <tr class='ligneTabNonQuad'>
            <td> Civilité*: </td>
            <td> <select name='civiliteResponsable'>";
               for ($i=0; $i<3; $i=$i+1)
                  if ($tabCivilite[$i]==$civiliteResponsable) 
                  {
                     echo "<option selected>$tabCivilite[$i]</option>";
                  }
                  else
                  {
                     echo "<option>$tabCivilite[$i]</option>";
                  }
               echo '
               </select>&nbsp; &nbsp; &nbsp; &nbsp; Nom*: 
               <input type="text" value="'.$nomResponsable.'" name=
               "nomResponsable" size="26" maxlength="25">
               &nbsp; &nbsp; &nbsp; &nbsp; Prénom*: 
               <input type="text"  value="'.$prenomResponsable.'" name=
               "prenomResponsable" size="26" maxlength="25">
            </td>
         </tr>
          <tr class="ligneTabNonQuad">
            <td> Nombre chambres offertes*: </td>
            <td><input type="text" value="'.$nombreChambresOffertes.'" name=
            "nombreChambresOffertes" size ="2" maxlength="3"></td>
         </tr>
		 <tr class ="informationsPratiques">
			<td> Informations pratiques : </td>
			<td><textarea type="text" value="'.$informationsPratiques.'" name=
			"informationsPratiques"  maxlength="255" cols="50" rows="5"></textarea></td>
   </table>';
   
   echo "
   <table align='center' cellspacing='15' cellpadding='0'>
      <tr>
         <td align='right'><input type='submit' value='Valider' name='valider'>
         </td>
         <td align='left'><input type='reset' value='Annuler' name='annuler'>
         </td>
      </tr>
      <tr>
         <td colspan='2' align='center'><a href='listeEtablissements.php'>Retour</a>
         </td>
      </tr>
   </table>
</form>";

// En cas de validation du formulaire : affichage des erreurs ou du message de 
// confirmation
if ($action=='validerCreEtab')
{
   if (nbErreurs()!=0)
   {
      afficherErreurs();
   }
   else
   {
      echo "
      <h5><center>La création de l'établissement a été effectuée</center></h5>";
   }
}

?>
