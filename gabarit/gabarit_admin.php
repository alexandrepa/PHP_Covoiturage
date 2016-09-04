<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title><?php echo $title; ?></title> 
        <meta name="generator" content="Bootply" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="icon" type="image/png" href="../favicon.png" />
        <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" /><![endif]-->
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link href="../gabarit/css/bootstrap.min.css" rel="stylesheet">
        <script type="text/javascript" src="../gabarit/js/verif_form.js"></script>

        <!--[if lt IE 9]>
                <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link href="../gabarit/css/styles.css" rel="stylesheet">

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
                    <a href="../index.php" class="navbar-brand"><span class = "logonavbar">CARPE DIEM</span></a>
                </div>
                <ul class="nav navbar-nav navbar-right">
                    <p class="navbar-btn">
                        <a href="../membre/deconnexion.php" class="btn btn-danger btn-large"><i class="glyphicon glyphicon-off"></i> Deconnexion</a>
                    </p>
                </ul>
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
        <div class="row">
            <!--left-->
            <div class="col-md-3" id="leftCol">

            </div><!--/left-->

            <!--right-->
            <div class="col-md-9">
                <?php echo $contenu; ?>

            </div><!--/right-->
        </div><!--/row-->
    </div><!--/container-->



    <!-- script references -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="../gabarit/js/bootstrap.min.js"></script>
    <script src="../gabarit/js/scripts.js"></script>
</body>
</html>