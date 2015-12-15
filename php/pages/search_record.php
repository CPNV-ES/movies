<?php

    if(isset($_POST['requete']) && $_POST['requete'] != NULL)
    {
        /* On crée une variable $requete pour faciliter l'écriture de la requête SQL, mais aussi pour empêcher les éventuels malins qui utiliseraient du PHP ou du JS, 
        avec la fonction htmlspecialchars(). "htmlspecialchars() est utilisable en MySQL et PDO */
        $requete = htmlspecialchars($_POST['requete']);

        $query = $connect ->query("SELECT * FROM movies 
                                WHERE Title LIKE '%$requete%' ORDER BY idMovies") or die();

        /* On utilise la fonction mysql_num_rows pour compter les résultats pour vérifier par après */
        $count = $query->rowCount();

        if($count != 0)
        {
            /* On fait un while pour afficher la liste des fonctions trouvées, ainsi que l'id qui permettra de faire le lien vers la page de la fonction */
            while($data = $query->fetch())
            {
                echo '<div class="row">
						<div class="col-md-3 portfolio-item">
                            <div class="thumbnail">
                               <b>'.htmlspecialchars($data['Title']).'</b>
                            </div>
						</div>
                      </div>';
                }      
            }
            /* Affichage d'un message d'erreur*/      
            else
            {
                echo '<div class="row">
						<div class="col-md-3 portfolio-item">
                            <div class="thumbnail">
                                Pas de résultats
                            </div>
                      	</div>
					  </div'; 
            }
        }
