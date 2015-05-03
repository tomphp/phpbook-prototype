var AppDispatcher = require('../dispatcher/AppDispatcher');
var UserConstants = require('../constants/UserConstants');
var EventEmitter = require('events').EventEmitter;
var assign = require('object-assign');

var CHANGE_EVENT = 'change';

var showLogin = false;
var showRegister = false;

var UserStore = assign({}, EventEmitter.prototype, {
  getShowLogin: function() {
    return showLogin;
  },

  getShowRegister: function() {
    return showRegister;
  },

  emitChange: function() {
    this.emit(CHANGE_EVENT);
  },

  addChangeListener: function(callback) {
    this.on(CHANGE_EVENT, callback);
  },

  removeChangeListener: function(callback) {
    this.removeListener(CHANGE_EVENT, callback);
  },
});

AppDispatcher.register(function(action) {
  switch(action.actionType) {
    case UserConstants.TOGGLE_LOGIN:
      showRegister = false;
      showLogin = !showLogin;
      UserStore.emitChange();
      break;

    case UserConstants.TOGGLE_REGISTER:
      showLogin = false;
      showRegister = !showRegister;
      UserStore.emitChange();
      break;

    default:
      // No op
  }
});

module.exports = UserStore;
