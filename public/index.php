<?php

use CocktailRater\Domain\AuthenticationService;
use CocktailRater\Domain\RecipeId;
use CocktailRater\Domain\RecipeList;
use CocktailRater\Domain\User;
use CocktailRater\Domain\Username;
use CocktailRater\FileSystemRepository\FileSystemRecipeRepository;
use Slim\Slim;
use Slim\Views\Twig;
use CocktailRater\Domain\ProspectiveUser;

require __DIR__ . '/../vendor/autoload.php';

if (file_exists($_SERVER["DOCUMENT_ROOT"] . $_SERVER["REQUEST_URI"])) {
    return false;
}

session_start();

$app = new Slim([
    'view'           => new Twig(),
    'templates.path' => __DIR__ . '/../templates/'
]);

$app->recipeList = new RecipeList(new FileSystemRecipeRepository(__DIR__ . '/../test-fsdb'));
$app->authService = new AuthenticationService();

$app->hook('slim.before', function () use($app) {
    // @todo really use SESSION?
    if (isset($_SESSION['slim.flash']['message'])) {
        $app->view->setData(['message' => $_SESSION['slim.flash']['message']]);
    }
});

$app->get('/recipes', function () use ($app) {
    $app->render(
        'list-recipes.html',
        ['recipes' => $app->recipeList->view()]
    );
});

$app->get('/recipes/:user/:name', function ($user, $name) use ($app) {
    $recipe = $app->recipeList->fetchByNameAndUser($name, new User(new Username($user)));

    $app->render(
        'view-recipe.html',
        ['recipe' => $recipe->view()]
    );
});

$app->get('/register', function () use ($app) {
    $app->render('register.html');
});

$app->post('/register', function () use ($app) {
    try {
        $user = ProspectiveUser::fromValues(
            $app->request->post('username'),
            $app->request->post('email'),
            $app->request->post('password')
        );
    } catch (UsernameTakenException $e) {
        $app->flash('message', 'This username has already been taken');
        $app->redirect('/register');
    }

    $app->redirect('/recipes');
});

$app->get('/login', function () use ($app) {
    $app->render('login.html');
});

$app->post('/login', function () use ($app) {
    $app->flash('message', 'Login Successful');
    $app->redirect('/recipes');
});


// START API
$app->get('/api/v1', function () use ($app) {
    $app->response->headers->set('content-type', 'application/hal+json');

    $url = $app->request->getUrl() . $app->request->getPath();

    echo json_encode([
        '_links' => [
            'self' => ['href' => $url],
            'recipes' => ['href' => $url . '/recipes']
        ],
    ]);
});

$app->get('/api/v1/recipes', function () use ($app) {
    $app->response->headers->set('content-type', 'application/hal+json');

    $url = $app->request->getUrl() . $app->request->getPath();

    $recipes = array_map(function (array $recipe) use ($url) {
        return [
            '_links' => [
                'self' => ['href' => $url . '/' . $recipe['id']]
            ],
            'name'      => $recipe['name'],
            'stars'     => $recipe['stars'],
            '_embedded' => [
                'user' => [
                    'name' => $recipe['user']['name']
                ]
            ]
        ];
    }, $app->recipeList->view());

    echo json_encode([
        '_links' => [
            'self' => ['href' => $app->request->getUrl() . $app->request->getPath()]
        ],
        'count' => 0,
        '_embedded' => [
            'recipes' => $recipes
        ],
    ]);
});

$app->get('/api/v1/recipes/:id', function ($id) use ($app) {
    $app->response->headers->set('content-type', 'application/hal+json');

    $url = $app->request->getUrl() . $app->request->getPath();

    $recipe = $app->recipeList->fetchById(new RecipeId($id))->view();

    echo json_encode([
        '_links' => [
            'self' => ['href' => $url]
        ],
        'name'      => $recipe['name'],
        'stars'     => $recipe['stars'],
        'method'    => $recipe['method'],
        '_embedded' => [
            'user' => [
                'name' => $recipe['user']['name']
            ]
        ]
    ]);
});
// END API

$app->get('/react', function () use ($app) {
    $app->render('react.html');
});

$app->run();
