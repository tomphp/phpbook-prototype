var React = require('react');
var RecipeActions = require('../actions/RecipeActions');

var RecipeItem = React.createClass({
  loadRecipe: function (event) {
    RecipeActions.selectRecipe(this.props.recipe.link);

    event.preventDefault();
  },

  render: function () {
    return (
      <tr>
        <td><a href="#" onClick={this.loadRecipe}>{this.props.recipe.name}</a></td>
        <td>{this.props.recipe.user}</td>
        <td>{this.props.recipe.stars}</td>
      </tr>
    );
  }
});

module.exports = RecipeItem;
