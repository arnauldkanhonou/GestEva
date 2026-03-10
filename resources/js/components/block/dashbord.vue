<template>
    <div>
        <nav class="fixed-top navbar navbar-expand-sm navbar-default" style="height: 55px">
            <navbar-app/>
        </nav>

        <div id="main" class="mt-8 card-body">
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
    <!-- Modal -->
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
         id="staticBackdropNav" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropNav" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                <div style="background-color: #acb3c4"
                     class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                    <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="exampleModalNav">
                        <b><i class="fa fa-question-circle"></i> CONFIRMATION</b>
                    </h5>
                    <!--<button type="button" class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                            data-bs-dismiss="modal" aria-label="Close"></button>-->
                </div>
                <div class="modal-body">
                    <b><h5>Cette action va vous déconnecter du système </h5></b>
                    <b class="text-red-600"><h5>Voulez vraiment vous déconnecter ?</h5></b>
                </div>
                <div class="modal-footer flex items-center justify-end  border-t border-gray-200">
                    <button id="btn-ferme-modal-nav"
                            class="bg-gray-600 hover:bg-gray-600 text-white font-bold py-2 px-4"
                            data-bs-dismiss="modal">NON
                    </button>

                    <!--<router-link :to="{name:'login'}" class="nav-link active"><b style="color: white;font-size: 17px"> <i class="fa fa-home"></i> Déconnexion</b></router-link>-->

                    <button id="btn-confirm-logout" @click="logout"
                            class="bg-red-600 hover:bg-red-600 text-white font-bold py-2 px-4">
                        OUI <i class="fa fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Profil -->
     <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
         id="staticBackdropProfil" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropNav" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                <div style="background-color: #acb3c4"
                     class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                    <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="staticBackdrol">
                        <b class="uppercase"><i class="fa fa-user"></i> Votre Profil</b>
                    </h5>
                    <button type="button" class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="row mb-4">
                        <div style="border: 1px solid #058069;" class="col-md-4">
                            <img class="container mt-3" style="height: 200px;width: 260px"
                                 v-bind:src="store.state.MIX_API_URL_IMAGE+form.avatar"
                                 alt="Photo candidat lanbda">
                        </div>
                        <div v-if="salarie" class="col-md-8">
                            <ul style="font-size: 18px">
                                <li>
                                    <strong class="uppercase text-red-600" style="text-align: justify;font-family: 'Helvetica Neue', 'sans-serif';">Nom:</strong> <b class="uppercase">{{salarie.nom}}</b>
                                    <br><br>
                                </li>
                                <li>
                                    <strong class="uppercase text-red-600" style="text-align: justify;font-family: 'Helvetica Neue', 'sans-serif';">Prénoms:</strong> <b class="uppercase">{{salarie.prenoms}}</b>
                                    <br><br>
                                </li>
                                <li>
                                    <strong class="uppercase text-red-600" style="text-align: justify;font-family: 'Helvetica Neue', 'sans-serif';">E-mail:</strong> <b>{{salarie.email}}</b>
                                    <br><br>
                                </li>
                                <li>
                                    <strong class="uppercase text-red-600" style="text-align: justify;font-family: 'Helvetica Neue', 'sans-serif';">Unité:</strong> <b class="uppercase">{{unite}}</b>
                                    <br><br>
                                </li>
                                <li>
                                    <strong class="uppercase text-red-600" style="text-align: justify;font-family: 'Helvetica Neue', 'sans-serif';">Date embauche:</strong> <b>{{getDate(salarie.dateEmbauche)}}</b>
                                    <br><br>
                                </li>
                            </ul>
                        </div>
                        <div v-else class="col-md-8">
                            <br><br><br>
                            <p class="text-red-600" style="text-align: justify;font-family: 'Helvetica Neue', 'sans-serif';font-size: 18px">
                                Vous n'exister pas dans la base en tant que salarié. Contatez-IT
                            </p>
                        </div>

                    </div>

                </div>
                <div class="modal-footer flex items-center justify-end  border-t border-gray-200">
                    <button class="bg-gray-600 hover:bg-gray-600 text-white font-bold py-2 px-4"
                            data-bs-dismiss="modal">NON
                    </button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import NavbarApp from "./NavbarApp";
    import MenuApp from "./MenuApp";
    import {useRouter} from "vue-router";
    import axios from 'axios';
    import {useStore} from "vuex";
    import {onMounted, reactive, ref} from "vue";
    import appService from "../../services/appService";

    export default {
        name: "dashbord",
        components: {NavbarApp, MenuApp},
        setup() {
            const router = useRouter();
            const store = useStore();
            const salarie = ref({});
            const unite = ref({});
            const sleep = (ms) => {
                return new Promise(resolve => setTimeout(resolve, ms));
            }
            const form = reactive({
                avatar: 'images/avatar1.png'
            });
            const {getDate} = appService();
            const logout = () => {
                $('#btn-confirm-logout').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
                axios.get('api/logout').then(() => {
                    $('#btn-confirm').html('OUI');
                    $('#btn-ferme-modal-nav')[0].click();
                    router.push({name: 'login'});
                })

            }

            onMounted(()=>{
                salarie.value = JSON.parse(store.state.salarie);
                if (salarie.value.unite !='' && salarie.value.unite!=null){
                    unite.value = salarie.value.unite.libelle;
                }else {
                    unite.value = 'R.A.S'
                }

            })

            return {
                logout,
                getDate,
                store,
                form,
                salarie,
                unite
            }
        }
    }
</script>

<style scoped>

</style>
