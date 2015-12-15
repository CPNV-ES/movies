<?php
	require_once(ROOT_PATH."php/configs/configs.php");
	require_once(ROOT_PATH.'php/functions/lib_searchMovies.php');

	$movies = getInfoMovies($connect);

	if($movies !== false){
<<<<<<< HEAD
	  foreach($movies as $row)
	  {
		?>
		 	<div class="col-md-6 portfolio-item">
		 		<div class="thumbnail">
		 			<b><?php echo 'Titre: '.$row['Title'].''; ?></b><br>
		 			<?php echo 'Année: '.$row['Year'].''; ?><br>
		 			<?php echo 'Durée: '.$row['Length'].' min'; ?><br>
		 			<div id="overlay">
		                    <div class="popup-block">
		                        <a class="close" href=><img alt="Fermer" title="Fermer la fenêtre" class="btn-close" src="css/imgs/exit.png"></a>
			                        <p><?php echo '<img src=../../image/'.$row['Poster'].'>'; ?></p>
			                        <h2><?php echo ''.$row['Title'].''; ?></h2>

			                        <p><?php echo 'Année: '.$row['Year'].''; ?></p>
			                        <p><?php echo 'Durée: '.$row['Length'].' min'; ?></p>
			                        <p><?php echo 'Durée: '.$row['Description'].' min'; ?></p>
		                    </div><!-- /.popup-block -->
		                </div><!-- /.overplay -->
		            	<p><a href="#overlay" class="btn">Plus d'infos</a></p>

		 		</div>
		 	</div>
	<?php
=======
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
>>>>>>> 0545f393c9d4b5ced898e5b7e5326a28f05e3cb2
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
