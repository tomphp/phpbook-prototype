var $ = require('jquery');
var AppDispatcher = require('../dispatcher/AppDispatcher');
var RecipeConstants = require('../constants/RecipeConstants');

function formatRecipes(result) {
  return result._embedded.recipes.map(function (recipe) {
    return {
      name: recipe.name,
      user: recipe._embedded.user.username,
      stars: recipe.stars,
      link: recipe._links.self.href
    }
  });
}

var RecipeActions = {
  fetchRecipes: function() {
    $.get('/api/v1/recipes', function (result) {
      AppDispatcher.dispatch({
        actionType: RecipeConstants.SET_RECIPE_LIST,
        recipes: formatRecipes(result),
      });
    });
  },

  selectRecipe: function(url) {
    $.get(url, function(recipe) {
      AppDispatcher.dispatch({
        actionType: RecipeConstants.SELECT_RECIPE,
        recipe: recipe,
      });
    });
  },
};

module.exports = RecipeActions;
