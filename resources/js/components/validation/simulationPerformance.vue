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
                            <div class="d-flex justify-content-end mt-3">
                                <button class="btn btn-outline-secondary btn-sm" type="button" @click="showFilters = !showFilters">
                                    {{ showFilters ? 'Masquer les filtres' : 'Afficher les filtres' }}
                                </button>
                            </div>

                            <div v-show="showFilters" class="filter-zone">
                                <div class="row align-items-end">
                                    <div class="col-md-5 mb-2">
                                        <label class="font-weight-bold mb-1">Recherche globale (Salarié)</label>
                                        <input
                                            v-model="filters[tab.id].global"
                                            type="text"
                                            class="form-control"
                                            placeholder="Rechercher un salarié"
                                        />
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="font-weight-bold mb-1">Tri par Salarié</label>
                                        <select v-model="filters[tab.id].salarieSort" class="form-control">
                                            <option value="">Aucun tri</option>
                                            <option value="asc">A → Z</option>
                                            <option value="desc">Z → A</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="font-weight-bold mb-1">Tri par Note pondérée</label>
                                        <button class="btn btn-outline-primary btn-block" type="button" @click="toggleNoteSort(tab.id)">
                                            {{ noteSortLabel(tab.id) }}
                                        </button>
                                    </div>
                                </div>
                                <div class="row align-items-end mt-1">
                                    <div class="col-md-4 mb-2">
                                        <label class="font-weight-bold mb-1">Service</label>
                                        <select v-model="filters[tab.id].service" class="form-control">
                                            <option value="">Tous</option>
                                            <option v-for="service in selectOptions[tab.id].services" :key="`${tab.id}-service-${service}`" :value="service">
                                                {{ service }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="font-weight-bold mb-1">Catégorie</label>
                                        <select v-model="filters[tab.id].cateprofe" class="form-control">
                                            <option value="">Toutes</option>
                                            <option v-for="categorie in selectOptions[tab.id].categories" :key="`${tab.id}-cat-${categorie}`" :value="categorie">
                                                {{ categorie }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="font-weight-bold mb-1">Poste</label>
                                        <select v-model="filters[tab.id].poste" class="form-control">
                                            <option value="">Tous</option>
                                            <option v-for="poste in selectOptions[tab.id].postes" :key="`${tab.id}-poste-${poste}`" :value="poste">
                                                {{ poste }}
                                            </option>
                                        </select>
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
                                <strong>Effectif affiché : {{ filteredRows[tab.id].length }} / {{ sourceRows[tab.id].length }}</strong>
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

const createFilterState = () => ({
    global: '',
    salarieSort: '',
    noteSort: '',
    service: '',
    cateprofe: '',
    poste: ''
})

const toSearchValue = (value) => (value === null || value === undefined ? '' : String(value).toLowerCase())

const toNumber = (value) => {
    const num = Number(value)
    return Number.isNaN(num) ? 0 : num
}

export default {
    name: "simulationPerformance",
    components: {TitreApplication},
    setup() {
        const {directions} = useDirection();
        const {catchErrors} = appService();
        const succesMessage = ref('')

        const tabs = [
            {id: 'cadres', label: 'Cadres'},
            {id: 'maitrises', label: 'Maitrises'},
            {id: 'executants', label: 'Executants'},
            {id: 'laureats', label: 'Lauréats'}
        ]

        const showFilters = ref(false)

        onMounted(() => {
            $(function () {
                $('#myTab a').on('click', function (e) {
                    e.preventDefault()
                    $(this).tab('show')
                })

                $('#myTab a[href="#cadres"]').tab('show')
            })
            getPerformanceGlobale()
        })

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

        const collectOptions = (rows, key) => {
            const values = Array.from(new Set(rows.map((item) => (item[key] || '').toString().trim()).filter((value) => value !== '')))
            return values.sort((a, b) => a.localeCompare(b, 'fr', {sensitivity: 'base'}))
        }

        const selectOptions = computed(() => ({
            cadres: {
                services: collectOptions(sourceRows.value.cadres, 'service'),
                categories: collectOptions(sourceRows.value.cadres, 'cateprofe'),
                postes: collectOptions(sourceRows.value.cadres, 'poste')
            },
            maitrises: {
                services: collectOptions(sourceRows.value.maitrises, 'service'),
                categories: collectOptions(sourceRows.value.maitrises, 'cateprofe'),
                postes: collectOptions(sourceRows.value.maitrises, 'poste')
            },
            executants: {
                services: collectOptions(sourceRows.value.executants, 'service'),
                categories: collectOptions(sourceRows.value.executants, 'cateprofe'),
                postes: collectOptions(sourceRows.value.executants, 'poste')
            },
            laureats: {
                services: collectOptions(sourceRows.value.laureats, 'service'),
                categories: collectOptions(sourceRows.value.laureats, 'cateprofe'),
                postes: collectOptions(sourceRows.value.laureats, 'poste')
            }
        }))

        const applyFiltersAndSort = (rows, state) => {
            const globalFilter = toSearchValue(state.global).trim()
            const serviceFilter = toSearchValue(state.service).trim()
            const categorieFilter = toSearchValue(state.cateprofe).trim()
            const posteFilter = toSearchValue(state.poste).trim()

            const filtered = rows.filter((row) => {
                const bySalarie = !globalFilter || toSearchValue(row.salarie).includes(globalFilter)
                const byService = !serviceFilter || toSearchValue(row.service) === serviceFilter
                const byCategorie = !categorieFilter || toSearchValue(row.cateprofe) === categorieFilter
                const byPoste = !posteFilter || toSearchValue(row.poste) === posteFilter
                return bySalarie && byService && byCategorie && byPoste
            })

            if (state.salarieSort === 'asc' || state.salarieSort === 'desc') {
                const order = state.salarieSort === 'asc' ? 1 : -1
                filtered.sort((a, b) => toSearchValue(a.salarie).localeCompare(toSearchValue(b.salarie), 'fr', {sensitivity: 'base'}) * order)
            }

            if (state.noteSort === 'desc' || state.noteSort === 'asc') {
                const order = state.noteSort === 'desc' ? -1 : 1
                filtered.sort((a, b) => (toNumber(a.performanceFinal) - toNumber(b.performanceFinal)) * order)
            }

            return filtered
        }

        const filteredRows = computed(() => ({
            cadres: applyFiltersAndSort(sourceRows.value.cadres, filters.cadres),
            maitrises: applyFiltersAndSort(sourceRows.value.maitrises, filters.maitrises),
            executants: applyFiltersAndSort(sourceRows.value.executants, filters.executants),
            laureats: applyFiltersAndSort(sourceRows.value.laureats, filters.laureats)
        }))

        const noteSortLabel = (tabId) => {
            if (filters[tabId].noteSort === 'desc') {
                return 'Tri note pondérée : du plus grand au plus petit'
            }
            if (filters[tabId].noteSort === 'asc') {
                return 'Tri note pondérée : du plus petit au plus grand'
            }
            return 'Tri note pondérée (1er clic : décroissant)'
        }

        const toggleNoteSort = (tabId) => {
            if (filters[tabId].noteSort === '') {
                filters[tabId].noteSort = 'desc'
            } else if (filters[tabId].noteSort === 'desc') {
                filters[tabId].noteSort = 'asc'
            } else {
                filters[tabId].noteSort = 'desc'
            }
        }

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
            showFilters,
            filters,
            filteredRows,
            sourceRows,
            selectOptions,
            form,
            load,
            performanceUsine,
            listeBeneficiaireValider,
            anneePerformance,
            noteSortLabel,
            toggleNoteSort,
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
