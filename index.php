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
            <p>You want to see the movies that you have in your datas ?</br>
               Then, click right bellow and you will see them</p>
            <input type="button" id="display" OnClick="display('1'); return false;" class="btn" value="Display movies"><!-- button "Display movies" -->
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
                <div id="overlay">
                    <div class="popup-block">
                        <a class="close" href=><img alt="Fermer" title="Fermer la fenêtre" class="btn-close" src="css/imgs/exit.png"></a>

                        <h2>Options</h2>
                        
                        <p>Choose your options and give the link file of your movies.</p><br>  

                        <!-- button to uplod the files on the computer -->           	                  
                        <form method="post" action="mapage.php3" enctype="multipart/form-data"> 
                        	<input type="hidden" name="max-file-size" value="300000"> <!-- limit the size of the file(s) to upload-->
                        	<input type="file" name="mon-fichier" size="3" value="Browse">
						</form>                        
                        <br>    
                        
                        <input type="button" id="refresh" onClick="javascript:window.location.reload()" class="btn" value="Refresh"><!-- button to reload the page -->

                    </div><!-- /.popup-block -->
                </div><!-- /.overplay -->
            	<p><a href="#overlay"><img class="img-options" src="css/imgs/btn_settings-small.png"></a></p>
                <!--
            	<div id="overlay"><a href="#nowehere">Exit</a></div>
                <a href="#overlay"><img class="img-options" src="css/imgs/btn_settings-small.png"></a>
                 do a opening window for the options
                	<a href="php/pages/options.php" onclick="window.open('','popup','width=auto, height=200, top=auto, left=auto, toolbar=0, location=0, directories=0, status=0, menubar=0, scrollbars=0, resizable=1')" target="popup"><img class="img-options" src="css/imgs/btn_settings-small.png"></a>
                -->
              </div><!-- /.navbar-collapse -->
        </div><!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">

        <div class="search">
            <form action="index.php" method="post">
                <input type="text" name="requete" size="30" placeholder="Name of film">
                <input type="submit" value="Search">
            </form>
        </div><!-- /.search -->

        <?php
            echo '<div id=search>';
                    include_once(ROOT_PATH."php/pages/search_record.php");//Include the function search
            echo '</div>';

            echo '<div id=1 style=display:none;>';
                    include_once(ROOT_PATH."php/pages/display_movies_record.php");//Include the function display
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

    <script src="js/clear.js"></script><!-- Clear the display of the search -->

</body>

</html>
