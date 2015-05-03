var React = require('react');

var UserControls = React.createClass({
  getInitialState: function() {
    return {
      showLogin: false,
    };
  },

  render: function() {
    var loginBox = '';
    if (this.state.showLogin) {
      loginBox = (
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
    }

    var toggleLogin = function() {
      this.setState({
        showLogin: !this.state.showLogin,
      });
    }.bind(this);

    return (
      <div>
        <a href="#" onClick={toggleLogin}>Login</a>
        {loginBox}
      </div>
    );
  }
});

module.exports = UserControls;
