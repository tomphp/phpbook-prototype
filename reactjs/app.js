var App = require('./components/App.react');
var React = require('react');
var RecipeActions = require('./actions/RecipeActions');
var UserControls = require('./components/user/UserControls.react');

RecipeActions.fetchRecipes();

React.render(<App />, document.getElementById('content'));
React.render(<UserControls />, document.getElementById('user-controls'));
