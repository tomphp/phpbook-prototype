var AppDispatcher = require('../dispatcher/AppDispatcher');
var RecipeConstants = require('../constants/RecipeConstants');
var EventEmitter = require('events').EventEmitter;
var assign = require('object-assign');

var CHANGE_EVENT = 'change';

var recipeList = [];
var selectedRecipe = null;

var RecipeStore = assign({}, EventEmitter.prototype, {
  getRecipeList: function() {
    return recipeList;
  },

  getSelectedRecipe: function() {
    return selectedRecipe;
  },

  emitChange: function() {
    this.emit(CHANGE_EVENT);
  },

  addChangeListener: function(callback) {
    this.on(CHANGE_EVENT, callback);
  },

  removeChangeListener: function (callback) {
    this.removeListener(CHANGE_EVENT, callback);
  },
});

AppDispatcher.register(function(action) {
  switch(action.actionType) {
    case RecipeConstants.SET_RECIPE_LIST:
      recipeList = action.recipes;
      RecipeStore.emitChange();
      break;

    case RecipeConstants.SELECT_RECIPE:
      selectedRecipe = action.recipe;
      RecipeStore.emitChange();
      break;

    default:
      // no op
  }
  console.log(this);
});

module.exports = RecipeStore;
