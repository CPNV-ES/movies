<?php
	require_once("php/configs/project_root.php");
	require_once(ROOT_PATH.'php/configs/configs.php');
	require_once(ROOT_PATH."php/functions/lib_db_connect.php");

    $connect= connectDB();//Connect the object "connectDB"
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Display movies in the DB -->
    <script type="text/javascript" src="js/display.js"></script>
    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
     <!-- Loading script -->   
	<script src="js/loading.js"></script>

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
            <p class="welcome">LIST OF MOVIES INFORMATIONS TAKED FROM THE WEB !</p>
            <p>Here you can see the movies taked from an application that</br>
               shows you some details about your films selected before</p>
            <input type="button" id="display" OnClick="display('1'); return false;" class="btn" value="Display movies" href><!-- button "Display movies" -->
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

    <!-- Page Content -->
    <div class="container">

        <!-- Field search -->
        <div class="search">
            <form class="webmovies-filters" action="webmovies.php" method="post">
                <input type="hidden" name="send">
                <input type="text" name="namefilm" size="30" placeholder="Name of movie">
                <input type="submit" value="Search"><br><br>
                <input type="year" name="year" size="4" placeholder="Year">
                <input type="text" name="genre" size="15" placeholder="Genre">
                <input type="text" name="namedirector" size="15" placeholder="Name of director">
                <input type="text" name="nameactor" size="15" placeholder="Name of actor"><br><br>
                <input type="text" name="studio" size="15" placeholder="Studio">
                <input type="text" name="country" size="15" placeholder="Country">
                <input type="text" name="writer" size="15" placeholder="Name of writer">
                <input type="text" name="producer" size="15" placeholder="Name of producer">
            </form>
        </div><!-- /.search -->

        <?php
            echo '<div id=search>';
                    include_once(ROOT_PATH."php/pages/search_web.php");//Include the page for the search movies
            echo '</div>';

            echo '<div id=1 style=display:none;>';
                    include_once(ROOT_PATH."php/pages/display_movies_web.php");//Include the page for the display movies
            echo '</div>';
        ?>

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
            </div><!-- /.col-lg-12 -->
        </div><!-- /.row -->

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                </div><!-- /.col-lg-12 -->
            </div><!-- /.row -->
        </footer>

    </div><!-- /.container -->
 
    <!-- for loading informations -->
    <div class="modal"><!-- Place at bottom of page --></div>
    
    <script src="js/clear.js"></script><!-- Clear the display of the search -->

</body>

</html>
