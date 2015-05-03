var React = require('react');

var RegisterBox = React.createClass({
  render: function() {
    return (
      <div id="register-popup">
        <h3>Register</h3>
        <label for="username">Username</label>
        <input id="username" name="username" />
        <br />
        <label for="email">Email</label>
        <input id="email" name="email" />
        <br />
        <label for="password">Password</label>
        <input id="password" name="password" type="password" />
        <br />
        <button>Register</button>
      </div>
    );
  },
});

module.exports = RegisterBox;
