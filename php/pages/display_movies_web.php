<?php
	require_once(ROOT_PATH."php/configs/configs.php");
	require_once(ROOT_PATH.'php/functions/lib_searchMovies.php');

	$movies = getInfoMovies($connect);

	/* Verify if the variable "$movies" is different of false */
	if($movies !== false)
	{
		/* Makes a loop to display all informations contained in the variable "$movies" */
		foreach($movies as $row)
		{
?>
			<!-- Create a method "GET" for recover the id of "idMovies" and display the page "more_informations"-->
			<form action="<?php echo $_SERVER["PHP_SELF"];?>" method="get">
				<div class="col-md-3 portfolio-item">
				 	<div class="thumbnail">
				 		<b><?php echo $row['Title']; ?></b><br>
				 		<?php echo 'Release date: '.$row['Year'].''; ?><br>
				 		<?php echo 'Duration: '.$row['Length'].' min'; ?><br>
	            		<p><a href="more_informations.php?id=<?php echo $row["idMovies"];?>"><input type="button" class="btn-more-infos" value="More Informations"></a></p>
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
