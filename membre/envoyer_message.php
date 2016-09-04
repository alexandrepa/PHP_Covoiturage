<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require'../BDD.php';
$bdd = getBdd();             //voir inscription.php
require '../formulaire.php';
require '../droits.php';

test_membre();
// on teste si le visiteur a soumis le formulaire
if (isset($_POST['Soumettre']) && $_POST['Soumettre'] == 'Envoyer') {
    // on teste l'existence de nos variables. On teste également si elles ne sont pas vides
    if ((isset($_POST['titre']) && !empty($_POST['titre'])) && (isset($_POST['message']) && !empty($_POST['message']))) {
        $contenu = action();
    } else {
        $contenu = "Certains champs ne sont pas remplis";
    }
} else {
    $contenu = formulaire();
}

function formulaire() {
    global $bdd;
    ob_start();
    ?>
    <h1>Ecrire un message</h1>
    <?php
    form_debut("form", "POST", "envoyer_message.php");      //on crée le formulaire pour que l'utilisateur puisse saisir le message

    
    
    //on regarde si la page a été appelé depuis un autre formulaire POST(mes trajets) qui contient le login du destinataire si oui on ne laisse pas le choix à l'utilisateur
    if (isset($_POST["destinataire"])) {
        form_hidden("destinataire_username", $_POST["destinataire"]);
        echo"<p>Destinataire : " . $_POST["destinataire"] . "</p>";
    } else {
        //sinon on fait une liste déroulante contenant tous les usernames des membres comme destinataire
        form_label("Destinataire"); 
        form_select_sql_attribut_user("Destinataire :", "destinataire_username", 1, $bdd, "username", "user");
    }
    echo"<br><br>";
    form_label("Titre");
    form_input_text("titre", TRUE, "", "", 45, "");
    echo"<br><br>";
    form_label("Message");
    form_textarea("message", 20, 90, "", TRUE, "");
    echo "<br><br>";
    form_submit("Soumettre", "Envoyer", FALSE);
    form_reset("Reset", "Reinitialiser", FALSE, FALSE);
    form_fin();
    ?>
    <?php
    return ob_get_clean();
}

function action() {

    global $bdd;
    $reponse = $bdd->query("SELECT id FROM user WHERE username = '" . $_POST["destinataire_username"] . "'");
    $donnee = $reponse->fetchAll(); //on recupere l'id du destinataire
    $destinataire_id = $donnee[0]["id"];
    $sql = 'INSERT INTO messagerie (titre,date,message,expediteur_user_id,destinataire_user_id) VALUES(:titre,:date,:message,:expediteur_id,:destinataire_id)';
    $statement = $bdd->prepare($sql); 
    $statement->execute(array(
        ":titre" => $_POST["titre"],
        ":date" => date('Y-m-d H:i:s'),//la fonction date permet d'envoyer la date au moment où l'utilisateur envoi le message
        ":message" => $_POST["message"],         //on insere le message dans la table messagerie
        ":expediteur_id" => $_SESSION["id"],
        ":destinataire_id" => $destinataire_id
    ));

    return "<div class='alert alert-success'>Le message a bien été envoyé.</div>";
}

$title = "Ecrire message";
gabarit();

