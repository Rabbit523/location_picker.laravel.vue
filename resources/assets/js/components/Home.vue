<template>
    <div class="home-view">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-default">
                        <div class="card-header">
                            Laravel VueJS Google Location Provider
                        </div>
                        <div class="card-body">
                            <img src="https://www.jqueryscript.net/images/Location-Picker-Place-Autocomplete-Plugin-For-Google-Maps-Location-Picker.jpg"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'home',
        computed: {
            welcome() {
                return this.$store.getters.welcome
            }
        }
    }
</script>
<style>
    .home-view {
        margin-top: 100px;
    }
    .card-header {
        background: #1985ac;
        color: #fff;
    }
    .card-body img {
        width: 100%;
    }
</style>