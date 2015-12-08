<?php
	
	//Comment
	$query = $connect ->query("SELECT DISTINCT * FROM movies						   		
						   		INNER JOIN files ON files.fkMovies = movies.idMovies ORDER BY idMovies") or die();   

    while ($data = $query->fetch())
    {   
?>
 	<div class="col-md-3 portfolio-item">
 		<div class="thumbnail">
 			<?php echo 'Titre: '.htmlspecialchars($data['Title']).''; ?><br>
 			<?php echo 'Année: '.htmlspecialchars($data['Year']).''; ?><br>
 			<?php echo 'Durée: '.htmlspecialchars($data['Length']).' min'; ?><br>
 			<div id="overlay">
                    <div class="popup-block">
                        <a class="close" href=><img alt="Fermer" title="Fermer la fenêtre" class="btn-close" src="css/imgs/exit.png"></a>
	                        <p><?php echo '<img src=../../image/'.$data['Poster'].'>'; ?></p>
	                        <h2><?php echo ''.htmlspecialchars($data['Title']).''; ?></h2>
	                        
	                        <p><?php echo 'Année: '.htmlspecialchars($data['Year']).''; ?></p>
	                        <p><?php echo 'Durée: '.htmlspecialchars($data['Length']).' min'; ?></p>
	                        <p><?php echo 'Durée: '.htmlspecialchars($data['Description']).' min'; ?></p>
                    </div><!-- /.popup-block -->
                </div><!-- /.overplay -->
            	<p><a href="#overlay" class="btn">Plus d'infos</a></p>
 		</div>
 	</div>
<?php
	}
?>