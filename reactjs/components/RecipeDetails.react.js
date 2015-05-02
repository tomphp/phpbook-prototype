var React = require('react');

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

module.exports = RecipeDetails;
