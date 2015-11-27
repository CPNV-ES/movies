<html>
<head>
	<meta charset="UTF-8">
	<title>Recherche</title>
</head>
<body>

	<form action="recherche.php" method="post">
		<input type="text" name="requete" size="30" placeholder="recherche">
		<input type="submit" value="Ok">
	</form>

<?php
	/* On vérifie d'abord l'existence du POST et aussi si la requete n'est pas vide.
	Si elle est vide et si elle exsite, on se connecte à la base de données */
	if(isset($_POST['requete']) && $_POST['requete'] != NULL)
	{
		//Démarrer la session
		session_start();

		$host = "localhost";// Nom du serveur
		$db = "recherche"; //Nom de la base de données
		$name = "root"; //Nom de l'utilisateur
		$pwd = ""; //Mot de passe

/* Version MySQL */

/*  
	mysql_connect($host, $name, $pwd);
	mysql_select_db($db);
*/

		/* Version PDO */
		try
		{
			//Connexion au serveur et à la base de données
			$cnx = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8', $name, $pwd);
			//En cas d'erreur, affiche un message détaillé
			$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
		}

		catch (Exception $e) 
		{
			//Si la connexion échoue, affiche un message d'erreur
			die('Erreur :'.$e->getMessage());
		}

		/* On crée une variable $requete pour faciliter l'écriture de la requête SQL, mais aussi pour empêcher les éventuels malins qui utiliseraient du PHP ou du JS, 
		avec la fonction htmlspecialchars(). "htmlspecialchars() est utilisable en MySQL et PDO */
		$requete = htmlspecialchars($_POST['requete']);
		
/* Version MySQL */

/* $query = mysql_query("SELECT * FROM recherche 
					  WHERE nom LIKE '%$requete%' ORDER BY idRecherche DESC") or die(mysql_error); */

		/* Version PDO */
		$query = $cnx->query("SELECT * FROM recherche 
							  WHERE nom LIKE '%$requete%' ORDER BY idRecherche DESC") or die();

/* Version MySQL */

/* $nb_resultats = mysql_num_rows($query); */

		/* Version PDO */
		
		/* On utilise la fonction mysql_num_rows pour compter les résultats pour vérifier par après */
		$count = $query->rowCount();

		if($count != 0)
		{

/* Version MySQL */

/* while($donnees = mysql_fetch_array($query)) */

			/* Version PDO */

			/* On fait un while pour afficher la liste des fonctions trouvées, ainsi que l'id qui permettra de faire le lien vers la page de la fonction */
			while($donnees = $query->fetch())
			{
				echo $donnees['Nom'];
			}
				
		}
		/* Fini d'afficher les résultats ! Maintenant, nous allons afficher l'éventuelle 
		   erreur en cas d'échec de recherche et le formulaire.*/
				
		else
		{
			echo "<h3>Pas de résultats</h3>";	
		}

	}
?>


</body>
</html>