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
            <p class="welcome">INFORMATIONS OF YOUR MOVIE !</p>
            <p>You can click on "Web movies" to return to the list of the movies taked from the web.</br>
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
    /* Display the values based on the id of "idMovies" */
	$movies = getInfoMovies($connect, array('idMovies' => array($_GET['id'], '=')));
    $movies = $movies[0];
	
    /*
        -- Test to see the display of data in a table --
        echo "<pre>";
	    print_r($movies);
	    echo "</pre>";
    */
	echo '<div class="infos-block">';//Global div
	
	echo '<div class="infos-poster-block">';//Div for the poster of the movie
    echo '<img class="poster" src="'.$movies['Poster'].'">'; 
   	echo '</div>';
   
    echo '<div class="infos-text-block">';//Div for the informations

    echo '<h2>'.$movies['Title'].'</h2><br>';
    echo '<b>Release date: </b>'.$movies['Year'].'';
    
    if($movies['genres'] !== false)
    {
        echo '<br><b>Genres:</b> ';
        foreach($movies['genres'] as $genres)
    	{			
            echo $genres['Name'].' / ';
        }
    }
    else
    {
        echo '<br><b>Genres:</b> No genres found in the database';
    }

    if($movies['director'] !== false)
    {
        echo '<br><b>Director: </b>';
        foreach($movies['director'] as $director)
        {           
            echo $director['FullName'].' / ';
        }
    }
    else
    {
        echo '<br><b>Director:</b> No directors found in the database';
    }

    if($movies['actor'] !== false)
    {
        echo '<br><b>Main actors: </b>';
        foreach($movies['actor'] as $actor)
        {           
            echo $actor['FullName'].' / ';
        }
    }
    else
    {
        echo '<br></b>Main actors:</b> No main actors found in the database';
    }

    if($movies['studios'] !== false)
    {
        echo '<br><b>Production studios: </b>';
        foreach($movies['studios'] as $studios)
        {           
            echo $studios['Name'].' / ';
        }
    }
    else
    {
        echo '<br><b>Production studios:</b> No production studios found in the database';
    }

    if($movies['countries'] !== false)
    {
        echo '<br><b>Countries: </b>';
        foreach($movies['countries'] as $countries)
        {           
            echo $countries['Name'].' / ';
        }
    }
    else
    {
        echo '<br><b>Countries:</b> No countries found in the database';
    }

    if($movies['writer'] !== false)
    {
        echo '<br><b>Writers: </b>';
        foreach($movies['writer'] as $writer)
        {           
            echo $writer['FullName'].' / ';
        }
    }
    else
    {
        echo '<br><b>Writers:</b> No writers found in the database';
    }

    if($movies['producer'] !== false)
    {
        echo '<br><b>Producers: </b> ';
        foreach($movies['producer'] as $producer)
        {           
            echo $producer['FullName'].' / ';
        }
    }
    else
    {
        echo '<br><b>Producers: </b> No producers found in the database';
    }
	
	echo '<div class="infos-description-block"';
    echo '<br><h2>Description: </h2><br>'.$movies['Description'].'';
    echo '</div>';
	echo '</div>';
?>

</body>

</html>
