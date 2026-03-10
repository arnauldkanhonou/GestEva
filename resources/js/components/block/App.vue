<template>
   <div id="app">
       <!--<div v-if="authenticat">
           <nav class="fixed-top navbar navbar-expand-sm navbar-default" style="height: 55px">
               <navbar-app/>
           </nav>

           <div  id="main" class="mt-8 card-body">
               <div class="content-wrapper">
                   <div class="row">
                       <div class="col-md-2 pb-64" style="background-color: #dbdde3;margin-left: -3px">
                           <br>
                           <menu-app/>
                       </div>
                       <div class="col-md-10 mt-4">
                           <div class="p-2 card" style="padding-top: 15px">
                               <router-view></router-view>
                               <br><br><br>
                           </div>
                       </div>
                   </div>

               </div>
           </div>

       </div>-->
       <router-view></router-view>
   </div>

</template>

<script>
    import MenuApp from "./MenuApp";
    import NavbarApp from "./NavbarApp";
    import {onMounted,ref} from "vue";
    import store from "../../store";
    import Login from "../Login/login";
    export default {
        name: "App",
        components: {Login, NavbarApp, MenuApp},
        setup(){
            let authenticat = ref(false);
            onMounted(()=>{
                authenticat.value = store.getters.getAuthenticate;
                //console.log('i see you',authenticat.value)
            })
            return {authenticat}
        }
    }
</script>

<style scoped>

</style>