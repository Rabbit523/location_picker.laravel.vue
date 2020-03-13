import { getLocalUser } from "../helpers/auth";

// https://vuex.vuejs.org/en/state.html

const user = getLocalUser();

export default {
  currentUser: user,
  isLoggedIn: !!user,
  loading: false,
  auth_error: null,
  providers: []    
}
