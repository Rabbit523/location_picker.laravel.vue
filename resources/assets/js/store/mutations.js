// https://vuex.vuejs.org/en/mutations.html

export default {
  login(state) {
    state.loading = true;
    state.auth_error = null;
  },
  loginSuccess(state, payload) {
    state.auth_error = null;
    state.isLoggedIn = true;
    state.loading = false;
    state.currentUser = Object.assign({}, payload.user, {token: payload.access_token});
    localStorage.setItem("user", JSON.stringify(state.currentUser));
  },
  loginFailed(state, payload) {
    state.loading = false;
    state.auth_error = payload.error;
  },
  logout(state) {
    localStorage.removeItem("user");
    state.isLoggedIn = false;
    state.currentUser = null;
  },
  updateProviders(state, payload) {
    state.providers = payload;
  }  
}
