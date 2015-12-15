<?php
	require_once("php/configs/project_root.php");
	require_once(ROOT_PATH.'php/configs/configs.php');
	require_once(ROOT_PATH."php/functions/lib_db_connect.php");
	require_once(ROOT_PATH."php/functions/lib_searchMovies.php");

    $connect= connectDB();//Connect the object "connectDB"
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Display movies in the DB -->
    <script type="text/javascript" src="js/display.js"></script>
    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

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
            <img class="logo" src="css/imgs/logo_mini_blanc.png"/><!-- logo site -->
            <p class="welcome">WELCOME !</p>
            <p></br>
               </p>
        </div><!-- /.page-header -->
    </div><!-- /.header -->

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
            </div><!-- /.navbar-header -->
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
              </div><!-- /.navbar-collapse -->
        </div><!-- /.container -->
    </nav>

<?php

	$movies = getInfoMovies($connect, array('idMovies' => array($_GET['id'], '=')));
    $movies = $movies[0];
	
    /*
        -- Test pour voir l'affichage des données sous forme de tableau --
        echo "<pre>";
	    print_r($movies);
	    echo "</pre>";
    */

    echo '<h2>'.$movies['Title'].'</h2><br>';
    echo 'Date de sortie: '.$movies['Year'].'';
    
    if($movies['genres'] !== false)
    {
        echo '<br>Genres: ';
        foreach($movies['genres'] as $genres)
    	{			
            echo $genres['Name'].' / ';
        }
    }
    else
    {
        echo '<br>Genres: Aucun genres trouvés dans la base de données';
    }

    if($movies['director'] !== false)
    {
        echo '<br>Réalisateurs: ';
        foreach($movies['director'] as $director)
        {           
            echo $director['FirstName'].' '.$director['LastName'].' / ';
        }
    }
    else
    {
        echo '<br>Réalisateurs: Aucun réalisateurs trouvés dans la base de données';
    }

    if($movies['actor'] !== false)
    {
        echo '<br>Acteurs principaux: ';
        foreach($movies['actor'] as $actor)
        {           
            echo $actor['FirstName'].' '.$actor['LastName'].' / ';
        }
    }
    else
    {
        echo '<br>Acteurs principaux: Aucun acteurs trouvés dans la base de données';
    }

    if($movies['studios'] !== false)
    {
        echo '<br>Studios de productions: ';
        foreach($movies['studios'] as $studios)
        {           
            echo $studios['Name'].' / ';
        }
    }
    else
    {
        echo '<br>Studios de productions: Aucun studios de productions trouvés dans la base de données';
    }

    if($movies['countries'] !== false)
    {
        echo '<br>Pays de productions: ';
        foreach($movies['countries'] as $countries)
        {           
            echo $countries['Name'].' / ';
        }
    }
    else
    {
        echo '<br>Pays de productions: Aucun pays de productions trouvés dans la base de données';
    }

    if($movies['writer'] !== false)
    {
        echo '<br>Scénaristes: ';
        foreach($movies['writer'] as $writer)
        {           
            echo $writer['FirstName'].' '.$writer['LastName'].' / ';
        }
    }
    else
    {
        echo '<br>Scénaristes: Aucun scénaristes trouvés dans la base de données';
    }

    if($movies['producer'] !== false)
    {
        echo '<br>Producteurs: ';
        foreach($movies['producer'] as $producer)
        {           
            echo $producer['FirstName'].' '.$producer['LastName'].' / ';
        }
    }
    else
    {
        echo '<br>Producteurs: Aucun producteurs trouvés dans la base de données';
    }

    echo '<br>Description: '.$movies['Description'].' min';
    
?>

</body>

</html>
