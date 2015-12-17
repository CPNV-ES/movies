<?php

  require_once(ROOT_PATH."php/configs/configs.php");
  require_once(ROOT_PATH.'php/functions/lib_searchMovies.php');

  if(isset($_POST['send']))
  {

    /* 
      We create a variable $... to facilitate writing of the SQL query , but also to prevent any evil who would use the PHP or JS,
      with htmlspecialchars().
    */

    $attr = array(); //Filter in the table "movies"
    $filter = array();//Filter in the tables like people, genres, ...

    /*
      This conditions check if the value of the field's name of the inupt matches the valeurs in th database
    */ 

    if(isset($_POST['namefilm']) && $_POST['namefilm'] != NULL)
    {
      $namefilm = $_POST['namefilm'];
      $attr['Title'] = ['%'.$namefilm.'%', 'LIKE' ];
    }

    if(isset($_POST['year']) && $_POST['year'] != NULL)
    {
      $year = $_POST['year'];
      $attr['Year'] = ["%".$year."%", 'LIKE' ];
    }

    if(isset($_POST['genre']) && $_POST['genre'] != NULL)
    {
      $genre = $_POST['genre'];
      $filter['genres'] = $genre;
    }

    if(isset($_POST['namedirector']) && $_POST['namedirector'] != NULL)
    {
      $namedirector = $_POST['namedirector'];
      $filter['director'] = '%'.$namedirector.'%';
    }

    if(isset($_POST['nameactor']) && $_POST['nameactor'] != NULL)
    {
      $nameactor = $_POST['nameactor'];
      $filter['actor'] = '%'.$nameactor.'%';
    }

    if(isset($_POST['studio']) && $_POST['studio'] != NULL)
    {
      $studio = $_POST['studio'];
      $filter['studios'] = $studio;
    }

    if(isset($_POST['country']) && $_POST['country'] != NULL)
    {
      $country = $_POST['country'];
      $filter['countries'] = $country;
    }

    if(isset($_POST['writer']) && $_POST['writer'] != NULL)
    {
      $writer = $_POST['writer'];
      $filter['writer'] = '%'.$writer.'%';
    }

    if(isset($_POST['producer']) && $_POST['producer'] != NULL)
    {
      $producer = $_POST['producer'];
      $filter['producer'] = '%'.$producer.'%';
    }

      /*  */
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
          <div class="col-md-3 portfolio-item">
            <div class="thumbnail">
              <b><?php echo ''.$row['Title'].''; ?></b><br>
              <?php echo 'Release date: '.$row['Year'].''; ?><br>
              <?php echo 'Duration: '.$row['Length'].' min'; ?><br>
              <p><a href="more_informations.php?id=<?php echo $row["idMovies"];?>" class="btn"><input type="button" class="btn-more-infos" value="More Informations"></a></p>
            </div><!-- /.thumbnail -->
          </div><!-- /.portfolio-item -->
        </form>
<?php
    		}
    	}
      /* Display "No results" if the value entered in a field isn't in the database */
    	else
      {
?>
        <div class="col-md-3 portfolio-item">
            <div class="thumbnail">
                <b>No results</b>
            </div><!-- /.thumbnail -->
        </div><!-- /.portfolio-item -->
<?php
      }
  }
  
