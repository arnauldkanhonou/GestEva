<template>
    <titre-application big-title="EVALUATION" small-title="Orientation" name-page="Salarié"></titre-application>
    <div class="container">
        <div v-if="messages!==''" class="alert alert-success">
            <strong> {{ messages }} </strong>
        </div>

        <div v-if="load">
            <!--&& cloture===false-->
            <div v-if="campagne===true" class="alert alert-info">
                <p>
                    <b>
                        Cher salarié, la campagne des évaluations a été lancée par les ressources humaines.
                        Vous êtes donc invité à vous auto-evalué puis passé un entretien avec votre responsable.
                    </b>
                </p>

                <div class="row">
                    <div class="mt-5 offset-3 col-md-8">
                        <router-link class="btn-green text-decoration-none px-32  py-2 text-white"
                                     :to="{name:'auto.evaluations.index'}"><b>DEMARRER VOTRE EVALUATION</b>
                        </router-link>
                    </div>
                </div>

            </div>
            <div v-else class="alert alert-danger">
                {{ messageErreur }}
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
    import axios from "axios";
    import {onMounted, ref} from "vue";
    import appService from "../../services/appService";

    export default {
        name: "evaluationIndex",
        components: {TitreApplication},
        props: {
            messages: {
                required: false,
                type: String,
                default: '',
            }
        },
        setup() {
            let user = JSON.parse(sessionStorage.getItem('user'));
            let messageErreur = ref('');
            let campagne = ref(false);
            let load = ref(false);
            let succesMessage = ref(false);
            let cloture = ref(false);
            const {catchErrors}= appService();
            onMounted(() => {
                axios.get('verify/campagne/performance/' + user.id)
                    .then((response) => {
                        if (response.data.data.code === 500) {
                            messageErreur.value = response.data.data.message;
                        } else {
                            campagne.value = response.data.data.campagne;
                            cloture.value = response.data.data.cloturePerformance;
                            messageErreur.value = response.data.data.message;
                        }
                        load.value = true;
                    })
                    .catch(error => {
                        catchErrors(error, succesMessage);
                    })
            })
            return {
                load,
                messageErreur,
                campagne,
                cloture,
            }
        }
    }
</script>

<style scoped>

</style>