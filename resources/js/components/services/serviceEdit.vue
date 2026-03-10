<template>
    <titre-application big-title="MODIFICATION SERVICES" small-title="Formulaire-Création" name-page="Service"></titre-application>
    <div class="container flex items-center justify-center p-12">
        <div class="container">
            <div class="container offset-2 col-md-8">
                <div class="alert alert-danger" v-if="tabError">
                    <strong>{{ tabError }}</strong>
                </div>
                <form method="POST" @submit.prevent="editService">
                    <div class="mb-4">
                        <label for="code" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Direction<i class="text-red-600">*</i></b>
                        </label>
                        <select @change="changeDirection" v-model="service.direction" name="" id="" class="rounded-md form-control">
                            <option value="" readonly>Sélectionnez la direction</option>
                            <option v-for="direction in directions" :key="direction.id" :value="direction.id">
                                {{direction.libelle}}
                            </option>
                        </select>
                    </div>

                    <div v-if="form.departementUsine" class="mb-4">
                        <label for="categorieEmploye" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Département<i class="text-red-600">*</i></b>
                        </label>
                        <select v-model="service.departement" name="" id="categorieEmploye"
                                class="rounded-md form-control">
                            <option value="" readonly>Sélectionnez le département</option>
                            <option value="aucun" readonly>Pas de pole</option>
                            <option v-for="departement in departements" :key="departement.id" :value="departement.id">
                                {{departement.libelle}}
                            </option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="code" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Code<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.code?'is-invalid':''" @keyup="validateIputcode" @blur="validateIputcode"
                               v-model="service.code" type="text" name="code" id="code" placeholder="code de la direction"
                               class="w-full rounded-md form-control  bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.code" class="text-red-600">{{ Errors.code }}</strong>
                    </div>
                    <div class="mb-4">
                        <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                           <b>Libelle<i class="text-red-600">*</i></b>
                        </label>
                        <input :class="Errors.libelle?'is-invalid':''" @keyup="validateInputlibelle"
                               @blur="validateInputlibelle" v-model="service.libelle" type="text" name="libelle"
                               id="libelle" placeholder="intiutlé de la direction"
                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                        <strong v-if="Errors.libelle" class="text-red-600">{{Errors.libelle }}</strong>
                    </div>
                    <div class="mt-5">
                        <button :disabled="isFormButtonDisabled"
                                :class="isFormButtonDisabled?'rounded btn btn-default text-white':'rounded-md py-2 px-20 text-white align-content-center'"
                                type="submit" class="btn-green col-md-12">
                            <b id="btn-submit">Modifier</b>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import {onMounted,reactive} from "vue";
    import useFormValidation from "../../validation/useFormValidation";
    import confirmForm from "../../validation/confirmForm";
    import useServices from "../../services/services";
    import useDepartement from "../../services/departements";
    import useUpdateVariable from "../../services/cleanVariable";
    import TitreApplication from "../block/TitreApplication";
    import useDirection from "../../services/directionServices";
    import {useStore} from "vuex";

    export default {
        name: "serviceEdit",
        components:{TitreApplication},
        props: {
            id: {
                required: true,
                type: String,
            }
        },
        setup(props) {
            let form = reactive({
                direction:'',
                departementUsine: false
            })
            const  sleep = (ms)=> {
                return new Promise(resolve => setTimeout(resolve, ms));
            }

            const store = useStore();
            const {service,tabError,getService,updateService} = useServices();
            onMounted(() => {
                getService(props.id)
                sleep(1100).then(() => {
                    if (service.codeDirection === 'DU'){
                        form.departementUsine = true;
                    }else {
                        form.departementUsine = false;
                    }

                })
            })
            const {validateNameField, validateLength, Errors} = useFormValidation();
            const {isFormButtonDisabled} = confirmForm(service, Errors);

            const {cleanVar} = useUpdateVariable(Errors,service);
            onMounted(cleanVar);

            const {directions,getDirections,direction,getDirection} = useDirection();

            onMounted(getDirections);


            const validateIputcode = () => {
                (service.code === '' ? validateNameField('code', service.code) :
                    validateLength('code', service.code, store.state.MAX_LENGTH))
            }

            const validateInputlibelle = () => {
                validateNameField('libelle', service.libelle)
            }
            const {departements,getDepartements} = useDepartement();
            onMounted(getDepartements);
            const editService = async () => {
                $('#btn-submit').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                await updateService(props.id)
                service.code = '';
            }

            const changeDirection = async ()=>{
                await getDirection(service.direction)
                sleep(80).then(() => {
                    if (direction.code ==='DU'){
                        form.departementUsine = true;
                    }else {
                        form.departementUsine = false;
                    }

                })
            }

            return {
                Errors,
                tabError,
                service,
                direction,
                departements,
                directions,
                isFormButtonDisabled,
                form,
                validateIputcode,
                validateInputlibelle,
                editService,
                changeDirection,
            }
        }
    }
</script>

<style scoped>

</style>
