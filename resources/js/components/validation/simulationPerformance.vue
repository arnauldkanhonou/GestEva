<template>
    <div>
        <div class="flex flex-col">
            <titre-application big-title="FICHE-DE-NOTE-PONDEREE" small-title="Simulation" name-page="Performances"/>
            <div class="row">
                <div class="row">
                    <div class="offset-6 col-md-3 text-danger">
                        <strong style="font-size: 18px">ANNEE PERFORMANCE : {{ anneePerformance }}</strong>
                    </div>
                    <div class="col-md-3 text-danger">
                        <strong style="font-size: 18px">PERFORMANCE ENTRPRISE : {{ performanceUsine }}</strong>
                    </div>
                </div>

                <div v-show="load" class="mt-4 container">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" v-for="(tab, index) in tabs" :key="tab.id">
                            <a
                                class="nav-link"
                                :class="{ active: index === 0 }"
                                :id="`${tab.id}-tab`"
                                data-toggle="tab"
                                :href="`#${tab.id}`"
                                role="tab"
                                :aria-controls="tab.id"
                                :aria-selected="index === 0"
                            >{{ tab.label }}</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div
                            v-for="(tab, index) in tabs"
                            :key="`panel-${tab.id}`"
                            class="tab-pane"
                            :class="{ active: index === 0 }"
                            :id="tab.id"
                            role="tabpanel"
                            :aria-labelledby="`${tab.id}-tab`"
                        >
                            <div class="filter-zone">
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <label class="font-weight-bold mb-1">Recherche globale</label>
                                        <input v-model="filters[tab.id].global" type="text" class="form-control" placeholder="Rechercher dans toutes les colonnes"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mb-2" v-for="column in filterableColumns" :key="`${tab.id}-${column.key}`">
                                        <label class="font-weight-bold mb-1">{{ column.label }}</label>
                                        <input v-model="filters[tab.id].columns[column.key]" type="text" class="form-control" :placeholder="`Filtrer ${column.label.toLowerCase()}`"/>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table style="border: white 2px solid; font-size: 13px;" class="w-100">
                                    <thead style="background-color: #057e72;color: white" class="uppercase">
                                    <tr>
                                        <td style="width: 5%; border: 1px solid black;padding: 9px">Matr.</td>
                                        <td style="width: 18%; border: 1px solid black;padding: 9px">Salarié</td>
                                        <td style="width: 15%; border: 1px solid black;padding: 9px">Poste</td>
                                        <td style="width: 10%; border: 1px solid black;padding: 9px">Service</td>
                                        <td style="width: 5%; border: 1px solid black;padding: 9px">Cat.</td>
                                        <td style="width: 8%; border: 1px solid black;padding: 9px">Note Obt.</td>
                                        <td style="width: 8%; border: 1px solid black;padding: 9px">Perfor.</td>
                                        <td style="width: 8%; border: 1px solid black;padding: 9px">Note Pondérée</td>
                                        <td style="width: 15%; border: 1px solid black;padding: 9px">Perf.Apr.Pondé.</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr
                                        v-for="val in filteredRows[tab.id]"
                                        :key="`${tab.id}-${val.id}-${val.matricule}`"
                                        style="border: black 1px solid"
                                        :class="val.beneficiaire ? 'alert-primary' : val.havePrimeExcept ? 'alert-warning' : ''"
                                    >
                                        <td style="width: 5%; border: 1px solid black;padding: 8px"><b>{{ val.matricule }}</b></td>
                                        <td style="width: 18%; border: 1px solid black;padding: 8px"><b>{{ val.salarie }}</b></td>
                                        <td style="width: 15%; border: 1px solid black;padding: 8px"><b>{{ val.poste }}</b></td>
                                        <td style="width: 10%; border: 1px solid black;padding: 8px"><b>{{ val.service }}</b></td>
                                        <td style="width: 5%; border: 1px solid black;padding: 8px"><b>{{ val.cateprofe }}</b></td>
                                        <td style="width: 8%; border: 1px solid black;padding: 8px"><b>{{ val.performanceRealiser }}</b></td>
                                        <td style="width: 8%; border: 1px solid black;padding: 8px"><b>{{ val.niveauPerf }}</b></td>
                                        <td style="width: 8%; border: 1px solid black;padding: 8px"><b :style="val.notePondere > 0 ? 'color: green' : ''">{{ val.performanceFinal }}</b></td>
                                        <td style="width: 15%; border: 1px solid black;padding: 8px">
                                            <b>{{ val.niveauPerfApresPonde }}</b>
                                            <template v-if="!listeBeneficiaireValider && tab.id !== 'laureats'">
                                                &nbsp;&nbsp;
                                                <button
                                                    v-if="!val.beneficiaire && !val.havePrimeExcept"
                                                    @click="defineBeneficiairePrime(val)"
                                                    type="button"
                                                    :id="'btnDefinePrimeExcept' + val.id"
                                                    style="background-color: green; color: white;padding: 2px;"
                                                    title="choisir pour prime exceptionnelle"
                                                >
                                                    <i class="fa fa-plus-circle"></i>
                                                </button>
                                                <button
                                                    @click="removeBeneficiairePrime(val)"
                                                    :id="'btnRemovePrimeExcept' + val.id"
                                                    type="button"
                                                    v-if="val.havePrimeExcept"
                                                    style="background-color: red; color: white;padding: 2px;"
                                                >
                                                    <i class="fa fa-minus-circle"></i>
                                                </button>
                                                <i hidden :id="'loadPrimeExcept' + val.id" class="fa fa-spinner fa-spin fa-2x"></i>
                                            </template>
                                        </td>
                                    </tr>
                                    <tr v-if="!filteredRows[tab.id].length">
                                        <td colspan="9" class="text-center" style="border: 1px solid black;padding: 12px">
                                            <b>Aucun employé trouvé avec les filtres saisis.</b>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-right mt-2 mb-3">
                                <strong>
                                    Effectif affiché : {{ filteredRows[tab.id].length }} / {{ sourceRows[tab.id].length }}
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-show="!load">
                    <div class="col-md-2 offset-5 mt-4">
                        <strong style="font-size: 20px;color: #057e72;" class="mb-0">Chargement...</strong><br>
                        <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TitreApplication from "../block/TitreApplication";
