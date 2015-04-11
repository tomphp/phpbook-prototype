'use strict';

var RecipeItem = React.createClass({
  loadRecipe: function (event) {
    this.props.showRecipe();

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

var RecipeDetails = React.createClass({
  render: function () {
    if (this.props.recipe == null) {
      return <div></div>;
    }

    return (
        <div>
          <div>{this.props.recipe.name}</div>
          <div>{this.props.recipe.stars} stars</div>
          <div>{this.props.recipe.method}</div>
        </div>
    );
  }
});

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
