<template>
    <titre-application big-title="CREATION SERVICES" small-title="Formulaire-Création" name-page="Service"></titre-application>
    <div class="container flex items-center justify-center p-8">
        <div class="container">
            <div class="container offset-2 col-md-8">
                <div class="alert alert-danger" v-if="tabError">
                    <strong >{{ tabError }}</strong>
                </div>
                <form method="POST" @submit.prevent="stroreService">
                    <div class="mb-4">
                        <label for="code" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Direction<i class="text-red-600">*</i></b>
                        </label>
                        <select @change="changeDirection" v-model="form.direction" name="" id="" class="rounded-md form-control">
                            <option value="" readonly>Sélectionnez le departement</option>
                            <option v-for="departement in directions" :key="departement.id" :value="departement.code">
                                {{departement.libelle}}
                            </option>
                        </select>
                    </div>
                    <div v-if="form.departementUsine" class="mb-4">
                        <label for="pole" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Pole<i class="text-red-600">*</i></b>
                        </label>
                        <select id="pole" class="rounded-md form-control">
                            <option value="" readonly>Sélectionnez le pole</option>
                            <option value="aucun" readonly>Pas de pole</option>
                            <option v-for="pole in departements" :key="pole.id" :value="pole.id">
                                {{pole.libelle}}
                            </option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="code" class="mb-1 block text-base font-medium text-[#07074D]">
                           <b> Code<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.code?'is-invalid':''" @keyup="validateIputcode" @blur="validateIputcode" v-model="form.code" type="text" name="code" id="code" placeholder="code du service"
                               class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.code" class="text-red-600">{{ Errors.code }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                           <b> Libelle<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.libelle?'is-invalid':''" @keyup="validateInputlibelle" @blur="validateInputlibelle" v-model="form.libelle"  type="text" name="libelle" id="libelle" placeholder="intiutlé du service"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.libelle" class="text-red-600">{{Errors.libelle }}</strong>
                    </div>
                    <div class="mb-4">
                        <div class="block">
                            <div class="mt-2">
                                <label class="inline-flex items-center">
                                    <input v-model="form.unite" type="checkbox" class="w-6 h-6 text-green-600 focus:ring-0" />
                                    <span class="ml-2">Créer comme unité ? </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <button :disabled="isFormButtonDisabled" :class="isFormButtonDisabled?'rounded btn btn-default text-white':'rounded-md py-2 px-20 text-white align-content-center'" type="submit"  class="btn-green col-md-12">
                            <b id="btn-save">Enregistrer</b>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import {onMounted, reactive} from "vue";
    import useFormValidation from "../../validation/useFormValidation";
    import confirmForm from "../../validation/confirmForm";
    import useServices from "../../services/services";
    import useDirection from "../../services/directionServices";
    import useDepartement from "../../services/departements";
    import useUpdateVariable from "../../services/cleanVariable";
    import TitreApplication from "../block/TitreApplication";
    import {useStore} from "vuex";

    export default {
        name: "serviceCreate",
        components:{TitreApplication},
        setup(){
            const form = reactive({
                code:'',
                libelle:'',
                direction:'',
                unite:false,
                departementUsine: false,
            })
            const store = useStore();
            const {validateNameField,validateLength,Errors} = useFormValidation();

            const {cleanVar} = useUpdateVariable(Errors,form);
            onMounted(cleanVar);

            const {isFormButtonDisabled} = confirmForm(form,Errors);

            const {tabError,createService} = useServices();

            const {directions,getDirections} = useDirection();
            const {departements,getDepartements} = useDepartement();

            onMounted(getDirections);

            onMounted(getDepartements);

            const validateIputcode = ()=>{
                (form.code ===''?validateNameField('code',form.code):
                    validateLength('code',form.code,store.state.MAX_LENGTH))
            }
            const validateInputlibelle = ()=>{
                validateNameField('libelle',form.libelle)
            }


            const stroreService = async () =>{
                let data = {
                    code: form.code,
                    libelle: form.libelle,
                    direction: form.direction,
                    unite: form.unite,
                    departementUsine: form.departementUsine,
                    pole: $('#pole').val(),
                }
                $('#btn-save').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
                await createService(data)
                form.code = '';
            }

            const changeDirection = async ()=>{
                if (form.direction ==='DU'){
                    form.departementUsine = true;
                }else {
                    form.departementUsine = false;
                }
            }

            return{
                form,
                tabError,
                Errors,
                isFormButtonDisabled,
                validateIputcode,
                validateInputlibelle,
                stroreService,
                directions,
                departements,
                changeDirection
            }
        }
    }
</script>

<style scoped>

</style>
