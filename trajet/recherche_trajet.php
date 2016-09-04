<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require'../BDD.php';
$bdd = getBdd();
//voir inscription.php
require '../droits.php';
require '../formulaire.php';

test_membre();
// on teste si le visiteur a soumis le formulaire
if (isset($_POST['Soumettre']) && $_POST['Soumettre'] == 'Soumettre') {
    // on teste l'existence de nos variables. On teste également si elles ne sont pas vides
    if (isset($_POST['ville_depart']) && isset($_POST['ville_arrivee']) && isset($_POST['date'])) {
        $contenu = action();
    } else {
        $contenu = "Certains champs ne sont pas remplis";
    }
} else {
    $contenu = formulaire();
}

function formulaire() {

    global $bdd;
    ?>

    <?php
    $reponse = $bdd->query("SELECT * FROM trajet WHERE effectue = 0 and pilote_user_id != " . $_SESSION["id"]);
    $donnee = $reponse->fetch(); //on verifie qu'il y a bien des trajets dans la base afin d'eviter des erreurs
    if ($donnee[0] != 0) {
        ob_start();
        echo"<h1>Recherche de votre trajet</h1>";
        form_debut("form", "POST", "recherche_trajet.php");
        form_label("Ville de départ");
        echo"<br>";

        //on construit des listes déroulantes avec les villes contenues dans la base et les dates
        form_select_sql_attribut_ville("Ville de départ", "ville_depart", 1, $bdd, "lieu_depart", "trajet");
        echo"<br><br>";
        form_label("Ville d'arrivée");
        echo"<br>";
        form_select_sql_attribut_ville("Ville d'arrivée", "ville_arrivee", 1, $bdd, "lieu_arrivee", "trajet");
        echo"<br><br>";
        form_label("Date");
        echo"<br>";
        form_select_sql_attribut_ville("Date", "date", 1, $bdd, "date", "trajet");
        echo"<br><br>";

        form_submit("Soumettre", "Soumettre", FALSE);
        form_reset("Reset", "Reinitialiser", FALSE, FALSE);
        form_fin();
        return ob_get_clean();
    } else {
        return "Il n'y a aucun trajet pour le moment.";
    }
    ?>
    <?php
}

function action() {

    global $bdd;
    //on va chercher dans la base des données les trajets correspondant au valeurs choisies par l'utilisateur
    $reponse = $bdd->prepare("SELECT * FROM trajet WHERE lieu_depart = ? AND lieu_arrivee = ? AND date = ? AND effectue = ? AND pilote_user_id != ?");
    $reponse->execute(array($_POST['ville_depart'],
        $_POST['ville_arrivee'],
        $_POST['date'],
        FALSE,
        $_SESSION["id"]
    ));
    
    ob_start();
    ?>
    <?php
    form_debut("form", "POST", "reserver_trajet.php"); //on crée un formulaire pour recuperer le choix du trajet
    ?>

    <!--On crée un tableau contenant les infos des trajets trouvé   -->
    <table class='table table-hover'>
        <tr>
            <th>Ville de départ</th>
            <th>Ville d'arrivée</th>
            <th>Date</th>
            <th>Heure de départ</th>
            <th>Prix</th>
            <th>Places restantes</th>
            <th>Coche</th>
        </tr>
    <?php
    while ($donnees = $reponse->fetch()) {
        echo"<tr>";
        echo"<th>" . $donnees["lieu_depart"] . "</th>";
        echo"<th>" . $donnees["lieu_arrivee"] . "</th>";
        echo"<th>" . $donnees["date"] . "</th>";
        echo"<th>" . $donnees["heure_dep"] . "</th>";
        echo"<th>" . $donnees["prix"] . "</th>";
        echo"<th>" . ($donnees["places_max"] - $donnees["places_prises"]) . "/" . $donnees["places_max"] . "</th>";
        
        //on crée un bouton radio qui renvoi l'id du trajet pour chaque trajet si il n'est pas complet
        if ($donnees["places_max"] != $donnees["places_prises"]) {
            echo"<th><input type='radio' name='choix_trajet' value='" . $donnees['id'] . "' /></th>";
        } else {
            echo'<th>COMPLET</th>';
        }
        echo"</tr>";
    }
    ?>

    </table>
    </br></br>
    <?php
    form_submit("Reserver", "Reserver", FALSE);
    form_fin();
    ?>


    <?php
    return ob_get_clean();
}

$title = "Recherche trajet";
gabarit();

