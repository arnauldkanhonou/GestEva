<template>
    <div class="content-header">
        <div class="row">
            <div class="col-md-7">
                <h3><b class="uppercase">{{ prop.bigTitle }}</b></h3>
            </div><!-- /.col -->
            <div class="col-md-4" style="padding-top: 5px">
                <ol class="breadcrumb float-right">
                    <li style="color: blue" class="breadcrumb-item uppercase">{{ prop.smallTitle }}</li>
                    <li class="breadcrumb-item active uppercase">{{ prop.namePage }}</li>
                </ol>
            </div><!-- /.col -->
            <div class="col-md-1">
                <img class="rounded-circle" style="width: 45px; height: 45px" :src="store.state.MIX_API_URL_IMAGE+'images/profile.jpg'" alt="photo de profile">
            </div>
        </div>
        <div style="margin-top: -10px">
            <hr>
        </div>
    </div>
</template>

<script>
    import {useStore} from "vuex";
    export default {
        props:{
            bigTitle:{
                required:true,
                type:String,
            },
            smallTitle:{
                required:true,
                type:String,
            },
            namePage:{
                required:true,
                type:String,
            }
        },
        setup(props){
            const store = useStore();
            const prop = props
            return {
                store,
                prop,
            }
        }
    }
</script>

<style scoped>

</style>
