<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Movies</title>

    <!-- Bootstrap Core CSS -->

    <link href="css/bootstrap.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    <!-- Page Header -->
    
    <div class="header">

        <div class="page-header">
            <img class="logo" src="css/imgs/logo_mini_blanc.png"/><!-- logo -->
            
            <p class="welcome">WELCOME !</p>
        	<p>You want to see the movies that you have in your datas ?</br> 
			Then, click right bellow and you will see them</p>
            
			<input type="button" OnClick="javascript:window.location.reload()" class="btn" value="Display movies" href><!-- button "Display movies" -->
        </div>
    </div>
        
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a class="menu" href="index.php">Your movies</a>
                    </li>
                    <li>
                        <a class="menu" href="webmovies.php">Web movies</a>
                    </li>

                </ul>
                <div id="overlay">
                    <div class="popup-block">
                        <a class="close" href="#noWhere"><img alt="Fermer" title="Fermer la fenêtre" class="btn-close" src="css/imgs/exit.png"></a>
                        <h2>Popup</h2>
                        
                        <p>Choose your options and give the link file of your movies.</p>
                    
                    </div>
                </div>
            	<p><a href="#overlay"><img class="img-options" src="css/imgs/btn_settings-small.png"></a></p>
                <!--
            	<div id="overlay"><a href="#nowehere">Exit</a></div>
                <a href="#overlay"><img class="img-options" src="css/imgs/btn_settings-small.png"></a>
                 do a opening window for the options 
                	<a href="php/pages/options.php" onclick="window.open('','popup','width=auto, height=200, top=auto, left=auto, toolbar=0, location=0, directories=0, status=0, menubar=0, scrollbars=0, resizable=1')" target="popup"><img class="img-options" src="css/imgs/btn_settings-small.png"></a>
                -->
                   
              </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">


        <!-- /.row -->
		<?php
			//$id_films = $_POST['id_films']; // Récupération de la données data{}
			//1 - connexion au serveur de BD
			$link = mysqli_connect("localhost", "root", "", "cinema_aida") or die("Problème de connexion...");
			
			if (!mysqli_set_charset($link, "utf8")){
				printf("Erreur lors du chargement du jeu de caractères utf8 : %s\n",
				mysqli_error($link));
			}    
					
			//2 - envoi de la requête
			$sql = "SELECT DISTINCT film_nom, film_annee, film_genre, film_duree, film_img FROM films INNER JOIN cinema ON cinema.id_cinema=cinema.id_cinema ORDER BY film_annee DESC LIMIT 4";
			$result = mysqli_query($link, $sql);
       
        ?>
        <!-- Projects Row -->
        <div class="row">
            <!-- boucle pour afficher les films -->
            <?php			
				while ($row=mysqli_fetch_array($result)){				
            ?>
            
            <div class="col-md-3 portfolio-item">
                <div class="thumbnail">
                	<h4>
						<?php 
                            echo''.htmlspecialchars($row['film_nom']).''; 
                        ?>
                    </h4>
                </div>
            </div>
            <?php
				}
            ?>
        </div>
        <!-- /.row -->


        <!-- Pagination -->
        <div class="row text-center">

            <div class="col-lg-12">
                <ul class="pagination">
                    <li>
                        <a href="#">Previous</a>
                    </li>
                    <li class="active">
                        <a href="#">1</a>
                    </li>
                    <li>
                        <a href="#">2</a>
                    </li>
                    <li>
                        <a href="#">3</a>
                    </li>
                    <li>
                        <a href="#">4</a>
                    </li>
                    <li>
                        <a href="#">5</a>
                    </li>
                    <li>
                        <a href="#">Next</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /.row -->


        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">

                </div>
                
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
