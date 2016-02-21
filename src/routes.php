<?php
// Routes
$app->get('/', function ($request, $response, $args) {
    // Render index view
    return $this->view->render($response,'index.phtml',[]);
});

$app->get('/api/wines', function ($request, $response, $args) use ($pdo) {
    //Définition de la requête
    $query = $pdo->query('SELECT * FROM wine');
    
    //Extraction des données
    $wines = $query->fetchAll(PDO::FETCH_ASSOC);
 
    //Conversion en flux JSON
    $wines = json_encode(['wine'=>$wines]);
    
    // Rendu du flux JSON
    return $this->response->getBody()->write($wines);
});

$app->get('/api/wines/search/{keyword}', function ($request, $response, $args) use ($pdo) {
    //Préparation de la requête paramétrée
    $stmt = $pdo->prepare('SELECT * FROM wine WHERE name LIKE :name');
    
    //Exécution de la requête et injection des données pour sécurité
    $stmt->execute([':name' => '%'.$args['keyword'].'%']);
    
    //Extraction des données
    $wines = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
    //Conversion en flux JSON
    $wines = json_encode(['wine'=>$wines]);
    
    // Rendu du flux JSON
    return $this->response->getBody()->write($wines);
});

$app->get('/api/wines/{id:[0-9]+}', function ($request, $response, $args) use ($pdo) {       
    //Préparation de la requête paramétrée
    $stmt = $pdo->prepare('SELECT * FROM wine WHERE id=:id');
    
    //Exécution de la requête et injection des données pour sécurité
    $stmt->execute([':id' => $args['id']]);
    
    //Extraction des données
    $wine = $stmt->fetch(PDO::FETCH_ASSOC);
 
    //Conversion en flux JSON
    $wine = json_encode($wine);
    
    // Rendu du flux JSON
    return $this->response->getBody()->write($wine);
});

$app->post('/api/wines', function ($request, $response, $args) use ($pdo) {
    //Préparation de la requête paramétrée
    $stmt = $pdo->prepare('INSERT INTO wine(name, year, grapes, country, region, description, picture) 
        VALUES (:name, :year, :grapes, :country, :region, :description, :picture)');
    
    $parsedBody = $request->getParsedBody();
    
    $stmt->bindParam(':name',$parsedBody['name'],PDO::PARAM_STR);
    $stmt->bindParam(':year',$parsedBody['year'],PDO::PARAM_STR);
    $stmt->bindParam(':grapes',$parsedBody['grapes'],PDO::PARAM_STR);
    $stmt->bindParam(':country',$parsedBody['country'],PDO::PARAM_STR);
    $stmt->bindParam(':region',$parsedBody['region'],PDO::PARAM_STR);
    $stmt->bindParam(':description',$parsedBody['description'],PDO::PARAM_STR);
    $stmt->bindParam(':picture',$parsedBody['picture'],PDO::PARAM_STR);
    
    //Exécution de la requête et injection des données pour sécurité
    $result = $stmt->execute();
    
    // Rendu du flux JSON
    return $this->response->getBody()->write($result);
});

$app->put('/api/wines/{id:[0-9]+}', function ($request, $response, $args) use ($pdo) {       
    //Préparation de la requête paramétrée
    $stmt = $pdo->prepare('UPDATE wine SET name = :name, 
            year = :year, 
            grapes = :grapes,
            country = :country,
            region = :region,
            description = :description,
            picture = :picture
            WHERE id=:id');
    
    $parsedBody = $request->getParsedBody();
    
    $stmt->bindParam(':name',$parsedBody['name'],PDO::PARAM_STR);
    $stmt->bindParam(':year',$parsedBody['year'],PDO::PARAM_STR);
    $stmt->bindParam(':grapes',$parsedBody['grapes'],PDO::PARAM_STR);
    $stmt->bindParam(':country',$parsedBody['country'],PDO::PARAM_STR);
    $stmt->bindParam(':region',$parsedBody['region'],PDO::PARAM_STR);
    $stmt->bindParam(':description',$parsedBody['description'],PDO::PARAM_STR);
    $stmt->bindParam(':picture',$parsedBody['picture'],PDO::PARAM_STR);
    $stmt->bindParam(':id',$args['id'],PDO::PARAM_INT);
    
    //Exécution de la requête et injection des données pour sécurité
    $result = $stmt->execute();
    
    // Rendu du flux JSON
    return $this->response->getBody()->write($result);
});

$app->delete('/api/wines/{id:[0-9]+}', function ($request, $response, $args) use ($pdo) {       
    //Préparation de la requête paramétrée
    $stmt = $pdo->prepare('DELETE FROM wine WHERE id=:id');
    
    $stmt->bindParam(':id',$args['id'],PDO::PARAM_INT);
    
    //Exécution de la requête et injection des données pour sécurité
    $result = $stmt->execute();
    
    // Rendu du flux JSON
    return $this->response->getBody()->write($result);
});

$app->get('/api/wines/filter/{fieldName}/{filterKey}', function ($request, $response, $args) use ($pdo) {
    //Préparation de la requête paramétrée
    $stmt = $pdo->prepare('SELECT * FROM wine WHERE '.$args['fieldName'].' LIKE :filterKey');
    
    //Exécution de la requête et injection des données pour sécurité
    $stmt->execute([':filterKey' => '%'.$args['filterKey'].'%']);
    
    //Extraction des données
    $wines = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
    //Conversion en flux JSON
    $wines = json_encode(['wine'=>$wines]);
    
    // Rendu du flux JSON
    return $this->response->getBody()->write($wines);
});

$app->get('/catalogue', function ($request, $response, $args) {
    // Render index view
    return $this->response->getBody()->write("TODO: Catalogue");
});