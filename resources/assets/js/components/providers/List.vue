<template>
    <div>
        <div class="btn-wrapper">
            <router-link to='/providers/new' class="btn btn-primary btn-sm">New Provider</router-link>
        </div>
        <table class="table">
            <thead class="table-header">
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Contact Person</th>
                <th>Location</th>
                <th>Active/Inactive</th>
                <th>Action</th>
            </thead>
            <tbody class="table-body">
                <template v-if="!providers.length">
                    <p>No providers available</p>
                </template>
                <template v-else>
                    <tr v-for="provider in providers" :key="provider.id">
                        <td>{{ provider.id }}</td>
                        <td>{{ provider.name }}</td>
                        <td>{{ provider.phone }}</td>
                        <td>{{ provider.contact_person }}</td>
                        <td>{{ JSON.parse(provider.location).lat }}, {{ JSON.parse(provider.location).lng }}</td>
                        <td>
                            <span class="badge badge-success" v-if="provider.is_active == 'true'">Active</span>
                            <span class="badge badge-secondary" v-else>Inactive</span>
                        </td>
                        <td>
                            <router-link :to="`/providers/${provider.id}`"><font-awesome-icon icon="edit" /></router-link>
                            <a v-on:click="remove(provider.id)"><font-awesome-icon icon="trash" /></a>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>
  
<script>
    import Axios from 'axios';
    export default {
        name: 'list',
        mounted() {
            this.$store.dispatch('getProviders');
        },
        methods: {
            remove(id) {
                Axios.post('/api/providers/delete', {id: id}, {
                    headers: {
                        "Authorization": `Bearer ${this.$store.getters.currentUser.token}`
                    }
                }).then((response) => {
                    this.$store.dispatch('getProviders');                    
                });
            }
        },
        computed: {
            providers() {
                return this.$store.getters.providers;
            }
        }
    }
</script>

<style scoped>
    .btn-wrapper {
        text-align: right;
        margin-bottom: 20px;
    }
    .table-header {
        background: linear-gradient(60deg, #66bb6a, #43a047) !important;
        color: #fff;
        box-shadow: 0 12px 20px -10px rgba(76, 175, 80, 0.28), 0 4px 20px 0px rgba(0, 0, 0, 0.12), 0 7px 8px -5px rgba(76, 175, 80, 0.2) !important;
        text-align: center;
    }
    .table-body {
        text-align: center;
    }
    .table-body svg {
        width: 20px;
        color: #3490dc;
        text-decoration: none;
        background-color: transparent;
    }
</style>