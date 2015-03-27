<?php

use Slim\Slim;
use CocktailRater\Domain\RecipeList;
use CocktailRater\Domain\User;
use CocktailRater\FileSystemRepository\FileSystemRecipeRepository;

require __DIR__ . '/../vendor/autoload.php';

$app = new Slim();
$app->view()->setTemplatesDirectory(__DIR__ . '/../templates/');

$app->recipeList = new RecipeList(new FileSystemRecipeRepository(__DIR__ . '/../test-fsdb'));

$app->get('/recipes', function () use ($app) {
    $app->render(
        'list-recipes.phtml',
        ['recipes' => $app->recipeList->view()]
    );
});

$app->get('/recipes/:user/:name', function ($user, $name) use ($app) {
    $recipe = $app->recipeList->fetchByNameAndUser($name, new User($user));

    $app->render(
        'view-recipe.phtml',
        ['recipe' => $recipe->view()]
    );
});

$app->run();
