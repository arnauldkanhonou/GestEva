<template>
    <titre-application big-title="CLOTURE EVALUATION" small-title="Cloture"
                       name-page="Evaluation"></titre-application>
    <div class="container">
        <div v-if="isload">
            <div v-if="!load && !iscloture">
                <p class="text-center" style="font-size: 20px">
                    <b>
                        Cher membre du CODI, Cliqué sur le bouton ci-dessous pour clôturer toutes les évaluations.
                    </b>
                </p>

                <div class="row">
                    <div class="mt-5 offset-3 col-md-8">
                        <button @click="cloturer" type="button" class="btn-green text-decoration-none px-32  py-2 text-white"><b>CLÔTURER TOUTES LES EVALUATIONS</b></button>
                    </div>
                </div>

            </div>
            <div v-else>
                <p class="text-center alert alert-success mt-5" style="font-size: 20px">
                    <b>
                        Cher membre du CODI, Toutes les évaluations ont été validée et cloturée.
                    </b>
                </p>
            </div>
        </div>
        <div v-else class="col-md-2 offset-5 mt-4">
            <br>
            <br>
            <strong style="font-size: 20px;color: #057e72;" class="mb-0">Chargement...</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
            <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
        </div>

    </div>
</template>

<script>
    import TitreApplication from "../block/TitreApplication";
    import appService from "../../services/appService";
    import axios from "axios";
    import {onMounted,ref} from "vue";
    import Swal from "sweetalert2";
    export default {
        name: "cloture",
        components: {TitreApplication},
        setup() {
            const {catchErrors}= appService();
            let succesMessage = ref(false);
            let load = ref(false);
            let isload = ref(false);
            let iscloture = ref(false);
            onMounted(()=>verify())

            const verify = ()=>{
                axios.get("/verify/cloture")
                    .then((response) => {
                        iscloture.value = response.data;
                        isload.value = true;
                    })
            }
            const cloturer = ()=>{
                axios.get('cloturer/toute/evaluation')
                    .then((response) => {
                        Swal.fire({
                            title: 'SUCCES',
                            text: response.data.message,
                            icon: 'success',
                        });
                        load.value = true;
                    }).catch(error => {
                    catchErrors(error, succesMessage);
                })
            }

            return {
                isload,
                load,
                iscloture,
                cloturer,
            }
        }
    }

</script>

<style scoped>

</style>
