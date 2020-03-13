<template>
    <div class="providers-view">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Providers</div>
    
                        <div class="card-body">
                            <router-view></router-view>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script>
    export default {
        name: 'providers-main'
    }
</script>

<style>
.providers-view {
    margin-top: 100px;
}
</style>
