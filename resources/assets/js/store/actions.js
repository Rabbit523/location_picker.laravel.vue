import Axios from "axios";

// https://vuex.vuejs.org/en/actions.html

export default {
  login(context) {
    context.commit("login");
  },
  getProviders(context) {
    Axios.get('/api/providers', {
      headers: {
        "Authorization": `Bearer ${context.state.currentUser.token}`
      }
    }).then((response) => {
      context.commit('updateProviders', response.data.providers);
    })
  }  
}
