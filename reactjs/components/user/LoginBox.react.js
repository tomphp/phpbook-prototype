var React = require('react');

var LoginBox = React.createClass({
  render: function() {
    return (
      <div id="login-popup">
        <h3>Log into your account</h3>
        <label for="username">Username</label>
        <input id="username" name="username" />
        <br />
        <label for="password">Password</label>
        <input id="password" name="password" type="password" />
        <br />
        <button>Login</button>
      </div>
    );
  },
});

module.exports = LoginBox;
