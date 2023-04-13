<?php

//SLIM
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;


//page
use Page\Page;
use PageAdmin\PageAdmin;


//USER
use Model\User;
use \Sql\Sql;

session_start();
require_once __DIR__ . "/vendor/autoload.php";

$app = AppFactory::create();





$app->get('/', function(Request $request, Response $response, $args){
    $page = new Page();
    $page->setTpl("index");
    
    return $response;
});

$app->get("/admin", function(Request $request, Response $response, $args){
    User::verifyLogin();
    
    $pageAdmin = new PageAdmin();
    $pageAdmin->setTpl("index");

    return $response;
});

$app->get("/admin/login", function(Request $request, Response $response, $args){
   
    $pageLogin = new PageAdmin([
        "header" => false,
        "footer" => false
     ]);

    $pageLogin->setTpl("login");
    
    return $response;

});


$app->post("/admin/login" , function(Request $request, Response $response, $args){
   
    if($_SERVER['REQUEST_METHOD'] === "POST"){
       
        $data = $request->getParsedBody();  

        User::login($data["login"], $data["password"]);
        header("Location: /admin");
        exit;
    }
   
    return $response;
});

$app->get("/admin/logout", function(){

    User::logout();
    header("Location: /admin/login");
    exit;
});


$app->get("/admin/users", function(Request $request, Response $response, $args){
    User::verifyLogin();

    $users = User::listAll();
    
    $pageLogin = new PageAdmin();
    $pageLogin->setTpl("users", array(
        "users" => $users
    ));
    
   return $response;
});

$app->get("/admin/users/create", function(Request $request, Response $response, $args){
    User::verifyLogin();
    
    $pageLogin = new PageAdmin();
    $pageLogin->setTpl("users-create");
    
    return $response;
});
/*
$app->delete("/admin/users/{iduser}/delete", function(Request $request, Response $response, $args){
    User::verifyLogin();

    $user = new User();
    $user->get((int)$args["iduser"]);

    $user->delete();

    header("Location: /admin/users");
    exit;
});
*/
$app->get("/admin/users/{iduser}/delete", function(Request $request, Response $response, $args){
    User::verifyLogin();

    $user = new User();
    $user->get((int)$args["iduser"]); //pucha os dados baseados no id e seta

    $user->delete(); //deleta baseado no id

    return $response->withHeader('Location', '/admin/users')->withStatus(302);
});


$app->post("/admin/users/create", function(Request $request, Response $response, $args){
    User::verifyLogin(); // verificando login
    $user = new User(); //instanciando novo usuaário
    $response = $request->getParsedBody(); // pega os dados

    $response['inadmin'] = isset($response['inadmin']) ? 1 : 0; //eu pego o post com inadmin 1 ou 0

    $user->setData($response); //eu crio um set para cada post 
    $user->save(); // salvando no banco
    header("Location: /admin/users");
    exit;

  });

$app->get("/admin/users/{iduser}", function(Request $request, Response $response, $args){
    User::verifyLogin();

    $user = new User();
    $user->get((int)$args["iduser"]);
    
    $page = new PageAdmin();

    $page->setTpl("users-update", array(
        "user" => $user->getValues()
    ));
});

$app->post("/admin/users/{iduser}", function(Request $request, Response $response, $args){
    User::verifyLogin();
    
    $user = new User();

    $results = $request->getParsedBody();
    $results['inadmin'] = isset($results['inadmin']) ? 1 : 0; //eu pego o post com inadmin 1 ou 0

    $user->get((int)$args["iduser"]);

    $user->setData($results);
    $user->update();
    header("Location: /admin/users");
    exit;

});

$app->get('/admin/forgot', function(Request $request, Response $response, $args){
    $pageLogin = new PageAdmin([
        "header" => false,
        "footer" => false
     ]);

    $pageLogin->setTpl("forgot");
    
    return $response;
});


$app->post('/admin/forgot', function(Request $request, Response $response, $args){

    $results = $request->getParsedBody();


    $user = User::getForgot( $results['email']);
});

$app->run();

?>