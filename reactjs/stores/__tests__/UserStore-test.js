jest.dontMock('../UserStore');
jest.dontMock('object-assign');
jest.dontMock('keymirror');
jest.dontMock('../../constants/UserConstants');

describe('UserStore', function() {
  var UserConstants = require('../../constants/UserConstants');
  var AppDispatcher;
  var UserStore;

  beforeEach(function() {
    AppDispatcher = require('../../dispatcher/AppDispatcher');
    UserStore = require('../UserStore');
    callback = AppDispatcher.register.mock.calls[0][0];
  });

  it('registers a callback with the dispatcher', function() {
    expect(AppDispatcher.register.mock.calls.length).toBe(1);
  });
});
