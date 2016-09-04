<?php
session_start();
if (isset($_SESSION['login'])) {          //si c'est un membre qui est connecté alors on le renvoi à la page index membre
    header('Location: membre/index.php');
    exit();
}
?>

<!-- On utilise le framework bootstrap pour génerer le html et CSS afin d'avoir une esthétique moderne mais qui ne prend pas trop de temps à réaliser".-->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title>Bienvenue</title>
        <meta name="generator" content="Bootply" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="icon" type="image/png" href="favicon.png" />
        <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="favicon.ico" /><![endif]-->
        <link href="gabarit/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
                <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link href="gabarit/css/styles.css" rel="stylesheet">

    </head>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top" role="banner">
            <div class="container">
                <div class="navbar-header">
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="#" class="navbar-brand"><span class = "logonavbar">CARPE DIEM</span></a>
                </div>
                <nav class="collapse navbar-collapse" role="navigation">

                    <ul class="nav navbar-nav navbar-right">
                        <p class="navbar-btn">
                            <a href="membre/connexion.php" class="btn btn-primary btn-large"><i class="glyphicon glyphicon-share-alt"></i> Connexion</a>
                            <a href="inscription/inscription.php" class="btn btn-success btn-large"><i class="glyphicon glyphicon-arrow-up"></i> Inscription</a>
                        </p>



                    </ul>
                </nav>
            </div>
        </nav>

        <div id="masthead">  
            <div class="container">
                <div class="row">
                    <div class="col-md-7"><br><br>
                        <h1><span class = "logop2"><span class = "logop1">CAR</span>PE DIEM</span>
                            <p class="lead"><span id = "sloganp1">Vivez le covoiturage </span><span id = "sloganp2">au jour le jour.</span></p>
                        </h1>
                    </div>

                </div>
            </div> 
        </div><!--/container-->
    </div><!--/masthead-->

    <!--main-->
    <div class="container">
        <section>
        <blockquote class="slogan text-center">Bienvenue sur la solution de covoiturage CARPE DIEM, connectez vous ou créez un compte pour commencer. </blockquote>
        </section>
        <section>
        <div class="row features" style="margin-top:25px;">
        <div class="col-lg-4 text-center">
            <div class="single-fet">
                <div>
               <span class="fa-stack fa-4x">
  <i class="fa fa-circle fa-stack-2x"></i>
  <i class="fa fa-car fa-stack-1x fa-inverse"></i>
</span>
                </div>
                <h2>Une solution covoiturage</h2>

                <p>
                    Trouvez facilement et rapidement des compagnons de voyage grâce pour vos trajets en voiture.
                </p>

            </div>
        </div>
        <div class="col-lg-4 text-center">
            <div class="single-fet">
                <div>
                  <span class="fa-stack fa-4x">
  <i class="fa fa-circle fa-stack-2x"></i>
  <i class="fa fa-users fa-stack-1x fa-inverse"></i>
</span>
                </div>
                <h2>Un site communautaire</h2>

                <p>
                    Fort d'une communauté fidèle et responsable, CARPE DIEM propose une alternative écologique efficace.
                </p>

            </div>
        </div>
        <div class="col-lg-4 text-center">
            <div class="single-fet">
                <div>
           <span class="fa-stack fa-4x">
  <i class="fa fa-circle fa-stack-2x"></i>
  <i class="fa fa-usd fa-stack-1x fa-inverse"></i>
</span>
                </div>
                <h2>Un système sécurisé</h2>

                <p>
                    Puisque les trajets ne sont pas gratuit, CARPE DIEM possède un système de gestion de payement sécurisé, pour que chacun puisse voyager à moindre prix.
                </p>

            </div>
        </div>

    </div>

            </section>
</div>
        <div class="container">

            <hr><hr>
        <div class="text-center" style="border-top: 1px solid;">
            <p><center>Alexandre Patelli - Florian Culié</center></p>
        <p><center>Université de Technologie de Troyes - LO07</center></p>
            <br />
                <a href="#"><i id="social" class="fa fa-facebook-square fa-3x"></i></a>
	            <a href="#"><i id="social" class="fa fa-twitter-square fa-3x"></i></a>
	            <a href="#"><i id="social" class="fa fa-google-plus-square fa-3x"></i></a>
	            <a href="#"><i id="social" class="fa fa-envelope-square fa-3x"></i></a>
</div>
        </div>



  

    <!-- script references -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="gabarit/js/bootstrap.min.js"></script>
    <script src="gabarit/js/scripts.js"></script>
</body>
</html>