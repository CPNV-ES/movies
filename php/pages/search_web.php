<?php

  require_once(ROOT_PATH."php/configs/configs.php");
  require_once(ROOT_PATH.'php/functions/lib_searchMovies.php');

  if(isset($_POST['send']))
  {

    /* 
      We create a variable $... to facilitate writing of the SQL query , but also to prevent any evil who would use the PHP or JS,
      with htmlspecialchars().
    */

    $attr = array(); //filtre sur la table movies
    $filter = array();//filtre sur le reste

    /*
      This condition checks if the value of the field's name of the inupt matches the valeurs in th database
    */ 

    if(isset($_POST['namefilm']) && $_POST['namefilm'] != NULL)
    {
      $namefilm = htmlspecialchars($_POST['namefilm']);
      $attr['Title'] = ['%'.$namefilm.'%', 'LIKE' ];
    }

    if(isset($_POST['year']) && $_POST['year'] != NULL)
    {
      $year = htmlspecialchars($_POST['year']);
      $attr['Year'] = ["%".$year."%", 'LIKE' ];
    }

    if(isset($_POST['genre']) && $_POST['genre'] != NULL)
    {
      $genre = htmlspecialchars($_POST['genre']);
      $filter['genres'] = $genre;
    }

    if(isset($_POST['namedirector']) && $_POST['namedirector'] != NULL)
    {
      $namedirector = htmlspecialchars($_POST['namedirector']);
      $filter['director'] = '%'.$namedirector.'%';
    }

    if(isset($_POST['nameactor']) && $_POST['nameactor'] != NULL)
    {
      $nameactor = htmlspecialchars($_POST['nameactor']);
      $filter['actor'] = '%'.$nameactor.'%';
    }

    if(isset($_POST['studio']) && $_POST['studio'] != NULL)
    {
      $studio = htmlspecialchars($_POST['studio']);
      $filter['studios'] = $studio;
    }

    if(isset($_POST['country']) && $_POST['country'] != NULL)
    {
      $country = htmlspecialchars($_POST['country']);
      $filter['countries'] = $country;
    }

    if(isset($_POST['writer']) && $_POST['writer'] != NULL)
    {
      $writer = htmlspecialchars($_POST['writer']);
      $filter['writer'] = '%'.$writer.'%';
    }

    if(isset($_POST['producer']) && $_POST['producer'] != NULL)
    {
      $producer = htmlspecialchars($_POST['producer']);
      $filter['producer'] = '%'.$producer.'%';
    }

      $movies = getInfoMovies($connect, $attr, $filter);

      /*
        This display the value in the array of the variable $movies

          print_r($movies);
      */

      /* Verify if the variable $movies is not empty */
      if(!empty($movies))
      {
    		foreach($movies as $row)
    		{
?>
  		    <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="get">
            <div class="col-md-6 portfolio-item">
              <div class="thumbnail">
                <b><?php echo 'Titre: '.$row['Title'].''; ?></b><br>
                <?php echo 'Date de sortie: '.$row['Year'].''; ?><br>
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
  
