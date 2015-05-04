jest.dontMock('../../constants/UserConstants');
jest.dontMock('../UserActions');
jest.dontMock('keymirror');

describe('UserActions', function() {
  var UserConstants = require('../../constants/UserConstants');
  var AppDispatcher;
  var UserActions;

  beforeEach(function() {
    AppDispatcher = require('../../dispatcher/AppDispatcher');
    UserActions = require('../UserActions');
  });

  it('dispatches as toggle login event', function() {
    UserActions.toggleLogin();

    expect(AppDispatcher.dispatch).toBeCalledWith({
      actionType: UserConstants.TOGGLE_LOGIN,
    });
  });

  it('dispatches as toggle register event', function() {
    UserActions.toggleRegister();

    expect(AppDispatcher.dispatch).toBeCalledWith({
      actionType: UserConstants.TOGGLE_REGISTER,
    });
  });
});
