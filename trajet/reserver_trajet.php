<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$choix_trajet = $_POST["choix_trajet"];// on recupere l'id du trajet choisit à reserver
require'../BDD.php';
$bdd = getBdd();
                        //voir inscription.php
require '../droits.php';
require '../formulaire.php';

test_membre();

if (!isset($_POST['choix_trajet'])) {//on verifie que l'utilisateur vient bien de la page recherche_trajet.php
    header('Location: ../index.php');
    exit();
}
// on teste si le visiteur a soumis le formulaire
if (isset($_POST['Reservation']) && $_POST['Reservation'] == 'Reservation') {
    // on teste l'existence de nos variables. On teste également si elles ne sont pas vides
    if ((isset($_POST['nb_places']) && !empty($_POST['nb_places']))) {
        $contenu = action();
    } else {
        $contenu = "Certains champs ne sont pas remplis";
    }
} else {
    $contenu = formulaire();//on affiche le formulaire car l'utilisateur vient d'arriver sur la page
}

function formulaire() {

    global $bdd;
    //on recupère les informations sur le trajet
    global $choix_trajet;
    $reponse = $bdd->query("SELECT * FROM trajet as T, user as U, pilote as P WHERE T.id = " . $choix_trajet." AND U.id= T.pilote_user_id AND P.pilote_user_id = T.pilote_user_id");
    $donnee = $reponse->fetch();
    ob_start();
    ?>
    <h1>Reserver votre trajet</h1>
    
    
    <?php
    
    //on affiche les informations du trajet
        echo"<div class='panel panel-success'>";
  echo"<div class='panel-heading'>";
  
    echo"<b>Ville de départ : </b> ".$donnee["lieu_depart"]." - <b>Ville d'arrivée : </b>".$donnee["lieu_arrivee"];

echo"</div>";
echo "<div class='panel-body'>";

echo" Pilote : ".$donnee["prenom"]." ".$donnee["nom"];
    
  echo"  <a href='../membre/profil.php?username=".$donnee["username"]."' class='btn btn-primary pull-right'>Son profil</a></br>";
  echo "Voiture : ".$donnee["voiture_marque"]." ".$donnee["voiture_modele"];

    echo"</div>";
echo "</div>";
        
        
        
        
        
    form_debut("form", "POST", "reserver_trajet.php");
    form_label("Nombre de places");
    //on limite le nombre de places à celle disponible en soustrayant le nombre de places max par le nombre de places prises
    $tab_places = range(1, $donnee["places_max"] - $donnee["places_prises"]);
    form_select("nb_places", FALSE,1, $tab_places);
    form_hidden("choix_trajet", $choix_trajet);//on garde en memoire l'id du trajet par un champ hidden
    form_submit("Reservation", "Reservation", FALSE);
    form_fin();
    ?>
    <?php
    return ob_get_clean();
}

function action() {

    global $bdd;
    global $choix_trajet;
    //on insere le passager sur le trajet par le biais de la table trajet_passager
    $sql = 'INSERT INTO trajet_passager (trajet_id,user_id,nb_places) VALUES(:trajet_id,:user_id,:nb_places)';
    $statement = $bdd->prepare($sql);
    $statement->execute(array(
        ":trajet_id" => $choix_trajet,
        ":user_id" => $_SESSION["id"],
        ":nb_places" => $_POST["nb_places"]
    ));
    
    //on modifie le nombre de place prises sur le trajet en ajoutant celles reservées
    $bdd->exec("UPDATE trajet SET places_prises = places_prises + " . $_POST['nb_places'] . " WHERE id = " . $choix_trajet);

    return "<div class='alert alert-success'>Votre trajet a bien été reservé, il est maintenant disponible dans votre espace 'mes trajets'.</div>";
}

$title = "Reserver trajet";
gabarit();

