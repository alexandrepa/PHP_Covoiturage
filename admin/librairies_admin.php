<?php

require'../BDD.php';
require'../droits.php';
$bdd = getBdd();
require'../formulaire.php';
test_admin();

function print_request($bd, $request, $att_req = TRUE) {   //fonction permettant d'afficher la requete sql dans un tableau dynamique
    $reponse = $bd->query($request); //on stocke le resultat de la requete
    if ($reponse === FALSE) { //si la requete n'a pas aboutit
        echo"impossible d'executer la requete";
        return FALSE;
    } else {
        $tab_res = $reponse->fetchALL(PDO::FETCH_ASSOC); //sinon on parcours chaque element de la requete, en plaçant les clés dans un <th> et les valeurs dans les lignes suivantes
        $i = 1;
        if ($att_req) {
            //echo $request; //enlever le commentaire pour afficher la requete
            echo "<table>";
            foreach ($tab_res as $unres) {
                if ($i == 1) {
                    echo"<tr><th>" . implode('</th><th>', array_keys($unres)) . "</th></tr>";
                    $i++;
                }
                echo"<tr><td>" . implode('</td><td>', $unres) . "</td></tr>";
            }
            echo"</table>";
            return TRUE;
        }
    }
}
ob_start();
echo "<h1>Comptes Utilisateurs</h1>";
print_request($bdd, "SELECT id, nom, prenom, username, email, birthday, compte, note FROM user;"); //on appelle simplement la fonction pour obtenir toutes les informations sur les comptes
echo "<h1>Trajets restant a effectuer</h1>";
$reponse = $bdd->query("SELECT id, effectue, lieu_depart, lieu_arrivee, date, heure_dep FROM trajet"); //on stocke les informations des trajets
$reponse_nb_trajet = $bdd->query("SELECT count(id) FROM trajet");
echo"<table><tr><th>ID</th><th>Ville de depart</th><th>Ville d'arrivee</th><th>Date</th><th>Heure</th><th>Pilote<br>Nom  Prenom</th><th>Passagers<br>Nom  Prenom</th></tr>"; //on crée le tableau
$nb_trajet = $reponse_nb_trajet->fetch();
$index_id = 1;
while ($tab_res = $reponse->fetch()) {
    $reponse_passagers = $bdd->query("SELECT nom, prenom, username FROM trajet_passager TP, user U, trajet T "
            . "WHERE TP.user_id = U.id "
            . "AND TP.trajet_id = T.id "
            . "AND TP.trajet_id = "
            . $index_id . " "
            . "AND effectue = 0 "
            . "GROUP BY TP.user_id;");
    $reponse_pilote = $bdd->query("SELECT nom, prenom, username FROM trajet T, user U "
            . "WHERE T.pilote_user_id = U.id "
            . "AND T.id = "
            . $index_id . " "
            . "AND effectue = 0 "
            . "GROUP BY T.id");
    if ($tab_res["effectue"] == 0) { //si le trajet n'est pas effectué (effetue = 0) on l'affiche
        echo"<tr><td>" . $tab_res["id"] . "</td><td>" . $tab_res["lieu_depart"] . "</td><td>" . $tab_res["lieu_arrivee"] . "</td><td>" . $tab_res["date"] . "</td><td>" . $tab_res["heure_dep"] . "</td>";
        echo"<td><table>";
        while ($pilote = $reponse_pilote->fetch()) {
            echo"<td><a href='../membre/profil.php?username=".$pilote["username"]."'>" . $pilote["nom"] . "</a></td><td>" . $pilote["prenom"] . "</td>";
        }
        echo"</td></table>";
        echo"<td><table>";
        while ($tab_passagers = $reponse_passagers->fetch()) {
            echo"<tr><td><a href='../membre/profil.php?username=".$tab_passagers["username"]."'>" . $tab_passagers["nom"] . "</a></td><td>" . $tab_passagers["prenom"] . "</td></tr>";
        }
        echo"</table></td>";
    }else{
        
    }
    $index_id = $index_id + 1;
}
$contenu=ob_get_clean();   
   
   
$title = "Administration";
require '../gabarit/gabarit_admin.php';

?>