<?php
	
	//Comment
	$query = $connect ->query("SELECT DISTINCT * FROM movies
						   INNER JOIN files ON files.fkMovies = movies.idMovies ORDER BY idMovies") or die();   

    while ($donnees = $query->fetch())
    {   
?>
 	<div class="col-md-3 portfolio-item">
 		<div class="thumbnail">
 			<b><?php echo''.htmlspecialchars($donnees['Title']).''; ?></b>
 		</div>
 	</div>
<?php
	}
?>