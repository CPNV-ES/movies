<?php
	require_once('./php/functions/lib_searchMovies.php');

    if(isset($_POST['requete']) && $_POST['requete'] != NULL)
    {
        /* On crée une variable $requete pour faciliter l'écriture de la requête SQL, mais aussi pour empêcher les éventuels malins qui utiliseraient du PHP ou du JS,
        avec la fonction htmlspecialchars(). "htmlspecialchars() est utilisable en MySQL et PDO */
        $request = htmlspecialchars($_POST['requete']);

		$movies = getMovies($connect, $request);

		if($movies !== false){
		    foreach($movies as $row)
			{
			?>
				<div class="col-md-3 portfolio-item">
					<div class="thumbnail">
						<b><?php echo''.$row['Title'].''; ?></b>
					</div>
				</div>
			<?php
			}
		}
		else{
			?>
			<div class="col-md-3 portfolio-item">
					<div class="thumbnail">
						Pas de résultats
					</div>
			  </div>
			<?php
		}
	}
