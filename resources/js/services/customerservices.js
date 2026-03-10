import {ref} from "vue";
import axios from "axios";
import {useRouter} from 'vue-router'
import {useStore} from "vuex";

export default function useCustomers() {
    const errors = ref('');
    const tabError = ref([]);
    const customers = ref([]);
    const customer = ref([]);
    const succesMessage = ref('');
    const router = useRouter();
    const store = useStore();

    const getCustomers = async () => {
        let response = await axios.get('api/customers');
        customers.value = response.data.data
    }

    const getCustomer = async (id) => {
        let response = await axios.get(`/api/customers/${id}`)
        customer.value = response.data.data;
    }

    const createCustomer = async (data) => {
        errors.value = '';
        tabError.value = [];
        succesMessage.value = '';
        axios.post('/api/customers', data)
            .then((response) => {
                if (response.data.code === 500) {

                  }else {
                    succesMessage.value = 'L\'employé ' + data.name + ' est enregistré dans la base avec succès !'
                    router.push({name: 'customers.index', params: {messages: succesMessage.value}});
                }
            }).catch(error => {
                //errors.value =error.response.data.errors;
                let createCustomerErrors = error.response.data.errors;
                // await store.dispatch('setError', error.response.data.errors);
                if (error.response.status === 422) {
                    for (const key in createCustomerErrors) {
                        errors.value += error.response.data.errors[key][0] + '| ';
                    }
                }
        })
    }

    const updateCustomer = async (id) => {
        errors.value = '';
        succesMessage.value = '';
        try {
            await axios.patch(`/api/customers/${id}`, customer.value);
            succesMessage.value = 'Les informations de ' + customer.value.name + ' ont été modifiées avec succès !'
            await router.push({name: 'customers.index', params: {messages: succesMessage.value}})
        } catch (e) {
            if (e.response.status === 422) {
                for (const key in e.response.data.errors) {
                    errors.value += e.response.data.errors[key][0] + '; ';
                }
            }
        }
    }

    const destroyCustomer = async (id) => {
        await axios.delete(`/api/customers/${id}`);
        succesMessage.value = 'Un employé a été supprimé avec succès!'
        getCustomers();
        await router.push({name: 'customers.index', params: {messages: succesMessage.value}})
    }

    return {
        customers,
        customer,
        errors,
        succesMessage,
        tabError,
        getCustomer,
        getCustomers,
        createCustomer,
        updateCustomer,
        destroyCustomer
    }
}
