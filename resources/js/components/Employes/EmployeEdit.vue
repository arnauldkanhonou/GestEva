<template>
    <titre-application big-title="MODIFICATION SALARIES" small-title="Formulaire-Modification" name-page="Salariés"></titre-application>
    <div class="container  items-center  p-4">
        <div class="alert alert-danger" v-if="tabError">
            <strong>{{ tabError }}</strong>
        </div>
        <form class="container" method="POST" @submit.prevent="storeRessource">
            <div class="row mb-4">
                <div style="border: 1px solid #058069;" class="col-md-4">
                    <img id="previewHolder" v-if="!isAvatar" class="container mt-3" style="height: 185px;width: 260px"
                         alt="Photo candidat lanbda" v-bind:src="store.state.MIX_API_URL_IMAGE+employe.pathFile">

                    <img v-else class="container mt-3" style="height: 155px;width: 210px" v-bind:src="employe.avatar"
                         alt="Photo candidat lanbda">

                    <div class="mt-4">
                        <input @change="onchange" type="file" id="photo" accept="image/png, image/jpeg">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="mb-4">
                        <label for="code" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Code<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.matricule?'is-invalid':''" @keyup="validateInputMatricule"
                               @blur="validateInputMatricule"
                               v-model="employe.matricule" type="text" name="code" id="code" placeholder="Saisir le matricule"
                               class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                        <strong v-if="Errors.matricule" class="text-red-600">{{ Errors.matricule }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="nom" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Nom<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.nom?'is-invalid':''" @keyup="validateInputNom" @blur="validateInputNom"
                               v-model="employe.nom" type="text" name="code" id="nom" placeholder="Saisir le nom"
                               class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                        <strong v-if="Errors.nom" class="text-red-600">{{ Errors.nom }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="prenoms" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Prénoms<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.prenoms?'is-invalid':''" @keyup="validateInputPrenoms"
                               @blur="validateInputPrenoms"
                               v-model="employe.prenoms" type="text" name="code" id="prenoms" placeholder="Saisir le prénom"
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
                           v-model="employe.email" type="email" name="email" id="email" placeholder="saisir email"
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
                           v-model="employe.telephone" type="text" name="telephone" id="telephone"
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
                    <select @blur="validateInputsexe" v-model="employe.sexe" name="" id="sexe"
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
                           @blur="validateInputdateEmbauche" v-model="employe.dateEmbauche" type="date" name="libelle"
                           id="dateEmbauche"
                           class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                    />
                    <strong v-if="Errors.dateEmbauche" class="text-red-600">{{Errors.dateEmbauche }}</strong>
                </div>
            </div>
            <!--:selected="parseInt(dataSQl.idCategorie)===parseInt(categorie.id)"-->

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="categorieEmploye" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Categorie<i class="text-red-600">*</i></b>
                    </label>
                    <select v-model="employe.categorie" @change="loardCatgorieProf" @blur="validateInputfonction"  name=""
                            id="categorieEmploye"
                            class="rounded-md form-select">
                        <option value="" readonly>Sélectionnez la catégorie</option>
                        <option  v-for="categorie in categorieEmployes" :key="categorie.id" :value="categorie.id" >
                            {{categorie.libelle}}
                        </option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="categorieProf" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Catégorie Professionnelle<i class="text-red-600">*</i></b>
                    </label>
                    <select v-model="employe.categorieProf" name="" id="categorieProf" class="rounded-md form-select">
                        <option value="" readonly>Sélectionnez la catégorie prof..</option>
                        <option v-for="categorieProf in categorieProfessionelles" :key="categorieProf.id" :value="categorieProf.id">
                            {{categorieProf.libelle}} - {{categorieProf.code}}
                        </option>
                    </select>
                </div>

            </div>
            <!--:selected="parseInt(dataSQl.idService)===parseInt(service.id)"-->

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="service" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Service<i class="text-red-600">*</i></b>
                    </label>
                    <select  v-model="employe.service" @change="loardFonctionUnite" @blur="validateInputfonction" name=""
                            id="service"
                            class="rounded-md form-select">
                        <option value="" readonly>Sélectionnez le service</option>
                        <option v-for="service in services" :key="service.id" :value="service.id">
                            {{service.libelle}}
                        </option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="fonction" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Fonction<i class="text-red-600">*</i></b>
                    </label>
                    <select @blur="validateInputfonction" v-model="employe.fonction" name="" id="fonction"
                            class="rounded-md form-select">
                        <option value="" readonly>Sélectionnez la fonction</option>
                        <option v-for="fonction in fonctions" :key="fonction.id" :value="fonction.id">
                            {{fonction.libelle}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="unite" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Unité du service<i class="text-red-600">*</i></b>
                    </label>
                    <select v-model="employe.unite" name="" id="unite" class="rounded-md form-select">
                        <option value="" readonly>Sélectionnez l'unité</option>
                        <option v-for="unite in unites" :key="unite.id" :value="unite.id">
                            {{unite.libelle}}
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <br>
                    <label for="respUnite" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Responsable Unité?</b>
                    </label>
                    <input :checked="employe.respUnite===true" @click="checkRadioButton('respUnite')" type="radio" name="responsable"
                           id="respUnite" class="w-4 h-4 text-green-600">
                </div>
                <div class="col-md-3">
                    <br>
                    <label for="respService" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Responsable Service?</b>
                    </label>
                    <input :checked="employe.respService===true" @click="checkRadioButton('respService')" type="radio" name="responsable" id="respService"
                           class="w-4 h-4 text-green-600">
                </div>
            </div>
            <div class="row mb-4">

                <div class="col-md-3">
                    <label for="respDepartement" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Responsable Département?</b>
                    </label>
                    <input :checked="employe.respDepartement" @click="checkRadioButton('respDepartement')" type="radio" name="responsable"
                           id="respDepartement"
                           class="w-4 h-4 text-green-600">
                </div>
                <div class="col-md-3">
                    <label for="respAucun" class="mb-1 block text-base font-medium text-[#07074D]">
                        <b>Aucun</b>
                    </label>
                    <input @click="checkRadioButton('respAucun')" type="radio" name="responsable" id="RespAucun"
                           class="w-4 h-4 text-green-600">
                </div>
            </div>
            <div class="mt-5">
                <button :disabled="isFormButtonDisabled"
                        :class="isFormButtonDisabled?'rounded btn btn-default text-white':'rounded-md py-2 px-20 text-white align-content-center'"
                        type="submit"  class="btn-green col-md-12">
                    <b>Enregistrer</b>
                </button>
            </div>
        </form>
    </div>

</template>

<script>
    import {onMounted, reactive, ref} from "vue";
    import {useStore} from "vuex";
    import useValidateFormEmploye from "../../validation/validationFormEmploye";
    import useSubmitButtonForm from "../../validation/confirmForm";
    import useServices from "../../services/services";
    import useFonctions from "../../services/fonction";
    import useUnite from "../../services/unites";
    import useCategorieEmploye from "../../services/categorieEmploye";
    import useCategorieProfessionnelle from "../../services/categorieProfessionel";
    import useEmploye from "../../services/employeservice";
    import TitreApplication from "../block/TitreApplication";

    export default {
        name: "EmployeEdit",
        components:{TitreApplication},
        props: {
          id:{
              required:true,
              type:String
          }
        },
        setup(props) {
            const store = useStore()
            const isAvatar = ref(false);
            const checkRadioButton = (fieldName) => {
                switch (fieldName) {
                    case 'respUnite':
                        employe.respUnite = true;
                        employe.respService = false;
                        employe.respDepartement = false;
                        employe.respAucun = false;
                        break;
                    case 'respDepartement':
                        employe.respDepartement = true;
                        employe.respService = false;
                        employe.respUnite = false;
                        employe.respAucun = false;
                        break;
                    case 'respAucun':
                        employe.respAucun = true;
                        employe.respDepartement = false;
                        employe.respUnite = false;
                        employe.respService = false;
                        break;
                    case 'respService':
                        employe.respService = true;
                        employe.respDepartement = false;
                        employe.respUnite = false;
                        employe.respAucun = false;
                        break;
                }

            }

            const onchange = (e) => {
                employe.pathFile = e.target.files[0];
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#previewHolder').attr('src', e.target.result);
                }
                isAvatar.value = false;
                reader.readAsDataURL(e.target.files[0]);
            }
            // const {} = useCategorieProfessionnelle();
           /* const {categorieEmployes, getCategorieEmployes} = useCategorieEmploye();
            const {services, getServices} = useServices();*/

            const {tabError,dataSQl,employe,fonctions,unites,services,categorieEmployes,categorieProfessionelles,getFonctionByIdService,getUniteByIdService,getCategorieProfessionnellesByCategorie,getIdCategorieService,getEmploye,updateEmploye} = useEmploye();
            //onMounted(getEmploye(props.id));
            onMounted(()=>{
                getIdCategorieService(props.id);
            });
            onMounted(()=>{
               /* getCategorieEmployes;
                getServices*/
            })
            /*onMounted(()=>{
                console.log(employe,categorieProfessionelles.value)
               /!* getFonctionByIdService(5)
                getUniteByIdService(5)
                getCategorieProfessionnellesByCategorie(10)*!/
            });*/

            const {Errors, validateInputMatricule, validateInputNom, validateInputPrenoms, validateInputemail, validateInputsexe, validateInputdateEmbauche, validateInputfonction, validateInputunite, validateInputTelephone} = useValidateFormEmploye(employe);

            const {isFormButtonDisabled} = useSubmitButtonForm(employe, Errors);

            const loardFonctionUnite = () => {
                employe.fonction ='';
                employe.unite = '';
                getFonctionByIdService(employe.service)
                getUniteByIdService(employe.service)
            }

            const loardCatgorieProf = () => {
                employe.categorieProf = '';
                getCategorieProfessionnellesByCategorie(employe.categorie)
            }

            const storeRessource = async () => {
                const formData = new FormData();
                formData.set('pathFile', employe.pathFile)
                formData.set('matricule', employe.matricule);
                formData.set('nom', employe.nom);
                formData.set('prenoms', employe.prenoms);
                formData.set('respService', employe.respService);
                formData.set('respDepartement', employe.respDepartement);
                formData.set('respUnite', employe.respUnite);
                formData.set('fonction', employe.fonction);
                formData.set('unite', employe.unite);
                formData.set('telephone', employe.telephone);
                formData.set('email', employe.email);
                formData.set('sexe', employe.sexe);
                formData.set('dateEmbauche', employe.dateEmbauche);
                formData.set('categorieProf', employe.categorieProf);
                await updateEmploye(formData)
                employe.matricule = '';
            }

            return {
                store,
                dataSQl,
                isAvatar,
                tabError,
                Errors,
                isFormButtonDisabled,
                employe,
                fonctions,
                services,
                unites,
                categorieEmployes,
                categorieProfessionelles,
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
                validateInputTelephone
            }
        }
    }
</script>

<style scoped>

</style>
