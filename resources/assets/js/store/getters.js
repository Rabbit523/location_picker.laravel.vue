// https://vuex.vuejs.org/en/getters.html

export default {
  isLoading(state) {
    return state.loading;
  },
  isLoggedIn(state) {
    return state.isLoggedIn;
  },
  currentUser(state) {
    return state.currentUser;
  },
  authError(state) {
    return state.auth_error;
  },
  providers(state) {
    return state.providers;
  }
}
