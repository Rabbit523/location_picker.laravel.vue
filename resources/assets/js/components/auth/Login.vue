<template>
    <div class="login-view">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card-group">
                        <div class="card p-4">
                            <div class="card-body">
                                <form @submit.prevent="authenticate">
                                    <h1>Login</h1>
                                    <p class="text-muted">Sign In to your account</p>
                                    <div role="group" class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <font-awesome-icon icon="user" />
                                            </div>
                                        </div>
                                        <input type="email" placeholder="Email address" v-model="form.email" autocomplete="username email" class="form-control form-control">
                                    </div>
                                    <div role="group" class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <font-awesome-icon icon="lock" />
                                            </div>
                                        </div>
                                        <input type="password" placeholder="Password" v-model="form.password" autocomplete="current-password" class="form-control form-control">
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="submit" class="btn px-4 btn-primary" value="Login">
                                        </div>
                                        <div class="text-right col-6">
                                            <button type="button" class="btn px-0 btn-link">Forgot password?</button>
                                        </div>
                                    </div>
                                </form>
                                <p class="error" v-if="authError">{{authError}}</p>
                            </div>
                        </div>
                        <div class="card text-white bg-primary py-5 d-md-down-none" style="width: 44%;">
                            <div class="card-body text-center">
                                <div>
                                    <h2>Sign up</h2>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                    <button type="button" class="btn active mt-3 btn-primary">Register Now!</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {login} from '../../helpers/auth';
export default {
    name: "login",
    data() {
        return {
            form: {
                email: "",
                password: ""
            },
            error: null
        }
    },
    methods: {
        authenticate() {
            this.$store.dispatch('login');

            login(this.$data.form)
                .then((res) => {
                    this.$store.commit('loginSuccess', res);
                    this.$router.push({path: '/'});
                })
                .catch((error) => {
                    this.$store.commit('loginFailed', {error});
                });
        }
    },
    computed: {
        authError() {
            return this.$store.getters.authError;
        }
    }
}
</script>

<style scoped>
.login-view {
    margin-top: 100px;
}
.card {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid #c8ced3;
    border-radius: .25rem;
}
.card.bg-primary {
    border-color: #187da0;
    background-color: #20a8d8!important;
}
.btn-primary {
    color: #fff;
    background-color: #20a8d8;
    border-color: #20a8d8;
}
.btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active, .show>.btn-primary.dropdown-toggle {
    color: #fff;
    background-color: #1985ac;
    border-color: #187da0;
}
.text-muted {
    color: #73818f!important;
}
.input-group-append, .input-group-prepend {
    white-space: nowrap;
    vertical-align: middle;
}
.input-group-text svg {
    width: 13px;
}
.error {
    color: red;
    margin-bottom: 0;
}
</style>
