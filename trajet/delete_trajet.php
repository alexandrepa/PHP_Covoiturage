<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require'../BDD.php';
$bdd = getBdd();

require '../droits.php';   //voir inscription.php
require '../formulaire.php';
session_start();
if (empty($_POST)) {         //on s'assure que l'utilisateur n'a pas acceder à la page sans passer par (mes trajets)
    
	header ('Location: ../index.php');
	exit();
}
$trajet_id=$_POST["suppr"];//on recupere l'id du trajet à supprimer

ob_start();


//on va recuper les differentes infos de chaque passager sur le trajet afin de leur informer qu'il doit payer 10€ pour chaque passager et on va l'ajouter dans les transactions
$reponse = $bdd->query("SELECT user_id, prenom, nom, nb_places FROM trajet_passager, user WHERE trajet_id = " .$trajet_id." AND id=user_id");
while($donnee = $reponse->fetch()){
   echo "Vous devez payer ".$donnee["nb_places"]." X 10 : <b>".($donnee["nb_places"]*10)."</b> € à ".$donnee["prenom"]." ".$donnee["nom"]." pour dédommagement<br><br>";
   $sql = 'INSERT INTO transaction (credit_user_id,debit_user_id,somme) VALUES(:credit_user_id,:debit_user_id,:somme)';
    $statement = $bdd->prepare($sql);
    $statement->execute(array(
        ":credit_user_id" => $donnee["user_id"],
        ":debit_user_id" => $_SESSION["id"],
        ":somme" => ($donnee["nb_places"]*10)
    ));
    
    //on envoi aussi un message aux passagers pour les informer
    $sql = 'INSERT INTO messagerie (expediteur_user_id,destinataire_user_id,titre, message,date) VALUES(:expediteur_user_id,:destinataire_user_id,:titre,:message,:date)';
    $statement = $bdd->prepare($sql);
    $statement->execute(array(
        ":destinataire_user_id" => $donnee["user_id"],
        ":expediteur_user_id" => $_SESSION["id"],
        ":message" => "Le conducteur ".$_SESSION["prenom"]." ".$_SESSION["nom"]." a annulé son trajet et vous doit donc ".$donnee["nb_places"]." X 10 : <b>".($donnee["nb_places"]*10)."</b> €",
        ":titre" => "Annulation trajet",
        ":date" => date('Y-m-d H:i:s')
        ));
    $bdd->exec("UPDATE user SET compte = compte + ".($donnee['nb_places']*10)." WHERE id = " . $donnee["user_id"]);
    $bdd->exec("UPDATE user SET compte = compte - ".($donnee['nb_places']*10)." WHERE id = " . $_SESSION["id"]);
    //on met a jour le compte du pilote et du passager 
    
}
//on supprime le trajet de la base
    $bdd->exec("DELETE FROM trajet WHERE id = ".$trajet_id);
    echo "<div class='alert alert-success'>Votre trajet a bien été supprimé, un email a été envoyé a chacun des utilisateurs pour les informer.</div>";
    echo "<a href='../membre/mon_compte.php'>Voir mon compte</a>";

 $contenu=ob_get_clean();   
   
   
$title = "Supprimer trajet";
gabarit();

