var AppDispatcher = require('../dispatcher/AppDispatcher');
var UserConstants = require('../constants/UserConstants');

var UserActions = {
  toggleLogin: function() {
    AppDispatcher.dispatch({
      actionType: UserConstants.TOGGLE_LOGIN,
    });
  },

  toggleRegister: function() {
    AppDispatcher.dispatch({
      actionType: UserConstants.TOGGLE_REGISTER,
    });
  },
};

module.exports = UserActions;
