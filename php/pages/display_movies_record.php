<?php
	
	//Comment
	$query = $connect ->query("SELECT DISTINCT * FROM movies
						   INNER JOIN files ON files.fkMovies = movies.idMovies ORDER BY idMovies") or die();   

    while ($donnees = $query->fetch())
    {   
?>
 	<div class="col-md-3 portfolio-item">
 		<div class="thumbnail">
 			<?php echo''.htmlspecialchars($donnees['Title']).''; ?>
 		</div>
 	</div>
<?php
	}
?>