<?php
    use Page\Page;
    use PageAdmin\PageAdmin;
    use Model\User;


    use Slim\Factory\AppFactory;
    use Slim\Http\Response;
    use Slim\App;
    require __DIR__ . '/vendor/autoload.php';

    //$app = AppFactory::create();

    $app = new App();

    //$app->config("debug", true);

    $app->get("/admin", function($request, $response, $args){
        //$pageAdmin = new PageAdmin();

        //$pageAdmin->setTpl("index");
        $reponse =  new Response();
        $response->getBody()->write('Hello, world!');
        return $response;
    });


    $app->get("/admin/login/", function(){
        $pageLogin = new PageAdmin([
            "header" => false,
            "footer" => false
        ]);

        $pageLogin->setTpl("login");    

    });

    $app->post("/admin/login/", function(){

        //User::login($_POST["login"], $_POST["password"]);
        //header("Location /ecomerce/admin");
        //exit;
        echo "teste";

    });



   $app->get("/", function(){
        $page = new Page();

        $page->setTpl("index"); 
    });

   



    $app->run();
?>