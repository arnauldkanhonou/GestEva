import {ref} from "vue";
import {reactive} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";
import appService from "./appService";
import Swal from "sweetalert2";
import directionServices from "./directionServices";

export default function useServices() {
    const errors = ref('');
    const tabError = ref('');
    const countData = ref([]);
    const countPage = ref([]);
    let service= reactive({id:'', code:'', libelle:'',departement:'',direction:'',codeDirection:''});
    const services = ref([]);
    const succesMessage = ref('');
    const router = useRouter();
    const {catchErrors,loadRessource}= appService();
    const {getDirection,direction}= directionServices();

    const getService = async (id) =>{
       axios.get('services/'+ id)
            .then((response)=>{
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    service.id = response.data.data.id;
                    service.code = response.data.data.code;
                    service.libelle = response.data.data.libelle;
                    service.departement = response.data.data.departement_id
                    service.direction = response.data.data.direction_id;
                    service.codeDirection = response.data.data.codeDirection
                }

            })
            .catch((error)=>{
                catchErrors(error,succesMessage);
            });
       ;
    }

    const getServices = async () => {
        loadRessource.value = false;
      axios.get('services')
            .then((response)=>{
                if (response.data.data.code ===500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    services.value = response.data.data;
                    loadRessource.value = true;
                }

            })
            .catch((error)=>{
                catchErrors(error,succesMessage);
            });

    }

    const createService = async (data)=> {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.post('services', data)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    $('#btn-save').html('Enregistrer')
                    succesMessage.value = 'Un service est enregistré dans la base avec succès !'
                    router.push({name: 'services.index', params: {messages: succesMessage.value}});
                }
            })
            .catch(error => {
            $('#btn-save').html('Enregistrer')
            let createCustomerErrors = error.response.data.errors;
            if (error.response.status === 422) {
                for (const key in createCustomerErrors) {
                    tabError.value += error.response.data.errors[key][0] + '| ';
                }
            }
                catchErrors(error,succesMessage);

        })
    }

    const updateService = async (id)=> {
        errors.value = '';
        tabError.value = '';
        succesMessage.value = '';
        axios.patch('services/'+id, service)
            .then((response) => {
                if (response.data.data.code === 500) {
                    Swal.fire({
                        title: 'Erreur serveur',
                        text: response.data.data.message,
                        icon: 'error',
                    });
                }else {
                    succesMessage.value = 'Modification effectuée avec succès !'
                    router.push({name: 'services.index', params: {messages: succesMessage.value}});
                }
            })
            .catch(error => {
            let createCustomerErrors = error.response.data.errors;
            if (error.response.status === 422) {
                for (const key in createCustomerErrors) {
                    tabError.value += error.response.data.errors[key][0] + '| ';
                }
            }
                catchErrors(error,succesMessage);
        })
    }

    const deleteService = async (id,idbtn) => {
        axios.delete('services/'+id)
            .then(()=>{
                succesMessage.value = 'Supression effectuée avec succès.'
                getServices();
                router.push({name: 'services.index', params: {messages: succesMessage.value}})
                $('#'+idbtn)[0].click();
                $('#btn-confirm').html('OUI');
            })
            .catch((error)=>{
                catchErrors(error,succesMessage);
            });

    }

    const paginateData = (nbre, page = 1) => {
        axios.get('service/liste/' + nbre + '?page=' + page)
            .then(response => {
                services.value = response.data;
                countData.value = response.data.data;
                loadRessource.value = true;
            });
    }

    const getRessources = async (nbre = 10) => {
        loadRessource.value=false;
        await axios.get('service/liste/' + nbre)
            .then((response) => {
                services.value = response.data
                countData.value = response.data.data;
                countPage.value = response.data.meta.last_page;
                loadRessource.value = true;
            }).catch(errors => {
                catchErrors(error, succesMessage);
            });

    }

    const searchRessource = (req) => {
        loadRessource.value=false;
        if (req.length > 3) {
            axios.get('service/search/' + req)
                .then((response) => {
                    services.value = response.data;
                    countData.value = response.data.data;
                    loadRessource.value = true;
                })
                .catch(error => {
                    console.log(error);
                })
        }else {
            getRessources(10)
        }
    }

    return{
        direction,
        service,
        services,
        countData,
        countPage,
        tabError,
        errors,
        loadRessource,
        getService,
        getServices,
        createService,
        updateService,
        deleteService,
        paginateData,
        searchRessource,
        getRessources
    }
}
