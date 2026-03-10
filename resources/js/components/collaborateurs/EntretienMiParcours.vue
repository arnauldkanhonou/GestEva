<template>
    <div>
        <titre-application big-title="ENTRETIEN-MI-PARCOURS" small-title="Entretien"
                           name-page="salarié"></titre-application>
        <div v-if="load" class="mt-4">
            <h4 align="center"><strong><small><b class="text-red-600">POINT SUR LA PERIODE ECOULEE</b>(Rappel des faits
                marquants qui
                ont pu influencer
                l'activité et les résultats.)</small></strong>
            </h4>
            <br>
            <section>
                <h4><strong>Points positifs</strong></h4>
                <br>
                <div class="row">
                    <div class="col-md-9 hideInputAccomplissement">
                        <input v-model="form.accomplissement" type="text"
                               placeholder="Veuillez saisir votre accomplissement"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
                    </div>
                    <div class="col-md-3 hideInputAccomplissement">
                        <button @click="addFaireMarquant('accomplissement')"
                                class="btn-green text-decoration-none px-3 py-2 text-white"><i
                            class="fa fa-plus"></i> Ajouter à la liste
                        </button>
                    </div>
                </div>
                <br>

                <div>
                    <ul class="alert-success">
                        <br>
                        <li v-for="(value,index) in form.tabAccomplissement" :key="index"><i
                            class="fa fa-check text-green-600"></i><strong>{{ value }}</strong>&nbsp;&nbsp;&nbsp;
                            <button @click="removeFaireMarquant('accomplissement',index,true)"
                                    class="bg-green-600 hover:bg-green-600 text-white px-1 py-0 rounded"><i
                                class="fa fa-edit"></i>&nbsp;Modifier
                            </button>&nbsp;&nbsp;&nbsp;
                            <button @click="removeFaireMarquant('accomplissement',index,false)"
                                    class="bg-red-600 hover:bg-red-600 text-white px-1 py-0 rounded"><i
                                class="fa fa-minus-circle"></i>&nbsp;Retirer
                            </button>
                            <br><br>
                        </li>
                        <br>
                    </ul>
                </div>
                <hr>
            </section>
            <!--v-show="form.tabAccomplissement.length>0"-->
            <section id="sectionDifficultes">
                <h4><strong>Point négatifs</strong></h4>
                <br>
                <div class="row">
                    <div class="col-md-9 hideInputDifficulte">
                        <input v-model="form.difficulte" type="text"
                               placeholder="Veuillez saisir votre difficulté"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
                    </div>
                    <div class="col-md-3 hideInputDifficulte">
                        <button @click="addFaireMarquant('difficulte')"
                                class="btn-green text-decoration-none px-3 py-2 text-white"><i
                            class="fa fa-plus"></i> Ajouter à la liste
                        </button>
                    </div>
                </div>
                <br>

                <div>
                    <ul class="alert-success">
                        <br>
                        <li v-for="(value,index) in form.tabDifficultes" :key="index">
                            <i class="fa fa-check text-green-600"></i><strong> {{ value }}</strong>&nbsp;&nbsp;&nbsp;
                            <button @click="removeFaireMarquant('difficulte',index,true)"
                                    class="bg-green-600 hover:bg-green-600 text-white px-1 py-0 rounded"><i
                                class="fa fa-edit"></i>&nbsp;Modifier
                            </button>&nbsp;&nbsp;&nbsp;
                            <button @click="removeFaireMarquant('difficulte',index,false)"
                                    class="bg-red-600 hover:bg-red-600 text-white px-1 py-0 rounded"><i
                                class="fa fa-minus-circle"></i>&nbsp;Retirer
                            </button>
                            <br><br></li>
                        <br>
                    </ul>
                </div>
                <hr>
            </section>

            <section>
                <h4 align="center"><strong class="text-red-600"><b>PASSAGE EN REVUE DES OBJECTIFS</b></strong></h4>
                <table class="mt-4 min-w-full leading-normal table table-bordered table-striped"
                       style="background-color:#acb3c4 ">
                    <thead>
                    <tr>
                        <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                            <b class="text-white">Rappel des objectifs fixés en début d'année</b>
                        </th>
                        <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                            <b class="text-white">Niveau de réalisation</b>
                        </th>
                        <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                            <b class="text-white">Commentaire</b>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="(objectif,index) in objectifs" :key="objectif.id">
                        <tr>
                            <td style="width: 55%" class="py-2 border-b border-gray-200 bg-white text-md"
                                v-text="objectif.libelle"></td>
                            <td style="width: 40%" class="py-2 border-b border-gray-200 bg-white text-md">
                                <input class="w-4 h-4" type="radio" :name="'niveau'+parseInt(objectif.id)"
                                       :checked="objectif.NivRealisationMPEvaluateur==='largement dépassé'"
                                       @click="setNiveauRealisationObjectif('largement dépassé',objectif.id)"
                                       value="largement dépassé"><b>A(<b class="text-red-600">largement dépassé</b>)</b>&nbsp;&nbsp;&nbsp;
                                <input class="w-4 h-4" type="radio" :name="'niveau'+parseInt(objectif.id)"
                                       :checked="objectif.NivRealisationMPEvaluateur==='atteint'"
                                       @click="setNiveauRealisationObjectif('atteint',objectif.id)"
                                       value="atteint"><b>B(<b class="text-red-600">atteint</b>)</b>&nbsp;&nbsp;&nbsp;
                                <input class="w-4 h-4" type="radio" :name="'niveau'+parseInt(objectif.id)"
                                       :checked="objectif.NivRealisationMPEvaluateur==='partiellement atteint'"
                                       @click="setNiveauRealisationObjectif('partiellement atteint',objectif.id)"
                                       value="partiellement atteint"><b>C(<b class="text-red-600">partiellement
                                atteint</b>)</b>&nbsp;&nbsp;&nbsp;
                                <input class="w-4 h-4" type="radio" :name="'niveau'+parseInt(objectif.id)"
                                       :checked="objectif.NivRealisationMPEvaluateur==='non atteint'"
                                       @click="setNiveauRealisationObjectif('non atteint',objectif.id)"
                                       value="non atteint"><b>D(<b class="text-red-600">non atteint</b>)</b>&nbsp;&nbsp;&nbsp;
                            </td>
                            <td style="width: 5%" class=" py-1 border-b border-gray-200 bg-white text-sm">
                                &nbsp;&nbsp;<button @click="setCommentaireObjectif(objectif.id,objectif.commentaireEvaluateurMP,false)"
                                                    class="bg-green-600 hover:bg-green-600 text-white px-2 py-1 rounded"
                                                    data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                <i class="fa fa-book"></i>
                            </button>
                            </td>
                        </tr>
                    </template>

                    </tbody>
                </table>
                <hr>
            </section>
            <h4 class="mt-4" align="center"><strong class="text-red-600"><b>AUTRES REALISATIONS ACCOMPLIES OU DIFFICULTES
                SOULIGNEES A MI-PARCOURS</b></strong></h4>
            <div class="mt-5">
                <button id="btnRealisation" data-bs-toggle="modal" data-bs-target="#staticBackdrop1"
                        class="uppercase btn-green text-decoration-none px-3 py-2 text-white"><i
                    class="fa fa-book"></i> AUTRES REALISATIONS OU DIFFICULTES
                </button>
            </div>
            <table class="mt-4 min-w-full leading-normal table table-bordered table-striped"
                   style="background-color:#acb3c4 ">
                <thead>
                <tr>
                    <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                        <b class="text-white">Realisation</b>
                    </th>
                    <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                        <b class="text-white">Difficultés</b>
                    </th>
                </tr>
                </thead>
                <tbody>
                <template v-for="(realisation,index) in form.tabRealisation" :key="realisation.id">
                    <tr>
                        <td class="py-2 border-b border-gray-200 bg-white text-md"
                            v-text="realisation.libelle"></td>
                        <td class="py-2 border-b border-gray-200 bg-white text-md"
                            v-text="realisation.difficulte"></td>
                        <td class="py-2 border-b border-gray-200 bg-white text-md">
                            <button @click="updateRealisation(index,realisation.id)" id="btn-edit-miparcours" title="suppimer la réalisation"
                                    class="btn-sm bg-green-600 text-white font-bold py-1 px-2">
                                <i class="fa fa-edit"></i>
                            </button>&nbsp;&nbsp;&nbsp;&nbsp;
                            <button @click="deleteRealisation(realisation.id)" id="btn-edit-miparcours" title="suppimer la réalisation"
                                    class="btn-sm bg-red-600 text-white font-bold py-1 px-2"
                            ><i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
            <section>
                <hr>
                <h4 class="title">Commentaire</h4>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <label for="synthese" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Commentaire de l'évaluateur<i class="text-red-600">*</i></b>
                        </label>
                        <textarea @blur="addcommentaireEvaluation" v-model="form.commentaireEvaluer" type="text" name="libelle"
                                  id="synthese" placeholder="saisir un commentaire"
                                  class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                    </div>
                </div>
            </section>
            <div class="mt-5 row">
                <div class="offset-6 col-md-3">
                    <button data-bs-toggle="modal" data-bs-target="#staticBackdrop2"
                            class="col-md-12 uppercase bg-red-600 text-decoration-none px-3 py-2 text-white">
                        <i class="fa fa-check-circle"></i> <b>ENREGISTRER et envoyer au rh  </b>
                    </button>
                </div>

                <div class="col-md-3">
                    <button id="saveEvaluation"  @click="saveEntretien"
                            class="col-md-12 bg-primary text-white px-4 py-2" type="button"><b>ENREGISTRER AU BROUILLON</b>
                    </button>
                </div>
            </div>

        </div>
        <div v-else class="mt-4">
            <br>
            <br>
            <div class="col-md-3 offset-5" v-if="code_retour===200">
                <strong style="font-size: 20px;color: #057e72;" class="mb-0">Chargement...</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
            </div>
            <div v-else class="col-md-12 alert alert-danger">
                {{ messageErreur }}
            </div>
        </div>


        <!-- Modal commentaire objectif -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                    <div style="background-color: #acb3c4"
                         class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                        <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="exampleModalLabel">
                            <b><i class="fa fa-question-circle"></i> Analyse et commentaire éventuel</b>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="col-md-12">
                                <label for="commentaire" class="mb-1 block text-base font-medium text-[#07074D]">
                                    <b>Commentaire<i class="text-red-600">*</i></b>
                                </label>
                                <textarea v-model="form.commentaire" @blur="setCommentaireObjectif(0,'',true)" rows="10"
                                          type="text" id="commentaire" class="mt-2 form-control"/>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer flex items-center justify-end  border-t border-gray-200">
                        <button id="btn-fermer" class="bg-gray-600 hover:bg-gray-600 text-white font-bold py-2 px-4"
                                data-bs-dismiss="modal">Fermer
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal autre realisation -->
        <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                    <div style="background-color: #acb3c4"
                         class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                        <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="exampleModalLabel1">
                            <b><i class="fa fa-question-circle"></i>AJOUTER UNE REALISATION OU UNE DIFFICULTE</b>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <strong class="text-danger mb-2">
                            <b class="text-black text-decoration-underline">NB:</b> VEUILLEZ RENSEIGNER LA REALISATION OU LA DIFFICULTE OU LES DEUX
                        </strong>
                        <div class="container mt-2">
                            <div class="col-md-12">
                                <label for="realisation" class="mb-1 block text-base font-medium text-[#07074D]">
                                    <b>Réalisation</b>
                                </label>
                                <input v-model="form.realisation.libelle" type="text" name="code" id="realisation"
                                       placeholder="Saisir votre réalisation"
                                       class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                            </div>

                            <div class="mt-4 col-md-12">
                                <label for="difficulte" class="mb-1 block text-base font-medium text-[#07074D]">
                                    <b>Difficultés</b>
                                </label>
                                <input v-model="form.realisation.difficulte" type="text" name="code" id="difficulte"
                                       placeholder="Saisir votre réalisation"
                                       class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                            </div>
                            <div class="mt-4 offset-4 col-md-4">
                                <button @click="addRealisationAmiParcours"
                                        id="btnSaveRealisation"     class="col-md-12 bg-green-600 hover:bg-green-600 text-white font-bold py-2 px-4"
                                >Enregistrer
                                </button>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer flex items-center justify-end  border-t border-gray-200">
                        <button id="btn-fermer1" class="bg-gray-600 hover:bg-gray-600 text-white font-bold py-2 px-4"
                                data-bs-dismiss="modal">Fermer
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal validation -->
        <div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                    <div style="background-color: #acb3c4"
                         class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                        <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="exampleModalLabel2">
                            <b><i class="fa fa-question-circle"></i> Autres Réalisations</b>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div>
                                <strong class="text-red-600">
                                    Cette action va valider l'évaluation mi-parcours du collaborateur et l'envoyer au RH.
                                    <br>Voulez-vous vraiment validé l'évaluation ?
                                </strong>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer flex items-center justify-end  border-t border-gray-200">
                        <button id="btn-fermerValidation"
                                class="bg-gray-600 hover:bg-gray-600 text-white font-bold py-2 px-3"
                                data-bs-dismiss="modal">Non
                        </button> &nbsp; &nbsp; &nbsp; &nbsp;
                        <button id="btn-valider" @click="clotureEvaluation"
                                class="btn-green text-white font-bold py-2 px-3">Oui
                        </button>
                    </div>
                </div>

            </div>
        </div>


    </div>
</template>

<script>
    import TitreApplication from "../block/TitreApplication";
    import {onMounted} from "@vue/runtime-core";
    import axios from "axios";
    import {reactive, ref} from "vue";
    import appService from "../../services/appService";
    import Swal from "sweetalert2";
    import useCollaborateur from "../../services/collaborateurs";
    import {useRouter} from "vue-router";

    export default {
        name: "EntretienMiParcours",
        components: {TitreApplication},
        props:{
            id:String
        },
        setup(props){
            let user = JSON.parse(sessionStorage.getItem('user'));
            const router = useRouter();
            let load = ref(false);
            let code_retour = ref(200);
            const messageErreur = ref('');
            const evaluation = ref('');
            const form = reactive({
                accomplissements: '',
                accomplissement: '',
                tabAccomplissement: [],
                difficultes: '',
                difficulte: '',
                tabDifficultes: [],
                idObjectif: '',
                commentaire: '',
                commentaireEvaluer: '',
                realisation: {
                    libelle: '',
                    difficulte: '',
                },
                tabRealisation: [],
            });
            const {dasboardName, catchErrors} = appService();
            const sleep = (ms) => {
                return new Promise(resolve => setTimeout(resolve, ms));
            }
            onMounted(()=>{
                axios.get('init/entretien//miparcours/'+props.id)
                .then((response)=>{
                    console.log(response);
                    if (response.data.data.code === 200) {
                        let resp = response.data.data;
                        form.accomplissements = resp.evaluation.accomplissement;
                        form.difficultes = resp.evaluation.difficulteMission;
                        form.tabAccomplissement = resp.tabAccomplissement;
                        form.tabDifficultes = resp.tabDifficulte;
                        form.tabRealisation = resp.realisations;
                        form.commentaireEvaluer = resp.evaluation.commentaireResp
                        evaluation.value = resp.evaluation;
                        load.value = true;
                    } else {
                        code_retour.value=403;
                        messageErreur.value=response.data.data.message;
                        evaluation.value = response.data.data.evaluation;
                        sleep(100).then(() => {
                            let employe = evaluation.value.employe;
                            router.push({
                                name: 'collaborateur.evaluations',
                                params: {
                                    id: employe.id,
                                    intitule: employe.nom + ' ' + employe.prenoms
                                }
                            });
                        })
                        //Swal.fire(response.data.data.message);
                    }
                })
            })

            const {objectifs, getObjectifCollaborateurValider} = useCollaborateur();

            onMounted(() => getObjectifCollaborateurValider(props.id,'entretienMiparcoursSalarieByResp'));

            let data = reactive({
                faireMarquand: '',
                type: ''
            })

            const addFaireMarquant = (type) => {
                data.type = type;
                if (type === 'accomplissement') {
                    if (form.accomplissement === '') {
                        alert('Veuillez saisir votre accomplissement');
                        return;
                    }
                    form.accomplissements = (form.accomplissements ? form.accomplissements + ';' + form.accomplissement : form.accomplissement);
                    form.tabAccomplissement.push(form.accomplissement);
                    data.faireMarquand = form.accomplissements;
                    form.accomplissement = '';
                }
                if (type === 'difficulte') {
                    if (form.difficulte === '') {
                        alert('Veuillez saisir votre difficulté');
                        return;
                    }
                    form.difficultes = (form.difficultes ? form.difficultes + ';' + form.difficulte : form.difficulte);
                    form.tabDifficultes.push(form.difficulte);
                    data.faireMarquand = form.difficultes;
                    form.difficulte = '';
                }

                axios.post('add/faire/marquant/collaborateur/'+ user.id + '/' + evaluation.value.id + '/param', {...data})
                    .then((response) => {
                    })
                    .catch((error) => {
                        catchErrors(error, succesMessage);
                    })

            }

            const removeFaireMarquant = (type, index, update = false) => {
                data.type = type;
                if (type === 'accomplissement') {
                    if (update) {
                        form.accomplissement = form.tabAccomplissement[index];
                    }
                    form.tabAccomplissement.splice(index, 1);
                    if (form.tabAccomplissement.length > 0) {
                        form.accomplissements = '';
                        for (let i = 0; i < form.tabAccomplissement.length; ++i) {
                            if (form.accomplissements !== '') {
                                form.accomplissements += ';' + form.tabAccomplissement[i];
                            } else {
                                form.accomplissements = form.tabAccomplissement[i];
                            }
                        }
                    } else
                        form.accomplissements = '';

                    data.faireMarquand = form.accomplissements;
                }

                if (type === 'difficulte') {
                    if (update) {
                        form.difficulte = form.tabDifficultes[index];
                    }
                    form.tabDifficultes.splice(index, 1);
                    if (form.tabDifficultes.length > 0) {
                        form.difficultes = '';
                        for (let i = 0; i < form.tabDifficultes.length; ++i) {
                            if (form.difficultes !== '') {
                                form.difficultes += form.tabDifficultes[i] + ';';
                            } else {
                                form.difficultes = form.tabDifficultes[i];
                            }
                        }
                    } else
                        form.difficultes = '';
                    data.faireMarquand = form.difficultes;
                }

                axios.post('add/faire/marquant/collaborateur/' + user.id + '/' + evaluation.value.id + '/param', {...data})
                    .then((response) => {
                        //console.log(response)
                    })
                    .catch(error => {
                        catchErrors(error, succesMessage);
                    })

            }

            const setNiveauRealisationObjectif = (niveau, idObjectif) => {
                let data = {
                    niveau: niveau,
                    type: 'evaluateur',
                }
                axios.post('niveau/realisation/miparcours/' + idObjectif, data)
                    .then((response) => {

                    });
            }

            const setCommentaireObjectif = (idObjectif,commentaire, go = false) => {
                if (!go) {
                    form.idObjectif = idObjectif;
                    form.commentaire = commentaire;
                    return;
                }
                let data = {
                    commentaire: form.commentaire,
                    type: 'evaluateur',
                }

                axios.post('add/commentaire/miparcours/' + form.idObjectif, data)
                    .then((response) => {
                        getObjectifCollaborateurValider(props.id)
                    });
            }

            const addRealisationAmiParcours = () => {
                $('#btnSaveRealisation').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
                if (form.realisation.libelle === '' && form.realisation.difficulte === '') {
                    alert('Renseigner au moins un champs et mettre R.A.S pour le second s\'il y en a pas');
                    $('#btnSaveRealisation').html('Enregistrer');
                    return;
                }
                let data = {
                    libelle: form.realisation.libelle,
                    difficulte: form.realisation.difficulte,
                }
                axios.post('add/realisation/miparcours/'+evaluation.value.id,{...data})
                    .then((response)=>{
                        $('#btn-fermer1')[0].click();
                        form.tabRealisation = response.data.data;
                        form.realisation.libelle = '';
                        form.realisation.difficulte = '';
                        $('#btnSaveRealisation').html('Enregistrer');
                    })

            }

            const updateRealisation = (index,id)=>{
                axios.get('remove/realisation/miparcours/' + id+'/'+evaluation.value.id)
                    .then((response) => {
                        let data = form.tabRealisation[index];
                        form.realisation.libelle = data.libelle;
                        form.realisation.difficulte = data.difficulte;
                        $('#btnRealisation')[0].click();
                        form.tabRealisation = response.data.data;
                    });
            }

            const deleteRealisation = (id)=>{
                if (confirm('Voulez-vous vraiment supprimer cette réalisation')){
                    axios.get('remove/realisation/miparcours/' + id+'/'+evaluation.value.id)
                        .then((response) => {
                            form.tabRealisation = response.data.data;
                        });
                }
            }

            const addcommentaireEvaluation = ()=>{
                let data = {
                    type:'evaluateur',
                    commentaire:form.commentaireEvaluer,
                }
                axios.post('add/commentaire/evaluation/miparcours/' + evaluation.value.id, data)
                    .then((response) => {

                    });
            }

            const clotureEvaluation = ()=>{
                let data = {
                    type:'evaluateur'
                }
                axios.post('cloture/evaluation/miparcours/' + evaluation.value.id,{...data})
                    .then((response) => {
                        if (response.data.data.code===500){
                            Swal.fire({
                                title: 'Erreur serveur',
                                text:  response.data.data.message,
                                icon: 'error',
                            });
                        }else {
                            $('#btn-fermerValidation')[0].click();
                            let name = dasboardName();
                            let succesMessage = 'Vous avez bien terminé l\'entretien mi-parcours avec votre collaborateur.'
                            router.push({name: name, params: {messages: succesMessage}});
                        }

                    })
                    .catch((error) => {
                        catchErrors(error, succesMessage);
                    })
            }

            const saveEntretien = ()=>{
                let name = dasboardName();
                let succesMessage = 'Vous avez bien enregistré l\'entretien mi-parcours avec votre collaborateur.'
                router.push({name: name, params: {messages: succesMessage}});
            }

            return{
                props,
                form,
                objectifs,
                load,
                code_retour,
                messageErreur,
                addFaireMarquant,
                removeFaireMarquant,
                setNiveauRealisationObjectif,
                setCommentaireObjectif,
                addRealisationAmiParcours,
                addcommentaireEvaluation,
                clotureEvaluation,
                updateRealisation,
                deleteRealisation,
                saveEntretien,
            }
        }
    }
</script>

<style scoped>

</style>
