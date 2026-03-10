<template>
    <titre-application big-title="IMPORTATION DES DONNEES" small-title="Formulaires" name-page="Importations"></titre-application>
    <div class="container flex items-center justify-center p-8">
        <div class="offset-1 col-md-10 ">
            <div class="alert alert-danger" v-if="messageError!==''">
                <strong>{{ messageError }}</strong>
            </div>
            <div class="mb-4">
                <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                    <b class="text-red-600">Imporation des salariés<i class="text-red-600">*</i></b>
                </label>
            </div>
            <div class="row">
                <div class="col-md-9">
                    <input @change="onchange"  type="file" accept=".csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" name="libelle" id="libelle"
                           class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                </div>
                <div class="col-md-3">
                    <button id="btnImport" @click="importSalarie()" class="bg-green-600 py-2 px-2 text-white align-content-center" type="submit">
                        <b id="btn-add"><i class="fa fa-download"></i> Importer</b>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import TitreApplication from "../block/TitreApplication";
    import {reactive, ref} from "@vue/reactivity";
    import axios from "axios"
    import Swal from "sweetalert2";

    export default {
        name: "importation",
        components:{TitreApplication},

        setup(){
            const form = reactive({
                pathFile:'',
            })
            const messageError = ref('');
            const onchange = (e) => {
               form.pathFile = e.target.files[0];
            }

            const importSalarie = ()=>{
                $('#btnImport').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
                const formData = new FormData();
                formData.set('uploaded_file', form.pathFile);
                axios.post('importation/salaries',formData)
                .then((response)=>{
                    if (response.data.data.code===500){
                        Swal.fire({
                            title: 'Erreur serveur',
                            text: response.data.data.message,
                            icon: 'error',
                        });
                    }else {
                        if (response.data.data.code===400){
                            messageError.value = response.data.data.message;
                        }else {
                            $('#btnImport').html(' <b><i class="fa fa-download"></i> Importer</b>')
                            Swal.fire({
                                title: 'FELICITATION',
                                text: response.data.data.message,
                                icon: 'success',
                            });
                        }
                    }

                })
            }

            return{
                onchange,
                importSalarie,
                messageError
            }
        }
    }
</script>

<style scoped>

</style>
