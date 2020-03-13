<template>
    <div class="provider-new">
        <location-picker v-model="location" :options="options"> </location-picker>
        <form @submit.prevent="add">
            <table class="table">
                <tr>
                    <th>Name</th>
                    <td><input type="text" class="form-control" v-model="provider.name" placeholder="Provider Name"/></td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>
                        <vue-phone-number-input v-model="provider.phone" />
                    </td>
                </tr>
                <tr>
                    <th>Contact Person</th>
                    <td><input type="text" class="form-control" v-model="provider.contact_person" placeholder="Contact Person"/></td>
                </tr>
                <tr>
                    <th>Location</th>
                    <td>{{location.lat}}, {{location.lng}}</td>
                </tr>
                <tr>
                    <th>Active/Inactive</th>
                    <td>
                        <switches v-model="provider.is_active" theme="bulma"></switches>
                    </td>
                </tr>
                <tr>
                    <td>
                        <router-link to="/providers" class="btn">Cancel</router-link>
                    </td>
                    <td class="text-right">
                        <input type="submit" value="Create Provider" class="btn btn-primary">
                    </td>
                </tr>
            </table>
        </form>
        <div class="errors" v-if="errors">
            <ul>
                <li v-for="(fieldsError, fieldName) in errors" :key="fieldName">
                    <strong>{{ fieldName }}</strong> {{ fieldsError.join('\n')}}
                </li>
            </ul>
        </div>
    </div>
    
</template>

<script>
import validate from 'validate.js';
import Axios from 'axios';
import {LocationPicker} from 'vue2-location-picker';

export default {
    name: 'new',
    components: {LocationPicker},
    data() {
        return {
            provider: {
                name: '',
                phone: '',
                contact_person: '',
                location: {
                    lat: 8.97773836646958,
                    lng: -79.52968559375
                },
                is_active: false
            },
            errors: null,
            location: {
                lat: 8.97773836646958,
                lng: -79.52968559375
            },
            options: { // is not required
                map: {/** other map options **/},
                marker: { /** marker options **/ },
                autocomplete: { /** autocomplete options **/ }
            }
        };
    },
    computed: {
        currentUser() {
            return this.$store.getters.currentUser;
        }
    },
    methods: {
        add() {
            this.errors = null;

            const constraints = this.getConstraints();
            const errors = validate(this.$data.provider, constraints);

            if (errors) {
                this.errors = errors;
                return;
            }
            if (this.$data.provider.is_active) {
                this.$data.provider.is_active = "true";
            } else {
                this.$data.provider.is_active = "false";
            }

            this.$data.provider.location = JSON.stringify(this.$data.location);
            Axios.post('/api/providers/new', this.$data.provider, {
                headers: {
                    "Authorization": `Bearer ${this.$store.getters.currentUser.token}`
                }
            }).then((response) => {      
                this.$router.push('/providers');
            });
        },
        getConstraints() {
            return {
                name: {
                    presence: true,
                    length: {
                        minimum: 3,
                        message: "Must be at least 3 characters long"
                    }
                },
                contact_person: {
                    presence: true,
                    length: {
                        minimum: 3,
                        message: "Must be at least 3 characters long"
                    }
                }
            }
        }
    }
}
</script>

<style>
.providers-view {
    margin-top: 0;
}
#pac-input {
    line-height: 27px;
    font-size: 18px;
}
.map_container {
    height: 500px;
}
.vue-switcher-theme--bulma.vue-switcher-color--default.vue-switcher--unchecked div {
    background-color: #9b9fa0  !important;
}
.vue-switcher-theme--bulma.vue-switcher-color--default div {
    background-color: #187da0 !important;
}
.errors {
    background: lightcoral;
    border-radius: 5px;
    padding: 21px 0 2px 0;
}
</style>
