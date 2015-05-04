var React = require('react');
var LoginBox = require('./LoginBox.react');
var RegisterBox = require('./RegisterBox.react');
var UserStore = require('../../stores/UserStore');
var UserActions = require('../../actions/UserActions');

function getUserState() {
  return {
    showLogin: UserStore.getShowLogin(),
    showRegister: UserStore.getShowRegister(),
  };
}

var UserControls = React.createClass({
  getInitialState: function() {
    return getUserState();
  },

  componentDidMount: function() {
    UserStore.addChangeListener(this._onChange);
  },

  componentWillUnMount: function() {
    UserStore.removeChangeListener(this._onChange);
  },

  render: function() {
    var loginBox = '';
    var registerBox = '';

    if (this.state.showLogin) {
      loginBox = <LoginBox />;
    }

    if (this.state.showRegister) {
      registerBox = <RegisterBox />;
    }

    function toggleLogin() {
      UserActions.toggleLogin();
    }

    return (
      <div>
        <a href="#" onClick={toggleLogin}>Login</a> |&nbsp;
        <a href="#" onClick={UserActions.toggleRegister}>Register</a>
        {loginBox}
        {registerBox}
      </div>
    );
  },

  _onChange: function() {
    this.setState(getUserState());
  },
});

module.exports = UserControls;
