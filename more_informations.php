<?php
	require_once("php/functions/lib_db_connect.php");

    $connect= connectDB();//Connect the object "connectDB"

	$movies = $connect->query("SELECT * FROM movies WHERE idMovies = ".$_GET['id']."");
		foreach($movies as $row)
		{
?>
			<h2><?php echo ''.$row['Title'].''; ?></h2><br>
			<?php echo 'Année: '.$row['Year'].''; ?><br>
			<?php echo 'Durée: '.$row['Length'].' min'; ?><br>
			<?php echo 'Description: '.$row['Description'].''; ?>
<?php
		}
?>
