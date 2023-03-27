<?php


require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Database/DatabaseConnection.php';

use Model\Person;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app = AppFactory::create();
$app->addBodyParsingMiddleware();
// list all persons
$app->get('/persons', function (Request $request, Response $response) {
    $persons = Person::all();

    $response->getBody()->write(json_encode($persons));
    return $response->withHeader('Content-Type', 'application/json');
});

// get a person by ID
$app->get('/persons/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];

    $person = Person::find($id);
    if (!$person) {
        return $response->withStatus(404);
    }
    $response->getBody()->write(json_encode($person));
    return $response->withHeader('Content-Type', 'application/json');
});

// create a new person
$app->post('/persons', function (Request $request, Response $response) {
    $data = $request->getParsedBody();

    $person = new Person;
    $person->name = $data['name'];
    $person->age = $data['age'];
    $person->save();

    $response->getBody()->write(json_encode($person));
    return $response->withHeader('Content-Type', 'application/json');
});

// update a person by ID
$app->put('/persons/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];
    $data = $request->getParsedBody();

    $person = Person::find($id);

    if (!$person) {
        $response->withStatus(404);
        $response->getBody()->write(json_encode([
            'message' => 'Person not found'
        ]));
    } else {
        $person->name = $data['name'];
        $person->age = $data['age'];
        $person->save();
        $response->getBody()->write(json_encode($person));
    }
    return $response->withHeader('Content-Type', 'application/json');
});

// delete a person by ID
$app->delete('/persons/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];
    $person = Person::find($id);
    if (!$person) {
        $response->withStatus(404);
        $response->getBody()->write(json_encode([
            'message' => 'Person not found'
        ]));
    } else {
        $person->delete();
        $response->withStatus(201);
        $response->getBody()->write(json_encode(['message' => "Successfully Deleted."]));
    }
    return $response->withHeader('Content-Type', 'application/json');
});


$app->run();
