<?php 
 
//Cette méthode est fonctionnel si la classe db (classe de connexion a la base) est existante et si on appel cette classe dans la page ou on fait notre pagination.
	$compteur = $DB->query("SELECT count(*) AS total FROM films"); 
	//$nbAbonnes = $compteur->total; 
	foreach ($compteur as $ligne) { 
	$total = $ligne->total; 
		} 
		$abonnesParPage = 20;
		$nbPage = ceil($total/$abonnesParPage);
		if(isset($_GET['page']) && $_GET['page']>0 && $_GET['page']<=$nbPage){ 
		$cPage = $_GET['page']; 
		} 
		
	else{ 
		$cPage = 1; 
	} 
	?> 
    
	$resultats = $DB->query("SELECT * FROM abonnes LIMIT ".(($cPage-1)*$abonnesParPage).", ".$abonnesParPage); 
	// Pour afficher les résultats de la recherche 
	...
	<?php 
	for ($i=1; $i<=$nbPage; $i++){ 
	if($i == $cPage){ 
		echo "<li><a href='#'>$i</a></li>"; 
	} 
	
	else{ 
		echo "<li><a href='listeAbonnes.php?page=$i'>$i</a></li>"; 
	} 
	
	} 
?>