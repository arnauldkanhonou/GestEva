<template>
    <div class="container-fluid">
    <span class="navbar-nav d-inline-block">
        <img v-bind:src="store.state.MIX_API_URL_IMAGE+'images/imageSCB1.jpg'" class="h-12">
    </span> &nbsp;
        <b style="color: white" class="navbar-brand"> SCB-Lafarge</b>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <b style="color: white"> <span class="fa fa-navicon"></span></b>
        </button>
        <!--collapse navbar-collapse-->
        <div class="navbar-nav ml-auto bg-white">
            <ul class="navbar-nav ms-auto ml-2 mr-2 mt-2 mb-lg-2">
                <li class="nav-item text-red-600">
                    <marquee>
                        <strong>&nbsp;&nbsp;BIENVENU A VOUS CHER <b class="text-black uppercase">{{intitule}}</b>, DANS ASSESMENT MANAGER</strong>
                    </marquee>
                </li>
            </ul>
        </div>
        <div id="navbarSupportedContent" class="navbar-nav ml-auto">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <button type="button" @click="dashboard" class="nav-link active"><b style="color: white;font-size: 17px">
                        <i class="fa fa-home"></i> Dashboard</b></button>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="modal" data-bs-target="#staticBackdropProfil" href="#"> <b style="color: white;font-size: 17px"><i
                            class="fa fa-user"></i> Profil</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#" data-bs-toggle="modal" data-bs-target="#staticBackdropNav">
                        <b style="color: white;font-size: 17px">
                            <i class="fa fa-sign-out-alt"></i>
                            Déconnexion
                        </b>
                    </a>
                </li>

            </ul>
        </div>
    </div>


</template>

<script>
    import {useStore} from "vuex";
    import appService from "../../services/appService";
    import {useRouter} from "vue-router";
    import {ref,onMounted} from "vue";

    export default {
        name: "NavbarApp",
        setup() {
            const store = useStore();
            const {displayDasboard,hasRole} = appService();
            const intitule = ref('');
            const dashboard = ()=>{
                displayDasboard();
            }

            onMounted(()=>{
                let salarie = JSON.parse(store.state.user);
                intitule.value = salarie.name;
            })


            return {
                store,
                intitule,
                dashboard
            }
        }
    }
</script>

<style scoped>

</style>