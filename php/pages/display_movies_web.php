<?php
	
	//Comment
	$query = $connect ->query("SELECT DISTINCT * FROM movies
						   INNER JOIN files ON files.fkMovies = movies.idMovies ORDER BY idMovies") or die();   

    while ($donnees = $query->fetch())
    {   
?>
 	<div class="col-md-3 portfolio-item">
 		<div class="thumbnail">
 			<?php echo'Titre: '.htmlspecialchars($donnees['Title']).''; ?><br>
 			<?php echo'Année: '.htmlspecialchars($donnees['Year']).''; ?><br>
 			<?php echo'Durée: '.htmlspecialchars($donnees['Length']).' min'; ?><br>
 			<button id="<?php echo $donnee['idMovies'] ?>" onClick="More; return false;">Plus d'infos</button>
 		</div>
 	</div>
<?php
	}
?>