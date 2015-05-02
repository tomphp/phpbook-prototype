var React = require('react');
var RecipeList = require('./RecipeList.react');
var RecipeDetails = require('./RecipeDetails.react');
var RecipeStore = require('../stores/RecipeStore');

function getRecipeState() {
  return {
    recipes: RecipeStore.getRecipeList(),
    selectedRecipe: RecipeStore.getSelectedRecipe(),
  };
}

var App = React.createClass({
  getInitialState: function() {
    return getRecipeState();
  },

  componentDidMount: function() {
    RecipeStore.addChangeListener(this._onChange);
  },

  componentWillUnMount: function() {
    RecipeStore.removeChangeListener(this._onChange);
  },

  render: function () {
    selectedRecipe = '';
    if (this.state.selectedRecipe) {
        selectedRecipe = <RecipeDetails recipe={this.state.selectedRecipe} />
    }

    return (
      <div>
        <RecipeList recipes={this.state.recipes} />
        {selectedRecipe}
      </div>
    );
  },

  _onChange: function() {
    this.setState(getRecipeState());
  },
});

module.exports = App;
