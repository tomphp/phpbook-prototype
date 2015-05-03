jest.dontMock('../RecipeStore');
jest.dontMock('../../constants/RecipeConstants');
jest.dontMock('object-assign');
jest.dontMock('keymirror');

describe('RecipeStore', function() {
  var RecipeConstants = require('../../constants/RecipeConstants');
  var AppDispatcher;
  var callback;
  var RecipeStore;
  
  beforeEach(function() {
    AppDispatcher = require('../../dispatcher/AppDispatcher');
    RecipeStore = require('../RecipeStore');
    callback = AppDispatcher.register.mock.calls[0][0];
  });

  it('registers a callback with the dispatcher', function() {
    expect(AppDispatcher.register.mock.calls.length).toBe(1);
  });

  it('initialises with and empty list of recipes', function() {
    expect(RecipeStore.getRecipeList()).toEqual([]);
  });

  it('sets the list of recipes', function() {
    var recipes = ['recipe1', 'recipe2'];

    callback({
      actionType: RecipeConstants.SET_RECIPE_LIST,
      recipes: recipes,
    });

    expect(RecipeStore.getRecipeList()).toEqual(recipes);
  });

  it('initialises selected recipe to none', function() {
    expect(RecipeStore.getSelectedRecipe()).toBe(null);
  });

  it('sets the selected recipe', function() {
    var recipe = {name: 'the recipe'};

    callback({
      actionType: RecipeConstants.SELECT_RECIPE,
      recipe: recipe,
    });

    expect(RecipeStore.getSelectedRecipe()).toEqual(recipe);
  });
});
