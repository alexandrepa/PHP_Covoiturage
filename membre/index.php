<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$title="Accueil";          //ceci est la page index du membre, lorsque un membre connecté va sur la page index du site il est directement redirigé ici.

require '../BDD.php';
$bdd=  getBdd();
session_start(); //on demarre la session
if (isset($_SESSION['login'])) { //on verifie que l'utilisateur est bien connecté
ob_start();?>
<h1>Bienvenue <?php echo$_SESSION['prenom']." ".$_SESSION['nom'];?></h1>
<?php 
$sql = "SELECT count(*) FROM messagerie WHERE destinataire_user_id=".$_SESSION['id']; //on recuêre le nombre de message recu par l'utilisateur
        $reponse = $bdd->query($sql);
        $donnee = $reponse->fetch();
$sql2 = "SELECT count(*) FROM trajet as T, trajet_passager as TP WHERE TP.trajet_id=T.id AND T.effectue=0 AND (T.pilote_user_id=".$_SESSION["id"]." OR TP.user_id=".$_SESSION["id"].")"; 
        $reponse2 = $bdd->query($sql2);  //on recupere le nombre de trajets en cours auquel l'utilisateur est lié
        $donnee2 = $reponse2->fetch();
?>
<!-- On affiche les deux infos precedentes -->
<h2><span class="fa-stack " style="vertical-align: middle; margin-right:15px;"><i class="fa fa-envelope-square fa-stack-2x" ></i></span>  Vous avez <?php echo $donnee[0];?> messages.</h2>
<h2><span class="fa-stack " style="vertical-align: middle; margin-right:15px;"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-car fa-stack-1x fa-inverse"></i></span>  Vous êtes actuellement sur <?php echo $donnee2[0];?> trajets.</h2>  
    
<h2 style="margin-bottom:25px;"><span class="fa-stack " style="vertical-align: middle; margin-right:15px; "><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-star fa-stack-1x fa-inverse"></i></span>  Vos dernières evaluations reçues</h2>
<table class="table table-hover">
    <tr><th>Evaluateur</th><th>Ville de départ</th><th>Ville d'arrivée</th><th>Date</th><th>Evaluation</th></tr>
 <!-- on va afficher les dernières evaluations de l'utilisateur-->   
<?php 
$sql3 = "SELECT * FROM evaluation as E, trajet as T, user as U WHERE E.evalue_user_id=".$_SESSION['id']." AND T.id=E.trajet_id AND U.id=E.evaluateur_user_id ORDER BY E.id DESC";
        $reponse3 = $bdd->query($sql3);
        $compteur=0;
        $etoile=array("","★","★★","★★★","★★★★","★★★★★");
        while($donnee3 = $reponse3->fetch()){
            $compteur++;
            if($compteur<=10){     //on recupere les infos puis on crée un tableau pour y mettre ces infos
                //on met un compteur afin de n'afficher que les 10 dernieres évaluations
                echo"<tr>";
                echo"<th>" . $donnee3["prenom"] ." ". $donnee3["nom"] ."</th>";
                echo"<th>" . $donnee3["lieu_depart"] . "</th>";
                echo"<th>" . $donnee3["lieu_arrivee"] . "</th>";
                echo"<th>" . $donnee3["date"] . "</th>"; 
                echo"<th><span style='font-size: 150%; color:#FCDC12;'>" . $etoile[$donnee3["evaluation"]] . "</span></th>";
                echo"</tr>";
            }
        }
        if($compteur==0){ //si il n'y aucune évaluation
            echo"</table>";
            echo"Vous n'avez jamais été évalué.";
        }
        else{
            echo"</table>";
        }

?>


    
    
    
    

    
    
    
    <?php $contenu = ob_get_clean();
    if($_SESSION["pilote"]==TRUE){
    require '../gabarit/gabarit_pilote.php';  //on choisit le gabarit on fonction si c'est un pilote ou passager
    }
    else{
    require '../gabarit/gabarit_passager.php';  
    }

}
else{
        header ('Location: ../index.php'); //si ce l'utilisateur n'est pas connecté on le redirige vers l'index du site
	exit();
}
