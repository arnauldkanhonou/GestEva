<template>
    <titre-application big-title="CREATION SALARIE" small-title="Formulaire" name-page="Salarié"></titre-application>
    <!--    <router-link :to="{name:'employes.index'}"><svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" class="mr-3 cursor-pointer text-disabled" height="20" width="20" xmlns="http://www.w3.org/2000/svg"><path d="M257.5 445.1l-22.2 22.2c-9.4 9.4-24.6 9.4-33.9 0L7 273c-9.4-9.4-9.4-24.6 0-33.9L201.4 44.7c9.4-9.4 24.6-9.4 33.9 0l22.2 22.2c9.5 9.5 9.3 25-.4 34.3L136.6 216H424c13.3 0 24 10.7 24 24v32c0 13.3-10.7 24-24 24H136.6l120.5 114.8c9.8 9.3 10 24.8.4 34.3z"></path></svg></router-link>-->
    <div class="container  items-center  p-4">
        <div class="alert alert-danger" v-if="tabError">
            <strong>{{ tabError }}</strong>
        </div>
        <form class="container" method="POST" @submit.prevent="storeRessource">
            <div class="row mb-4">
                <div style="border: 1px solid #058069;" class="col-md-4">
                    <img id="previewHolder" v-if="!isAvatar" class="container mt-3" style="height: 185px;width: 260px"
                         alt="Photo candidat lanbda">

                    <img v-else class="container mt-3" style="height: 155px;width: 210px"
                         v-bind:src="store.state.MIX_API_URL_IMAGE+form.avatar"
                         alt="Photo candidat lanbda">

                    <div class="mt-4">
                        <input @change="onchange" type="file" id="photo" accept="image/png, image/jpeg">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="mb-4">
                        <label for="code" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Matricule<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.matricule?'is-invalid':''" @keyup="validation"
                               @blur="validateInputMatricule"
                               v-model="form.matricule" type="text" name="code" id="code" placeholder="Saisir le matricule"
                               class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                        <strong v-if="Errors.matricule" class="text-red-600">{{ Errors.matricule }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="nom" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Nom<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.nom?'is-invalid':''" @keyup="validateInputNom" @blur="validateInputNom"
                               v-model="form.nom" type="text" name="code" id="nom" placeholder="Saisir le nom"
                               class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                        <strong v-if="Errors.nom" class="text-red-600">{{ Errors.nom }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="prenoms" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Prénoms<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.prenoms?'is-invalid':''" @keyup="validateInputPrenoms"
                               @blur="validateInputPrenoms"
                               v-model="form.prenoms" type="text" name="code" id="prenoms" placeholder="Saisir le prénom"
                               class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.prenoms" class="text-red-600">{{ Errors.prenoms }}</strong>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="email" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>E-mail<i class="text-red-600">*</i></b>
                    </label>
                    <input :class="Errors.email?'is-invalid':''" @keyup="validateInputemail" @blur="validateInputemail"
                           v-model="form.email" type="email" name="email" id="email" placeholder="saisir email"
                           class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                    />
                    <strong v-if="Errors.email" class="text-red-600">{{ Errors.email }}</strong>
                </div>
                <div class="col-md-6">
                    <label for="telephone" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Téléphone<i class="text-red-600">*</i></b>
                    </label>
                    <input :class="Errors.telephone?'is-invalid':''" @keyup="validateInputTelephone"
                           @blur="validateInputTelephone"
                           v-model="form.telephone" type="text" name="telephone" id="telephone"
                           placeholder="saisir le numéro de téléphone"
                           class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                    />
                    <strong v-if="Errors.telephone" class="text-red-600">{{ Errors.telephone }}</strong>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="sexe" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Sexe<i class="text-red-600">*</i></b>
                    </label>
                    <select @blur="validateInputsexe" v-model="form.sexe" name="" id="sexe"
                            class="rounded-md form-select">
                        <option value="" readonly>Sélectionnez le sexe</option>
                        <option value="M">Masculin</option>
                        <option value="F">Féminin</option>
                    </select>
                    <strong v-if="Errors.sexe" class="text-red-600">{{ Errors.sexe }}</strong>
                </div>
                <div class="col-md-6">
                    <label for="dateEmbauche" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Date Embauche<i class="text-red-600">*</i></b>
                    </label>
                    <input :class="Errors.dateEmbauche?'is-invalid':''" @keyup="validateInputdateEmbauche"
                           @blur="validateInputdateEmbauche" v-model="form.dateEmbauche" type="date" name="libelle"
                           id="dateEmbauche"
                           class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                    />
                    <strong v-if="Errors.dateEmbauche" class="text-red-600">{{Errors.dateEmbauche }}</strong>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="categorieEmploye" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Categorie<i class="text-red-600">*</i></b>
                    </label>
                    <select @change="loardCatgorieProf" @blur="validateInputfonction" v-model="form.categorie" name=""
                            id="categorieEmploye"
                            class="rounded-md form-select">
                        <option value="" readonly>Sélectionnez la catégorie</option>
                        <option v-for="categorie in categorieEmployes" :key="categorie.id" :value="categorie.id">
                            {{categorie.libelle}}
                        </option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="categorieProf" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Catégorie Professionnelle<i class="text-red-600">*</i></b>
                    </label>
                    <select v-model="form.categorieProf" name="" id="categorieProf" class="rounded-md form-select">
                        <option value="" readonly>Sélectionnez la catégorie prof..</option>
                        <option v-for="categorieProf in categorieProfessionelles" :key="categorieProf.id"
                                :value="categorieProf.id">
                            {{categorieProf.libelle}} - {{categorieProf.code}}
                        </option>
                    </select>
                </div>

            </div>
            <div class="row">
                <div class="col-md-2">
                    <label for="typeEmploye" class="text-base font-medium text-[#07074D]"><b>Salarié Directeur
                        ?</b></label>
                    &nbsp;&nbsp;&nbsp;<input id="typeEmploye" @click="showDirection" v-model="form.isDirecteur"
                                             type="checkbox" class="w-4 h-4 text-green-600">
                </div>
                <div class="col-md-10">
                    <hr>
                </div>
            </div>
            <br>

            <div id="hideDirection">
                <div class="col-md-12">
                    <label for="direction" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Direction<i class="text-red-600">*</i></b>
                    </label>
                    <select v-model="form.direction" name="" id="direction" class="rounded-md form-select">
                        <option value="" readonly>Sélectionnez la direction</option>
                        <option v-for="direction in directions" :key="direction.id" :value="direction.id">
                            {{direction.libelle}}
                        </option>
                    </select>
                </div>
                <br>
            </div>

            <div class="row mb-4 hideEmploye">
                <div class="col-md-6">
                    <label for="service" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Service<i class="text-red-600">*</i></b>
                    </label>
                    <select @change="loardFonctionUnite" @blur="validateInputfonction" v-model="form.service"
                            name=""
                            id="service"
                            class="rounded-md form-select">
                        <option value=null readonly>Sélectionnez le service</option>
                        <option v-for="service in services" :key="service.id" :value="service.id">
                            {{service.libelle}}
                        </option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="fonction" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Fonction<i class="text-red-600">*</i></b>
                    </label>
                    <select @blur="validateInputfonction" v-model="form.fonction" name="" id="fonction"
                            class="rounded-md form-select">
                        <option value=null readonly>Sélectionnez la fonction</option>
                        <option v-for="fonction in fonctions" :key="fonction.id" :value="fonction.id">
                            {{fonction.libelle}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="row mb-4 hideEmploye">
                <div class="col-md-6">
                    <label for="unite" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Unité du service<i class="text-red-600">*</i></b>
                    </label>
                    <select v-model="form.unite" name="" id="unite" class="rounded-md form-select">
                        <option value=null readonly>Sélectionnez l'unité</option>
                        <option v-for="unite in unites" :key="unite.id" :value="unite.id">
                            {{unite.libelle}}
                        </option>
                    </select>
                </div>
                <div class="col-md-6">
                    <br>
                    <label for="respDepart" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Responsable Pole?</b>
                    </label>
                    <input @click="showDepartement" type="checkbox" name="checkResp"
                           id="respDepart" class="w-4 h-4 text-green-600">
                </div>
            </div>
            <div class="hideDepart">
                <div class="col-md-12 mt-4">
                    <label for="depart" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Poles<i class="text-red-600">*</i></b>
                    </label>
                    <select v-model="form.departement" name="" id="depart" class="rounded-md form-select">
                        <option value="" selected readonly>Sélectionnez le departement</option>
                        <option v-for="depart in departements" :key="depart.id" :value="depart.id">
                            {{depart.libelle}}
                        </option>
                    </select>
                </div>
                <br>
            </div>
            <div class="row hideEmploye hideSectionDepart">
                <div class="col-md-4">
                    <br>
                    <label for="respUnite" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Responsable Unité?</b>
                    </label>
                    <input @click="checkRadioButton('respUnite')" type="radio" name="responsable"
                           id="respUnite" class="w-4 h-4 text-green-600">
                </div>
                <div class="col-md-4">
                    <br>
                    <label for="respService" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Responsable Service?</b>
                    </label>
                    <input @click="checkRadioButton('respService')" type="radio" name="responsable" id="respService"
                           class="w-4 h-4 text-green-600">
                </div>
                <div class="col-md-4">
                    <br>
                    <label for="respAucun" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Aucun</b>
                    </label>
                    <input @click="checkRadioButton('respAucun')" checked type="radio" name="responsable"
                           id="RespAucun"
                           class="w-4 h-4 text-green-600">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                <br>
                <label for="respUnite" class="mb-1 block text-base font-medium text-[#07074D]">
                    <b>A accès à l'outil informatique</b>
                </label>
                <input v-model="form.haveComputer" type="checkbox" name="responsable"
                      class="w-4 h-4 text-green-600">
            </div>
            </div>

            <div class="mt-5">
                <button :disabled="isFormButtonDisabled"
                        :class="isFormButtonDisabled?'btn btn-default text-white':'py-2 px-20 text-white align-content-center'"
                        type="submit" class="btn-green col-md-12">
                    <b id="btn-save">Enregistrer</b>
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    import {ref, reactive} from "vue";
    import useSubmitButtonForm from "../../validation/confirmForm";
    import {onMounted} from "vue";
    import useFonctions from "../../services/fonction";
    import useEmploye from "../../services/employeservice";
    import useValidateFormEmploye from "../../validation/validationFormEmploye";
    import useUnite from "../../services/unites";
    import useServices from "../../services/services";
    import useCategorieEmploye from "../../services/categorieEmploye";
    import useCategorieProfessionnelle from "../../services/categorieProfessionel";
    import {useStore} from "vuex";
    import useUpdateVariable from "../../services/cleanVariable";
    import TitreApplication from "../block/TitreApplication";
    import useDirection from "../../services/directionServices";
    import useDepartement from "../../services/departements";
    import axios from "axios";
    import Swal from "sweetalert2";

    export default {
        name: "EmployeCreate",
        components: {TitreApplication},
        setup() {
            onMounted(() => {
                $(function () {
                    $('#hideDirection').hide('slow');
                    $('.hideDepart').hide('slow');
                });
            })

            const form = reactive({
                matricule: '', nom: '', prenoms: '', sexe: '', email: '', respAucun: false, pathFile: null,
                telephone: '', dateEmbauche: '', respUnite: false, respService: false, respDepartement: false,
                fonction: null, unite: null, service: null, categorie: '', categorieProf: '',haveComputer:false,
                avatar: 'images/avatar1.png', isDirecteur: false, direction: null,departement:null
            });

            const salarie_paie = ref({});

            const isAvatar = ref(true);

            const showDirection = () => {
                form.direction = null;
                if ($('#typeEmploye').is(':checked')) {
                    form.isDirecteur = true;
                    form.direction = '';
                    $('#hideDirection').show('slow');
                    $('.hideEmploye').hide('slow');
                } else {
                    form.isDirecteur = false;
                    form.direction = null;
                    $('#hideDirection').hide('slow');
                    $('.hideEmploye').show('slow');
                }
            }
            const showDepartement = () => {
                form.departement = null;
                if ($('#respDepart').is(':checked')) {
                    form.respDepartement = true;
                    $('.hideDepart').show('slow');
                    $('.hideSectionDepart').hide('slow');
                } else {
                    form.respDepartement = false;
                    $('.hideDepart').hide('slow');
                    $('.hideSectionDepart').show('slow');
                }
            }

            const checkRadioButton = (fieldName) => {
                switch (fieldName) {
                    case 'respUnite':
                        form.respUnite = true;
                        form.respService = false;
                        form.respDepartement = false;
                        form.respAucun = false;
                        break;
                    case 'respDepartement':
                        form.respDepartement = true;
                        form.respService = false;
                        form.respUnite = false;
                        form.respAucun = false;
                        break;
                    case 'respAucun':
                        form.respAucun = true;
                        form.respDepartement = false;
                        form.respUnite = false;
                        form.respService = false;
                        break;
                    case 'respService':
                        form.respService = true;
                        form.respDepartement = false;
                        form.respUnite = false;
                        form.respAucun = false;
                        break;
                }
            }
            const store = useStore();

            const onchange = (e) => {
                form.pathFile = e.target.files[0];
                console.log(form.pathFile);
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#previewHolder').attr('src', e.target.result);
                }
                isAvatar.value = false;
                reader.readAsDataURL(e.target.files[0]);
            }

            const {Errors, validateInputMatricule, validateInputNom, validateInputPrenoms, validateInputemail, validateInputsexe, validateInputdateEmbauche, validateInputfonction, validateInputunite, validateInputTelephone} = useValidateFormEmploye(form);

            const {cleanVar} = useUpdateVariable(Errors, form);
            onMounted(cleanVar);

            const {isFormButtonDisabled} = useSubmitButtonForm(form, Errors);

            const {services, getServices} = useServices();
            onMounted(getServices);

            const {fonctions, getFonctionByIdService} = useFonctions();

            const {directions, getDirections} = useDirection();
            const {departements, getDepartements} = useDepartement();

            onMounted(getDirections);
            onMounted(getDepartements);

            const {unites, getUniteByIdService} = useUnite();

            const loardFonctionUnite = () => {
                getFonctionByIdService(form.service)
                getUniteByIdService(form.service)
            }

            const validation = () => {
               validateInputMatricule();
               let data = {
                   'code':form.matricule
               }
               axios.post("salarie/by/paie",data)
                .then((response)=>{
                    if (response.status===200){
                        form.nom=response.data.nom;
                        form.prenoms=response.data.prenoms;
                        form.email=response.data.email;
                        form.telephone=response.data.telephone;
                        form.dateEmbauche=response.data.dateEmbauche;
                        form.sexe=response.data.civilite;
                        form.categorie=response.data.groupe;
                        form.service=response.data.service;
                        loardFonctionUnite();
                        loardCatgorieProf();
                        form.categorieProf=response.data.categorie;
                        form.fonction=response.data.fonction;
                    }else {
                        Swal.fire({
                            title: 'error',
                            text:  'Information du salarié introuvable dans la paie',
                            icon: 'error',
                        });
                    }

                })
            }

            const {categorieEmployes, getCategorieEmployes} = useCategorieEmploye();
            onMounted(getCategorieEmployes);

            const {categorieProfessionelles, getCategorieProfessionnellesByCategorie} = useCategorieProfessionnelle();

            const loardCatgorieProf = () => {
                getCategorieProfessionnellesByCategorie(form.categorie)
            }

            const {tabError, createEmploye} = useEmploye();

            const storeRessource = async () => {
                if (form.isDirecteur) {
                    if (form.direction === null) {
                        await Swal.fire({
                            title: 'error',
                            text:  'Veuillez choisir la diretion du directeur',
                            icon: 'error',
                        });
                        return;
                    }

                } else {
                    if (form.service === null || form.fonction === null && form.unite === null) {
                        await Swal.fire({
                            title: 'error',
                            text:  'Veuillez choisir le service, fonction et unité de l\'employé',
                            icon: 'error',
                        });
                        return;
                    }
                }
                $('#btn-save').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
                const formData = new FormData();
                formData.set('pathFile', form.pathFile)
                formData.set('matricule', form.matricule);
                formData.set('nom', form.nom);
                formData.set('prenoms', form.prenoms);
                formData.set('respService', form.respService);
                formData.set('respDepartement', form.respDepartement);
                formData.set('respUnite', form.respUnite);
                formData.set('fonction', form.fonction);
                formData.set('unite', form.unite);
                formData.set('direction', form.direction);
                formData.set('departement', form.departement);
                formData.set('isDirecteur', form.isDirecteur);
                formData.set('telephone', form.telephone);
                formData.set('email', form.email);
                formData.set('sexe', form.sexe);
                formData.set('dateEmbauche', form.dateEmbauche);
                formData.set('categorieProf', form.categorieProf);
                await createEmploye(formData)
                form.matricule = '';
            }

            return {
                store,
                form,
                isAvatar,
                tabError,
                Errors,
                isFormButtonDisabled,
                fonctions,
                services,
                unites,
                categorieEmployes,
                categorieProfessionelles,
                directions,
                departements,
                salarie_paie,
                showDirection,
                showDepartement,
                onchange,
                loardFonctionUnite,
                loardCatgorieProf,
                checkRadioButton,
                storeRessource,
                validateInputMatricule,
                validateInputNom,
                validateInputPrenoms,
                validateInputemail,
                validateInputsexe,
                validateInputdateEmbauche,
                validateInputfonction,
                validateInputunite,
                validateInputTelephone,
                validation,
            }
        }
    }
</script>

<style scoped>

</style>
