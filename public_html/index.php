<?php
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	require '../vendor/autoload.php';

    $hybrid_auth = new Hybrid_Auth('../app/config/hybrid-auth.php');

	$configuration = [
	    'settings' => [
	        'displayErrorDetails' => true
	    ],
	];

	$app = new \Slim\App($configuration);

	// Get container
	$container = $app->getContainer();

	// Register component on container
	$container['view'] = function ($container) {
	    $view = new \Slim\Views\Twig('../app/views/', [
	        'cache' => false
	    ]);
	    $view->addExtension(new \Slim\Views\TwigExtension(
	        $container['router'],
	        $container['request']->getUri()
	    ));

	    return $view;
	};

    $app->get('/', function (Request $request, Response $response) {
        return $this->view->render($response, 'home/index.html', []);
    });

	$app->get('/admin/index', function (Request $request, Response $response) use ($hybrid_auth) {
        $adapter = $hybrid_auth->authenticate( "Google" );
        die(json_encode($adapter->getUserProfile()));
	    return $this->view->render($response, 'admin/home/index.html', []);
	});

	$app->get('/api/donation-requests/list', function (Request $request, Response $response) {
	    $client = new MongoDB\Client("mongodb://localhost:27017");
		$collection = $client->ffd->furniture_requests;
		$cursor = $collection->find([],
	    [
	        'sort' => ['priority' => 1],
	    ]);
		$result = [];

		foreach($cursor as $doc) {
			$result[] = $doc;
		}

		return $response->withJson($result);
	});

    $app->get('/auth', function(Request $request, Response $response) {
        Hybrid_Endpoint::process();
    });

	$app->run();