<template>
    <div class="container flex items-center justify-center p-12">
        <div class="mx-auto w-full max-w-[550px]">
            <div class="alert alert-danger" v-if="errors">
                <strong>{{ errors }}</strong>
            </div>
            <form method="POST" @submit.prevent="saveCustomer">
                <div class="mb-5">
                    <label for="name" class="mb-3 block text-base font-medium text-[#07074D]">
                        Nom du client
                    </label>
                    <input v-model="customer.name" type="text" name="name" id="name" placeholder="Full Name"
                           class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                    />
                </div>
                <div class="mb-5">
                    <label for="email" class="mb-3 block text-base font-medium text-[#07074D]">
                        Téléphone
                    </label>
                    <input v-model="customer.tel"  type="text" name="tel" id="email" placeholder="example@domain.com"
                           class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                    />
                </div>
                <div class="mb-5">
                    <label class="mb-3 block text-base font-medium text-[#07074D]">
                        Fovour ?
                    </label>
                    <input v-model="customer.is_favourite"  type="checkbox" :checked="customer.is_favourite"
                           class=" rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium"/>
                </div>
                <div>
                    <button type="submit"
                            class="rounded-md bg-green-600 py-2 px-20 text-white align-content-center">
                        Modifier
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import useCustomers from "../../services/customerservices";
    import { onMounted } from "vue";

    export default {
        name: "CustomerEdit",
        props: {
            id: {
                required: true,
                type: String
            }
        },
        setup(props) {
            const { errors, customer, updateCustomer, getCustomer } = useCustomers()

            onMounted(() => getCustomer(props.id))

            const saveCustomer = async () => {
                await updateCustomer(props.id)
            }

            return {
                errors,
                customer,
                getCustomer,
                saveCustomer
            }
        }
    }
</script>

<style scoped>

</style>
