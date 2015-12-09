<?php
	
	//Comment
	$query = $connect ->query("SELECT DISTINCT * FROM movies
						   INNER JOIN files ON files.fkMovies = movies.idMovies ORDER BY idMovies") or die();   

    while ($data = $query->fetch())
    {   
?>
 	<div class="col-md-3 portfolio-item">
 		<div class="thumbnail">
<<<<<<< HEAD
 			<b><?php echo''.htmlspecialchars($donnees['Title']).''; ?></b>
=======
 			<?php echo''.htmlspecialchars($data['Title']).''; ?>
>>>>>>> 2b8e7cbb7766cfb952ff2198ae2f488c16da50c2
 		</div>
 	</div>
<?php
	}
?>