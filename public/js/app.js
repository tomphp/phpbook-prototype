'use strict';

var RecipeItem = React.createClass({
  loadRecipe: function (event) {

    event.preventDefault();
  },

  render: function () {
    return (
      <tr>
        <td><a href="" onClick={this.loadRecipe}>{this.props.recipe.name}</a></td>
        <td>{this.props.recipe.user}</td>
        <td>{this.props.recipe.stars}</td>
      </tr>
    );
  }
});

var RecipeList = React.createClass({
  getInitialState: function () {
    return {recipes: []};
  },

  formatRecipes: function (result) {
    return result._embedded.recipes.map(function (recipe) {
      return {
        name: recipe.name,
        user: recipe._embedded.user.name,
        stars: recipe.stars,
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
    var recipes = this.state.recipes.map(function (recipe) {
      return (
        <RecipeItem recipe={recipe} />
      );
    });

    return (
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
    );
  }
});

var App = React.createClass({
  render: function () {
    return (
      <div>
        <RecipeList listUrl="/api/v1/recipes" />
      </div>
    );
  }
});

React.render(<App />, document.getElementById('content'));
