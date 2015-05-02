var $ = require('jquery');
var React = require('react');
var RecipeItem = require('./RecipeItem.react');
var RecipeDetails = require('./RecipeDetails.react');

var RecipeList = React.createClass({
  getInitialState: function () {
    return {recipes: [], recipe: null};
  },

  formatRecipes: function (result) {
    return result._embedded.recipes.map(function (recipe) {
      return {
        name: recipe.name,
        user: recipe._embedded.user.username,
        stars: recipe.stars,
        link: recipe._links.self.href
      }
    });
  },

  componentDidMount: function () {
    $.get(this.props.listUrl, function (result) {
      if (this.isMounted()) {
        this.setState({
          recipes: this.formatRecipes(result)
        });
      }
    }.bind(this));
  },

  render: function () {
    var component = this, recipes = this.state.recipes.map(function (recipe) {
      function showRecipe() {
        $.get(recipe.link, function (result) {
          if (component.isMounted()) {
            component.setState({
              recipe: result
            });
          }
        });
      }

      return (
        <RecipeItem recipe={recipe} showRecipe={showRecipe} />
      );
    });

    return (
        <div>
          <table>
            <thead>
              <tr>
                <th>Name</th>
                <th>User</th>
                <th>Rating</th>
              </tr>
            </thead>
            <tbody>{recipes}</tbody>
          </table>
          <RecipeDetails recipe={this.state.recipe} />
        </div>
    );
  }
});

module.exports = RecipeList;
