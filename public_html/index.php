<?php
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	require '../vendor/autoload.php';

	$slim_config = [
	    'settings' => [
	        'displayErrorDetails' => true
	    ]
	];

	$app = new \Slim\App($slim_config);

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

	$container['hybrid_auth'] = function($container) {
	    return new Hybrid_Auth('../app/config/hybrid-auth.php');
    };

    $container['mongo_db'] = function($container) {
        return new Hybrid_Auth('../app/config/hybrid-auth.php');
    };

    $app->get('/', function (Request $request, Response $response) {
        return $this->view->render($response, 'home/index.html', []);
    });

	$app->get('/admin[/]', function (Request $request, Response $response) {
        $auth = $this->hybrid_auth->authenticate( "Google" );
        $user = $auth->getUserProfile();
	    return $this->view->render($response, 'admin/home/index.html');
	});

	$app->get('/api/donation-requests/list', function (Request $request, Response $response) {
		$collection = $this->mongo_db->ffd->furniture_requests;
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

    $app->get('/auth/logout', function(Request $request, Response $response) {
        $this->hybrid_auth->logoutAllProviders();
        return $response->withHeader('Location', '/');
    });

	$app->run();