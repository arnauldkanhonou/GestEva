<template>
    <div class="flex flex-col">
        <div v-if="messages!==''" class="alert alert-success">
          <strong>  {{ messages }} </strong>
        </div>
        <div>
            <div class="flex">
                <router-link style="background-color: #058069" :to="{name:'customers.create'}" class="text-decoration-none bg-green-600 px-3 py-2 text-white rounded"> Créer un
                    employé
                </router-link>
            </div>
            <div class="py-1">
                <div class="shadow  overflow-hidden">
                    <table class="min-w-full leading-normal table table-bordered table-striped" style="background-color: #556e6e">
                        <thead>
                        <tr>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200  text-left text-xs font-semibold uppercase tracking-wider">
                                <b class="text-white">Name</b>
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200  text-left text-xs font-semibold uppercase tracking-wider">
                                <b class="text-white">Teléphone</b>
                            </th>
                            <th
                                class="px-5 py-2 border-b-2 border-gray-200  text-left text-xs font-semibold uppercase tracking-wider">
                                <b class="text-white">Is Favourite?</b>
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200  text-left text-xs font-semibold uppercase tracking-wider">
                                <b class="text-white">Action</b>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="customer in customers" :key="customer.id">
                            <tr>
                                <td class="px-5 py-2 border-b border-gray-200 bg-white text-md"
                                    v-text="customer.name"></td>
                                <td class="px-5 py-2 border-b border-gray-200 bg-white text-md"
                                    v-text="customer.tel"></td>
                                <td class="px-5 py-2 border-b border-gray-200 bg-white text-md">
                                <span class="relative inline-block px-3 py-1 font-semibold rounded-full"
                                      v-bind:class="customer.is_favourite ? 'bg-green-300 text-green-800':'bg-red-300 text-red-800'"
                                      v-text="customer.is_favourite">

                                </span>
                                </td>
                                <td class="px-5 py-2 border-b border-gray-200 bg-white text-sm">
                                    <router-link style="background-color: #058069" :to="{ name: 'customers.edit', params: { id: customer.id } }"
                                                 class="text-decoration-none px-3 py-2 text-white rounded"><i class="fa fa-edit"></i></router-link>
                                    &nbsp;&nbsp;<button @click="deleteCustomer(customer.id)" class="bg-red-600 hover:bg-red-600 text-white px-3 py-2 rounded"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {onMounted} from "vue";
    import useCustomers from "../../services/customerservices.js";

    export default {
        props: {
          messages:{
              required:false,
              default:'',
          },

        },
        setup: function () {
            const {customers, getCustomers, destroyCustomer} = useCustomers();

            const deleteCustomer = async (id) => {
                if (!window.confirm('ête vous sûr de vouloir supprimer ?')) {
                    return
                }
                await destroyCustomer(id)
            }

            onMounted(getCustomers());

            return {
                customers,
                deleteCustomer,
                getCustomers,
                destroyCustomer,
            }
        }

    }

</script>
