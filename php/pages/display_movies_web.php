<?php
	require_once('/php/functions/lib_searchMovies.php');

	$movies = getInfoMovies($connect);

	if($movies !== false){
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
            <div class="col-md-3 portfolio-item">
                <div class="thumbnail">
                    Pas de résultats
                </div>
            </div>
            
		<?php
	}
