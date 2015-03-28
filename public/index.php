<?php

use CocktailRater\Domain\RecipeList;
use CocktailRater\Domain\User;
use CocktailRater\Domain\Username;
use CocktailRater\FileSystemRepository\FileSystemRecipeRepository;
use Slim\Slim;
use Slim\Views\Twig;

require __DIR__ . '/../vendor/autoload.php';

$app = new Slim([
    'view'           => new Twig(),
    'templates.path' => __DIR__ . '/../templates/'
]);

$app->recipeList = new RecipeList(new FileSystemRecipeRepository(__DIR__ . '/../test-fsdb'));

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

$app->get('/login', function () use ($app) {
    $app->render('login.html');
});

$app->post('/login', function () use ($app) {
    echo "Login Successful";
});

$app->run();
