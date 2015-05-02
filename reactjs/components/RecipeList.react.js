var $ = require('jquery');
var React = require('react');
var RecipeItem = require('./RecipeItem.react');
var RecipeDetails = require('./RecipeDetails.react');

var RecipeList = React.createClass({
  render: function () {
    var keyId = 0;

    var recipes = this.props.recipes.map(function(recipe) {
      keyId++;
      return (
        <RecipeItem key={keyId} recipe={recipe} />
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
        </div>
    );
  }
});

module.exports = RecipeList;
