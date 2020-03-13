<template>
    <div class="provider-view" v-if="provider">
        <div class="location-section">
            <gmap-autocomplete @place_changed="updatePlace" class="gmap-auto"></gmap-autocomplete>
            <gmap-map ref="map" :center="location" :zoom="12" style="width:100%;  height: 400px;">
                <gmap-marker 
                    :key="index" 
                    v-for="(m, index) in f_markers" 
                    :position="m.position"
                    :draggable="true" 
                    @dragend="updateMarker" 
                    @dragstart="selected(m)">
                </gmap-marker>
            </gmap-map>
        </div>
        <div class="user-info">
            <div class="btn-group">
                <button class="btn btn-primary" v-on:click="cancel()" v-if="is_edit">Cancel</button>
                <button class="btn btn-primary" v-on:click="editProvider()" v-if="!is_edit">Edit</button>
                <button class="btn btn-primary" v-on:click="updateProvider()">Save</button>
            </div>
            <table class="table" v-if="is_edit">
                <tr>
                    <th>ID</th>
                    <td>{{ provider.id }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td><input type="text" class="form-control" v-model="provider.name" placeholder="Provider Name"/></td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td><input type="text" class="form-control" v-model="provider.phone" placeholder="Contact Person"/></td>
                </tr>
                <tr>
                    <th>Contact Person</th>
                    <td><input type="text" class="form-control" v-model="provider.contact_person" placeholder="Contact Person"/></td>
                </tr>
                <tr>
                    <th>Location</th>
                    <td>{{ location.lat }}, {{ location.lng }}</td>
                </tr>
                <tr>
                    <th>IsActive</th>
                    <switches v-model="provider.is_active" theme="bulma"></switches>
                </tr>
            </table>
            <table class="table" v-else>
                <tr>
                    <th>ID</th>
                    <td>{{ provider.id }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ provider.name }}</td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>{{ provider.phone }}</td>
                </tr>
                <tr>
                    <th>Contact Person</th>
                    <td>{{ provider.contact_person }}</td>
                </tr>
                <tr>
                    <th>Location</th>
                    <td>{{ JSON.parse(provider.location).lat }}, {{ JSON.parse(provider.location).lng }}</td>
                </tr>
                <tr>
                    <th>IsActive</th>
                    <td>
                        <span class="badge badge-success" v-if="provider.is_active == 'true'">Active</span>
                        <span class="badge badge-secondary" v-else>Inactive</span>
                    </td>
                </tr>
            </table>
            <div class="table" v-if="is_edit">
                <h3>Delivery Zones</h3>
                <table class="table">
                    <thead class="table-header">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Rate</th>
                        <th>Time</th>
                        <th>Action</th>
                    </thead>
                    <tbody class="table-body">
                        <template v-if="!zones.length">
                            <p>No zones available</p>
                        </template>
                        <template v-else>
                            <tr v-for="zone in zones" :key="zone.id">
                                <th>{{ zone.id }}</th>
                                <td>{{ zone.name }}</td>
                                <th>{{ zone.rate }}</th>
                                <td>{{ zone.time }}</td>
                                <td>
                                    <a v-on:click="editZone(zone)" data-toggle="modal" data-target="#add_zone"><font-awesome-icon icon="edit"/></a>
                                    <a v-on:click="deleteZone(zone.id)"><font-awesome-icon icon="trash"/></a>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <button v-on:click="addZone()" class="btn btn-primary" data-toggle="modal" data-target="#add_zone">Add Zone</button>
            </div>
            <router-link to="/providers" v-if="!is_edit">Back to providers</router-link>
        </div>
        <div class="modal fade" id="add_zone" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 v-if="is_edit_zone">EDIT ZONE</h4>
                        <h4 v-else>ADD NEW ZONE</h4>
                        <button class="close" type="button" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="zone_general_info">
                            <div class="row">
                                <div class="col-md-4 col-xs-4">
                                    <div class="form-group">
                                        <label>Name:</label>
                                        <input type="text" class="form-control" v-model="current_zone.name" placeholder="Zone Name" v-if="is_edit_zone"/>
                                        <input type="text" class="form-control" v-model="new_zone.name" placeholder="Zone Name" v-else/>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-4">
                                    <div class="form-group">
                                        <label>Rate:</label>
                                        <input type="text" class="form-control" v-model="current_zone.rate" placeholder="Rate" v-if="is_edit_zone"/>
                                        <input type="text" class="form-control" v-model="new_zone.rate" placeholder="Rate" v-else/>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-4">
                                    <div class="form-group">
                                        <label>Time:</label>
                                        <input type="text" class="form-control" v-model="current_zone.time" placeholder="Delivery Time" v-if="is_edit_zone"/>
                                        <input type="text" class="form-control" v-model="new_zone.time" placeholder="Delivery Time" v-else/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <gmap-map ref="map" :center="location" :zoom="12" style="width:100%;  height: 400px;" @click="clickEvent">
                            <gmap-polygon 
                                :paths="polygon.path"
                                v-bind:options="{ strokeColor:'#000'}">
                             </gmap-polygon>
                            <gmap-marker 
                                :key="index" 
                                v-for="(m, index) in markers" 
                                :position="m.position" 
                                :draggable="true" 
                                @dragend="getMarkerPosition" 
                                @dragstart="selected(m)">
                            </gmap-marker>
                        </gmap-map>
                    </div>
                    <div class="modal-footer">
                        <p class="alert-polygon-validation">{{ polygon_validation }}</p>
                        <button type="button" class="btn btn-primary" v-on:click="remakePolygon()" v-if="is_edit_area">Edit Area</button>
                        <button type="button" class="btn btn-primary" v-on:click="makePolygon()" v-if="is_confirm_area">Confirm Area</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" v-on:click="updateZone()" v-if="is_edit_zone">Update</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" v-on:click="createZone()" v-else>Create</button>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Axios from 'axios';
    import {LocationPicker} from 'vue2-location-picker';

    export default {
        name: 'view',
        components: {LocationPicker},
        created() {
            if (this.providers.length) {
                this.provider = this.providers.find((provider) => provider.id == this.$route.params.id);
                this.location = JSON.parse(this.provider.location);
                this.options.center = this.$data.location;
            } else {
                Axios.get(`/api/providers/${this.$route.params.id}`, {
                    headers: {
                        "Authorization": `Bearer ${this.$store.getters.currentUser.token}`
                    }
                }).then((response) => {
                    this.$data.provider = response.data.provider;
                    this.$data.location = JSON.parse(response.data.provider.location);
                    this.$data.options.center = this.$data.location;
                    if (this.$data.provider.is_active == "true") {
                        this.$data.provider.is_active = true;
                    } else {
                        this.$data.provider.is_active = false;
                    }
                })
            }
            Axios.get(`/api/zones/all/${this.$route.params.id}`, {
                headers: {
                    "Authorization": `Bearer ${this.$store.getters.currentUser.token}`
                }
            }).then((response) => {
                this.zones = response.data.zones;
            });
        },
        data() {
            return {
                provider: null,
                zones: [],
                new_zone: {
                    provider_id: "",
                    rate: "",
                    time: "",
                    path: []
                },
                current_zone: null,
                location: {
                    lat: "",
                    lng: ""
                },
                is_edit: false,
                is_edit_area: false,
                is_confirm_area: true,
                is_edit_zone: false,
                is_active: false,
                options: { // is not required                    
                    map: {/** other map options **/
                        zoom: 10,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    },
                    marker: { /** marker options **/ },
                    autocomplete: { /** autocomplete options **/ }
                },
                markers: [],
                f_markers: [],
                temp_markers: [],
                locations: [],
                selected_marker: {},
                polygon:{
                    path:[]
                },
                polygon_validation: ""
            };
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            },
            providers() {
                return this.$store.getters.providers;
            }
        },
        methods: {
            editProvider() {
                this.$data.is_edit = true;
            },
            cancel() {
                this.$data.is_edit = false;
            },
            updateProvider() {
                if (this.$data.provider.is_active) {
                    this.$data.provider.is_active = "true";
                } else {
                    this.$data.provider.is_active = "false";
                }
                Axios.post('/api/providers/update', this.$data.provider, {
                    headers: {
                        "Authorization": `Bearer ${this.$store.getters.currentUser.token}`
                    }
                }).then((response) => {
                    this.$router.push('/providers');
                });
            },
            // get click event on the map
            clickEvent(location) {
                this.$data.is_confirm_area = true;
                this.$data.locations.push ( {lat: location.latLng.lat(), lng: location.latLng.lng()} );
                const marker = {
                    lat: location.latLng.lat(),
                    lng: location.latLng.lng()
                };
                this.$data.markers.push({ position: marker });
            },
            // update marker's position
            getMarkerPosition(location) {
                this.$data.is_confirm_area = true;
                const marker = {
                    lat: location.latLng.lat(),
                    lng: location.latLng.lng()
                };
                this.$data.markers.forEach(element => {
                    if (element.position.lat === this.$data.selected_marker.position.lat && element.position.lng === this.$data.selected_marker.position.lng) {
                        element.position = marker;
                    }
                });
            },
            // get drag started marker's position for comparison
            selected(marker) {
                this.$data.selected_marker = marker;
            },
            // make the polygon 
            makePolygon() {
                var first_position = null;
                this.$data.polygon.path = [];
                var that = this;
                if (this.$data.markers.length > 0) {
                    this.$data.polygon_validation = "";
                    this.$data.markers.map(function (value, key) {
                        if (key == 0) {
                            first_position = value.position;
                        }
                        that.$data.polygon.path.push(value.position);
                    });
                    this.$data.polygon.path.push(first_position);
                    this.$data.temp_markers = this.$data.markers;
                    this.$data.markers = [];
                    this.$data.is_edit_area = true;
                    this.$data.is_confirm_area = false;
                } else {
                    this.$data.polygon_validation = "Can't draw polygon. check again please.";
                }
            },
            // edit the confirmed polygon
            remakePolygon() {
                this.$data.markers = this.$data.temp_markers;
                this.$data.is_confirm_area = false;
            },
            // create zone
            createZone() {
                if (this.$data.polygon.path.length > 0) {
                    this.$data.new_zone.path = JSON.stringify(this.$data.polygon.path);
                    this.$data.new_zone.provider_id = this.$data.provider.id;
                    Axios.post('/api/zones/new', this.$data.new_zone, {
                        headers: {
                            "Authorization": `Bearer ${this.$store.getters.currentUser.token}`
                        }
                    }).then((response) => {
                        this.zones = response.data.zones;
                    });
                } else {
                    this.$data.polygon_validation = "Can't draw polygon. check again please.";
                }
            },
            // edit zone
            editZone(zone) {
                this.$data.current_zone = zone;
                this.$data.polygon.path = JSON.parse(zone.path);
                this.$data.is_edit_zone = true;
                this.$data.is_confirm_area = false;
                this.$data.is_edit_area = true;
                var paths = JSON.parse(zone.path);
                this.$data.markers = [];
                this.$data.temp_markers = [];
                paths.forEach(sel=> {
                    const marker = {
                        lat: sel.lat,
                        lng: sel.lng
                    };
                    this.$data.temp_markers.push({ position: marker });
                });
            },
            // update zone
            updateZone() {
                if (this.$data.polygon.path.length > 0) {
                    this.$data.current_zone.path = JSON.stringify(this.$data.polygon.path);
                    Axios.post('/api/zones/update', this.$data.current_zone, {
                        headers: {
                            "Authorization": `Bearer ${this.$store.getters.currentUser.token}`
                        }
                    }).then((response) => {
                        this.zones = response.data.zones;
                    });
                } else {
                    this.$data.polygon_validation = "Can't draw polygon. check again please.";
                }
            },
            // delete zone
            deleteZone(id) {
                Axios.post('/api/zones/delete', {id: id}, {
                    headers: {
                        "Authorization": `Bearer ${this.$store.getters.currentUser.token}`
                    }
                }).then((response) => {
                    this.zones = response.data.zones;
                });            
            },
            // add zone
            addZone() {
                this.$data.is_edit_zone = false;
                this.$data.polygon.path = [];
                this.$data.markers = [];
                this.$data.temp_markers = [];
                this.$data.new_zone = {};
            },
            // get place of location picker
            updatePlace(place) {
                this.$data.location.lat = place.geometry.location.lat();
                this.$data.location.lng = place.geometry.location.lng();
                this.$data.f_markers = [];
                this.$data.f_markers.push({position: this.$data.location});
                var data = {
                    location: this.$data.location,
                    provider_id: this.$data.provider.id
                };
                Axios.post("/api/zones/polygonValidate", data, {
                    headers: {
                        "Authorization": `Bearer ${this.$store.getters.currentUser.token}`
                    }
                }).then((response) => {
                    console.log(response);
                });
            },
            // get update place of picker
            updateMarker(location) {
                this.$data.location.lat = location.latLng.lat();
                this.$data.location.lng = location.latLng.lng();
                this.$data.f_markers = [];
                this.$data.f_markers.push({position: this.$data.location});
                var data = {
                    location: this.$data.location,
                    provider_id: this.$data.provider.id
                };
                Axios.post("/api/zones/polygonValidate", data, {
                    headers: {
                        "Authorization": `Bearer ${this.$store.getters.currentUser.token}`
                    }
                }).then((response) => {
                    console.log(response);
                });
            }
        }
    }
</script>

<style scoped>
    .provider-view {
        display: flex;
        align-items: center;
    }
    .vue-switcher {
       margin: 17px; 
    }
    .btn-group {
        margin-bottom: 20px;
    }
    .btn-group button {
        margin-right: 20px;
        padding: 10px 30px 10px 30px;
    }
    .location-section {
        flex: 1;
        padding-right: 50px;
    }
    .modal-dialog {
        max-width: 700px;
    }
    .modal-content {
        padding: 20px;
    }
    .alert-polygon-validation {
        color: red;
        position: absolute;
        left: 40px;
    }
    svg {
        width: 20px;
        vertical-align: top;
    }
    .gmap-auto {
        position: absolute;
        top: 78px;
        left: 210px;
        line-height: 28px;
        padding: 5px;
        width: 250px;
        z-index: 1;
    }
</style>