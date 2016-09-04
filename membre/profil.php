<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require'../BDD.php';
$bdd = getBdd();

require '../droits.php';  //voir inscription.php
require '../formulaire.php';

test_membre_admin();
if (empty($_GET)) {                  //on peut afficher le profil d'un autre utilisateur par la méthode get avec comme variable l'username
    
	$username=$_SESSION["login"];  //on regarde donc si la page a été appelé par une methode GET, sinon on affiche le profil du membre qui accede à la page
}
else {
    $username=$_GET["username"];
}
ob_start();


$resultat=$bdd->query("SELECT * from user WHERE username ='".$username."'"); //on recupere les infos de l'user pour les afficher
$exist=0;
                    $donnee = $resultat->fetch(); //si la methode GET a été utilisée, on regarde si l'utilsateur existe bien afin d'afficher une erreur dans le cas inverse
                        
                        $exist=1;
                        ?>
                        <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <div class="col-md-12 lead"><?php echo $donnee["username"]." profile"; ?><hr></div>
          </div>
          <div class="row">
			<div class="col-md-4 text-center">                   <!-- On utilise bootstrap pour afficher de facon esthétique les infos -->
              <img class="img-thumbnail" style="-webkit-user-select:none; 
              display:block; margin:auto; width:200px;" src="<?php echo $donnee["photo"]; ?>">
            </div>
            <div class="col-md-8">
              <div class="row">
                <div class="col-md-12">
                  <h1 class="only-bottom-margin"><?php echo $donnee["prenom"]." ".$donnee["nom"]; ?></h1>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <span class="text-muted">Email:</span> <?php echo $donnee["email"]; ?><br>
                  <span class="text-muted">Birth date:</span> <?php echo $donnee["birthday"]; ?><br>
                  <span class="text-muted">Compte :</span> <?php echo $donnee["compte"]." €"; ?><br><br>
                  
                </div>
                <div class="col-md-6">
                  <div class="activity-mini">
                      <?php 
                       
                        if($donnee["note"]<2){
                            $note = "★</span>";
                        }
                        else if($donnee["note"]<3){
                            $note = "★★</span>";
                        }
                        else if($donnee["note"]<4){
                            $note = "★★★</span>";
                        }
                        else if($donnee["note"]<5){
                            $note = "★★★★</span>";
                        }
                        else {
                            $note = "★★★★★</span>";
                        }  
                        $note = "<span style='font-size: 150%; color:#FCDC12;'>".$note;
                      ?>
                    <i class="glyphicon glyphicon-star-empty"></i><?php echo "Evaluation : ".$note; ?>
                  </div>
                  
                </div>
              </div>
            </div>
          </div>
            <?php 
            
             $resultat2=$bdd->query("SELECT * from pilote WHERE pilote_user_id =".$donnee["id"]); 
 //on teste si c'est un pilote, si oui on affiche les infos de la voiture
                    $donnee2 = $resultat2->fetch();    
            
            if(!empty($donnee2)){     ?>
             <div class="row">
            <div class="col-md-12 lead">Voiture<hr></div>
          </div>
             <div class="row">
			<div class="col-md-4 text-center">
              <img class="img-thumbnail" style="-webkit-user-select:none; 
              display:block; margin:auto; width:200px;" src="<?php echo $donnee2["photo"]; ?>">
            </div>
            <div class="col-md-8">
              <div class="row">
                <div class="col-md-12">
                  <h1 class="only-bottom-margin"><?php echo $donnee2["voiture_marque"]." ".$donnee2["voiture_modele"]; ?></h1>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <span class="text-muted">Année:</span> <?php echo $donnee2["voiture_annee"]; ?><br>
                  <span class="text-muted">Couleur:</span> <?php echo $donnee2["voiture_couleur"]; ?><br>
                  <br>
                  
                </div>
                
              </div>
            </div>
          </div>   
       <?php        
            }
            
            ?>
            
            
            </div>
                            </div>
  <?php                 
 if($exist==0){
     echo"L'utilisateur n'existe pas";
 }

 $contenu=ob_get_clean();   
   
   
$title = "Profil";
gabarit();

