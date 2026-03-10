<template>
    <div class="">
        <titre-application big-title="CHOIX DU FORMULAIRE D'ENTRETIEN" small-title="entetien" name-page="evaluation"></titre-application>
        <div class="mt-8">
            <div class="row">
                <div class="offset-1 col-md-4 alert alert-info" role="alert">
                   <b> ENTRETIEN A MI-PARCOURS</b> <br>
                    <button @click="gotoEntretienMiparcours" class="btn btn-info mt-4">
                        <b>DEMARRER L'ENTETIEN A MI-PACOURS DE {{intitule}}</b> <br>
                        <i hidden id="loader-miparcours" class="mt-2 fa fa-spinner fa-2x fa-spin"></i>
                    </button>
                </div>

                <div class="offset-1 col-md-4 alert alert-danger" role="alert">
                    <b>ENTRETIEN ANNUEL</b>
                    <br>
                    <button @click="gotoEntretienAnnuel" class="btn btn-danger mt-4">
                        <b>DEMARRER L'ENTRETIEN ANNUEL DE {{intitule}}</b>
                        <br>
                        <i hidden id="loader-annuel" class="mt-2 fa fa-spinner fa-2x fa-spin"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
 import TitreApplication from "../block/TitreApplication.vue";
 import {useRouter} from "vue-router";
 import axios from "axios";
 import appService from "../../services/appService";
 import Swal from "sweetalert2";

 export default {
     components: {TitreApplication},
     props: {
         messages: {
             required: false,
             type: String,
             default: '',
         },
         id: {
             required: true,
             type: String,
         },
         intitule: {
             required: false,
             type: String,
         }
     },

     setup(props, { emit }) {
         const router = useRouter();
         const {dasboardName, catchErrors} = appService();
         const gotoEntretienMiparcours = ()=>{
             $('#loader-miparcours').removeAttr('hidden');
             axios.get('verify/entretien/miparcours/'+props.id)
             .then((response) => {
                 if (response.data.data.code === 200) {
                     let evaluation = response.data.data.evaluation;
                     router.push({
                         name: 'entretien.mis.pacours',
                         params: {id: evaluation.id}
                     });
                 }else {
                     Swal.fire({
                         title: 'Erreur',
                         text: response.data.data.message,
                         icon: 'warning',
                     });
                 }
                  $('#loader-miparcours').attr('hidden','hidden');
             }).catch((error) => {
                  $('#loader-miparcours').attr('hidden','hidden');
                 catchErrors(error, succesMessage);
             })
         }
         const gotoEntretienAnnuel = ()=>{
             $('#loader-annuel').removeAttr('hidden');
             axios.get('verify/entretien/annuel/'+props.id)
             .then((response) => {
                 if (response.data.data.code === 200) {
                     let evaluation = response.data.data.evaluation;
                     router.push({
                         name: 'entretien.evaluations',
                         params: {
                             id: evaluation.id,
                             intitule: props.intitule,
                         }
                     });
                 }else {
                     Swal.fire({
                         title: 'Erreur',
                         text: response.data.data.message,
                         icon: 'warning',
                     });
                 }
                  $('#loader-annuel').attr('hidden','hidden');
             }).catch((error) => {
                  $('#loader-annuel').attr('hidden','hidden');
                 catchErrors(error, succesMessage);
             })
         }

         return {
             gotoEntretienMiparcours,
             gotoEntretienAnnuel,
         }
     }

 }
</script>

<style scoped>

</style>
