<?php
function getSourceId($db, $path){
    $query  = 'SELECT sources.`idSources` FROM sources ';
    $query .= 'WHERE sources.`Name` LIKE ? ';
    $query .= 'LIMIT 1';

    $req = $db->prepare($query);
    $req->bindParam(1, $path);

    if(!$req->execute()){
        $error = $req->errorCode();
        $error = "Erreur est survenu lors de l'execution de la requête ('$error')";
		//echo $error;
        return false;
    }

    if($req->rowCount() == 1){
        $result = $req->fetch(PDO::FETCH_LAZY);
        return $result["idSources"];
    }
    return false;
}
function insertSource($db, $path){
    $query  = 'INSERT INTO sources';
    $query .= '(`Name`) VALUE (?)';

    $req = $db->prepare($query);
    $req->bindParam(1, $path);

    if(!$req->execute()){
        $error = $req->errorCode();
        $error = "Erreur est survenu lors de l'execution de la requête ('$error')";
		//echo $error;
        return false;
    }

    if($req->rowCount() == 1){
        $result = $db->lastInsertId();
		//echo $error;
        return $result;
    }
    return false;
}
function getTypeId($db, $file_type){
    $query  = 'SELECT types.`idTypes` FROM types ';
    $query .= 'WHERE types.`Name` LIKE ? ';
    $query .= 'LIMIT 1';

    $req = $db->prepare($query);
    $req->bindParam(1, $file_type);

    if(!$req->execute()){
        $error = $req->errorCode();
        $error = "Erreur est survenu lors de l'execution de la requête ('$error')";
		//echo $error;
        return false;
    }

    if($req->rowCount() == 1){
        $result = $req->fetch(PDO::FETCH_LAZY);
        return $result["idTypes"];
    }

    return false;
}
function getFile($db, $source_id, $path, $file_name, $file_type_id){
    $query  = 'SELECT files.`idFiles` FROM files ';
    $query .= 'INNER JOIN paths ON paths.`idPaths` = files.`fkPaths` ';
    $query .= 'INNER JOIN sources ON sources.`idSources` = paths.`fkSources` ';
    $query .= 'INNER JOIN types ON types.`idTypes` = files.`fkTypes` ';
    $query .= 'WHERE sources.`idSources` = ? ';
    $query .= "AND paths.`Name` LIKE ? ";
    $query .= "AND files.`Name` LIKE ? ";
    $query .= 'AND types.`idTypes` = ? ';
    $query .= 'LIMIT 1';

    $req = $db->prepare($query);
    $req->bindParam(1, $source_id);
    $req->bindParam(2, $path);
    $req->bindParam(3, $file_name);
    $req->bindParam(4, $file_type_id);

    if(!$req->execute()){
        $error = $req->errorCode();
        $error = "Erreur est survenu lors de l'execution de la requête ('$error')";
		//echo $error;
        return false;
    }

    if($req->rowCount() == 1){
        $result = $req->fetch(PDO::FETCH_LAZY);
        return $result["idFiles"];
    }

    return false;
}
function insertFile($db, $source_id, $path, $file_name, $file_type_id){
    $query  = 'INSERT INTO paths ';
    $query .= '(`fkSources`, `Name`) VALUES (?, ?)';

    $req = $db->prepare($query);
    $req->bindParam(1, $source_id);
    $req->bindParam(2, $path);

    if(!$req->execute()){
        $error = $req->errorCode();
        $error = "Erreur est survenu lors de l'execution de la requête ('$error')";
		//echo $error;
        return false;
    }

    $path_id = false;
    if($req->rowCount() == 1){
        $path_id = $db->lastInsertId();
    }
    else{
        $error = "Aucune insertion n'a été faite lors de la requête d'insertion du chemin !!";
		//echo $error;
        return false;
    }

    $query  = 'INSERT INTO files ';
    $query .= '(`fkTypes`, `fkPaths`, `Name`) VALUE (?, ?, ?)';

    $req = $db->prepare($query);
    $req->bindParam(1, $file_type_id);
    $req->bindParam(2, $path_id);
    $req->bindParam(3, $file_name);

    if(!$req->execute()){
        $error = $req->errorCode();
        $error = "Erreur est survenu lors de l'execution de la requête ('$error')";
        //echo $error;
        return false;
    }

    $file_id = false;
    if($req->rowCount() == 1){
        $file_id = $db->lastInsertId();
    }
    else{
        $error = "Aucune insertion n'a été faite lors de la requête d'insertion du fichier !!";
		//echo $error;
        return false;
    }

    return $file_id;
}
