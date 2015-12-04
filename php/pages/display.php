<?php
	$query = $cnx ->query("SELECT DISTINCT film_nom, film_annee, film_genre, film_duree, film_img FROM films 
						   INNER JOIN cinema ON cinema.id_cinema=cinema.id_cinema ORDER BY film_annee") or die();   

    while ($donnees = $query->fetch())
    {   
?>
 	<div class="col-md-3 portfolio-item">
 		<div class="thumbnail">
 			<?php echo''.htmlspecialchars($donnees['film_nom']).''; ?>
 		</div>
 	</div>
<?php
	}
?>