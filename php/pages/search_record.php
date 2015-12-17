<?php
	require_once(ROOT_PATH.'php/functions/lib_searchMovies.php');

    /* Verify if the variable $_POST['requete] is set and not null */
    if(isset($_POST['requete']) && $_POST['requete'] != NULL)
    {
        /* 
            We create a variable $... to facilitate writing of the SQL query , but also to prevent any evil who would use the PHP or JS,
            with htmlspecialchars().
        */
        $request = $_POST['requete'];

		$movies = getMovies($connect, $request);

		if($movies !== false)
		{
		    foreach($movies as $row)
			{
?>				
                <div class="col-md-3 portfolio-item">
                    <div class="thumbnail">
                        <b><?php echo''.$row['Title'].''; ?></b>
                    </div><!-- /.thumbnail -->
                </div><!-- /.portfolio-item --> 

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
	}

