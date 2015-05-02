var App = require('./components/App.react');
var React = require('react');
var RecipeActions = require('./actions/RecipeActions');

RecipeActions.fetchRecipes();

React.render(<App />, document.getElementById('content'));
