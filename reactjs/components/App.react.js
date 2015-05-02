var React = require('react');
var RecipeList = require('./RecipeList.react');

var App = React.createClass({
  render: function () {
    return (
      <div>
        <RecipeList listUrl="/api/v1/recipes" />
      </div>
    );
  }
});

module.exports = App;