import {computed, onMounted, reactive, ref} from "vue";
import axios from "axios";
import useDirection from "../../services/directionServices";
import appService from "../../services/appService";
import {useStore} from "vuex";

const createFilterState = () => ({
    global: '',
    columns: {
        matricule: '',
        salarie: '',
        poste: '',
        service: '',
        cateprofe: '',
        performanceRealiser: '',
        niveauPerf: '',
        performanceFinal: '',
        niveauPerfApresPonde: ''
    }
})

const toSearchValue = (value) => (value === null || value === undefined ? '' : String(value).toLowerCase())

export default {
    name: "simulationPerformance",
    components: {TitreApplication},
    setup() {
        const {directions} = useDirection();
        const {catchErrors} = appService();
        const succesMessage = ref('')
        const store = useStore();

        onMounted(() => {
            $(function () {
                $('#myTab a').on('click', function (e) {
                    e.preventDefault()
                    $(this).tab('show')
                })

                $('#myTab a[href="#cadres"]').tab('show')
            })
            store.state.MIX_API_URL;
            getPerformanceGlobale()
        })

        const tabs = [
            {id: 'cadres', label: 'Cadres'},
            {id: 'maitrises', label: 'Maitrises'},
            {id: 'executants', label: 'Executants'},
            {id: 'laureats', label: 'Lauréats'}
        ]

        const filterableColumns = [
            {key: 'matricule', label: 'Matr.'},
            {key: 'salarie', label: 'Salarié'},
            {key: 'poste', label: 'Poste'},
            {key: 'service', label: 'Service'},
            {key: 'cateprofe', label: 'Catégorie'},
            {key: 'performanceRealiser', label: 'Note Obt.'},
            {key: 'niveauPerf', label: 'Perfor.'},
            {key: 'performanceFinal', label: 'Note Pondérée'},
            {key: 'niveauPerfApresPonde', label: 'Perf.Apr.Pondé.'}
        ]

        const form = reactive({
            annee: '',
            type: '',
            datas: [],
            direction_id: '',
            service_id: '',
            services: [],
            currentEval: '',
            cadres: [],
            maitrises: [],
            executants: []
        })

        const filters = reactive({
            cadres: createFilterState(),
            maitrises: createFilterState(),
            executants: createFilterState(),
            laureats: createFilterState()
        })

        const load = ref(false)
        const listeBeneficiaireValider = ref(false)
        const performanceUsine = ref(0)
        const anneePerformance = ref(0)

        const normalizeRows = (liste) => {
            const rows = []
            const pushRows = (group) => {
                if (Array.isArray(group)) {
                    group.forEach((item) => {
                        if (item && typeof item === 'object') {
                            rows.push({
                                ...item,
                                salarie: `${item.nom || ''} ${item.prenoms || ''}`.trim()
                            })
                        }
                    })
                    return
                }
                if (group && typeof group === 'object') {
                    Object.values(group).forEach(pushRows)
                }
            }

            if (Array.isArray(liste)) {
                liste.forEach(pushRows)
            } else {
                pushRows(liste)
            }

            return rows
        }

        const sourceRows = computed(() => {
            const cadres = normalizeRows(form.cadres)
            const maitrises = normalizeRows(form.maitrises)
            const executants = normalizeRows(form.executants)
            return {
                cadres,
                maitrises,
                executants,
                laureats: [...cadres, ...maitrises, ...executants].filter((item) => item.beneficiaire)
            }
        })

        const applyFilters = (rows, state) => {
            const globalFilter = toSearchValue(state.global).trim()
            return rows.filter((row) => {
                const matchGlobal = !globalFilter || filterableColumns.some(({key}) => toSearchValue(row[key]).includes(globalFilter))
                if (!matchGlobal) {
                    return false
                }
                return filterableColumns.every(({key}) => {
                    const columnFilter = toSearchValue(state.columns[key]).trim()
                    return !columnFilter || toSearchValue(row[key]).includes(columnFilter)
                })
            })
        }

        const filteredRows = computed(() => ({
            cadres: applyFilters(sourceRows.value.cadres, filters.cadres),
            maitrises: applyFilters(sourceRows.value.maitrises, filters.maitrises),
            executants: applyFilters(sourceRows.value.executants, filters.executants),
            laureats: applyFilters(sourceRows.value.laureats, filters.laureats)
        }))

        const getPerformanceGlobale = (from = '') => {
            if (from === '') {
                load.value = false
            }

            axios.get('get/liste/notepondere')
                .then((response) => {
                    load.value = true
                    form.cadres = response.data.liste.AC
                    form.maitrises = response.data.liste.AM
                    form.executants = response.data.liste.AE
                    performanceUsine.value = response.data.performanceUsine
                    anneePerformance.value = response.data.annee
                    listeBeneficiaireValider.value = response.data.listeBeneficiaireValider
                })
        }

        const getPerformanceService = () => {
            load.value = false
            axios.get('get/notepondere/service/' + form.service_id)
                .then((response) => {
                    load.value = true
                    form.datas = response.data.liste
                    performanceUsine.value = response.data.performanceUsine
                })
        }

        const getServiceByDirection = () => {
            axios.get('service/direction/' + form.direction_id + '/' + true)
                .then((response) => {
                    if (response.data.data.code !== 500) {
                        form.services = response.data.data.services
                    }
                }).catch((error) => {
                catchErrors(error, succesMessage)
            })
        }

        const defineBeneficiairePrime = (item) => {
            $('#btnDefinePrimeExcept' + item.id).attr('hidden', 'hidden')
            $('#loadPrimeExcept' + item.id).removeAttr('hidden')

            axios.post('definir/beneficiaire/primeexcept', item)
                .then(() => {
                    getPerformanceGlobale('defineprime')
                    $('#loadPrimeExcept' + item.id).attr('hidden', 'hidden')
                }).catch((error) => {
                catchErrors(error, succesMessage)
            })
        }

        const removeBeneficiairePrime = (item) => {
            $('#btnRemovePrimeExcept' + item.id).attr('hidden', 'hidden')
            $('#loadPrimeExcept' + item.id).removeAttr('hidden')

            axios.post('remove/beneficiaire/primeexcept', item)
                .then(() => {
                    getPerformanceGlobale('removeprime')
                    $('#loadPrimeExcept' + item.id).attr('hidden', 'hidden')
                }).catch((error) => {
                catchErrors(error, succesMessage)
            })
        }

        return {
            directions,
            tabs,
            filterableColumns,
            filters,
            filteredRows,
            sourceRows,
            form,
            load,
            performanceUsine,
            listeBeneficiaireValider,
            anneePerformance,
            getPerformanceGlobale,
            getServiceByDirection,
            getPerformanceService,
            defineBeneficiairePrime,
            removeBeneficiairePrime
        }
    }
}
</script>

<style scoped>
.filter-zone {
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 12px;
    margin: 12px 0;
    background: #f8fafc;
}
</style>
