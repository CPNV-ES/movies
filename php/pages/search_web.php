<?php

  require_once(ROOT_PATH."php/configs/configs.php");
  require_once(ROOT_PATH.'php/functions/lib_searchMovies.php');

  if(isset($_POST['requete']) && $_POST['requete'] != NULL)
  {
    /* On crée une variable $requete pour faciliter l'écriture de la requête SQL, mais aussi pour empêcher les éventuels malins qui utiliseraient du PHP ou du JS,
    avec la fonction htmlspecialchars(). "htmlspecialchars() est utilisable en MySQL et PDO */
    $request = htmlspecialchars($_POST['requete']);

    $movies = getInfoMovies($connect, ['Title' => ['%'.$request.'%', 'LIKE' ]]);

    if($movies !== false)
    {
  		foreach($movies as $row)
  		{
?>
			 	<form action="<?php echo $_SERVER["PHP_SELF"];?>" method="get">
          <div class="col-md-6 portfolio-item">
            <div class="thumbnail">
              <b><?php echo 'Titre: '.$row['Title'].''; ?></b><br>
              <?php echo 'Année: '.$row['Year'].''; ?><br>
              <?php echo 'Durée: '.$row['Length'].' min'; ?><br>
              <p><a href="more_informations.php?id=<?php echo $row["idMovies"];?>" class="btn">Plus d'infos</a></p>
            </div><!-- /.thumbnail -->
          </div><!-- /.portfolio-item -->
        </form>
<?php
  		}
  	}
  	else
    {
?>
  		<div class="col-md-6 portfolio-item">
          <div class="thumbnail">
  			    Pas de résultats
          </div><!-- /.thumbnail -->
      </div><!-- /.portfolio-item -->
<?php
  	}
  }
