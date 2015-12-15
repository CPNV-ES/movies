<?php
	require_once(ROOT_PATH."php/configs/configs.php");
	require_once(ROOT_PATH.'php/functions/lib_searchMovies.php');

	$movies = getMovies($connect);

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
