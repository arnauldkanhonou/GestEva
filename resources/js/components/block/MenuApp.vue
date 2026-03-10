<template>
    <div class="menu-item dropdown main-color-bg">
        <a class="list-group-item main-color-bg" role="button" data-bs-toggle="collapse" href="#administration"
           aria-expanded="false" aria-controls="collapseExample">
            <span class="fa fa-file" aria-hidden="true"></span> <b style="font-size: 17px">Fichiers</b>
        </a>
        <div class="list-group" id="administration">
            <router-link v-if="hasRole('ROLE_USER')" class="list-group-item" :to="{name:'missions.index'}"><b
                class="text-gray-800"><i
                class="fa fa-user-circle text-red-600"></i> Missions</b></router-link>
            <router-link v-if="hasRole('ROLE_USER')" class="list-group-item" :to="{name:'objectifs.index'}"><b
                class="text-gray-800"><i
                class="fa fa-book-open text-red-600"></i> Objectifs</b></router-link>

            <router-link v-if="hasRole('ROLE_USER')" class="list-group-item" :to="{name:'evaluation.mis.pacours'}"><b
                class="text-gray-800"><i
                class="fa fa-book-open text-red-600"></i> Evaluat. mi-pacours</b></router-link>

            <router-link v-if="hasRole('ROLE_USER')" class="list-group-item" :to="{name:'evaluations.index'}"><b
                class="text-gray-800"><i
                class="fa fa-book text-red-600"></i> Evaluation annuelle</b></router-link>

            <router-link v-if="hasRole('ROLE_USER')" class="list-group-item" :to="{name:'evaluations.user.index'}"><b
                class="text-gray-800"><i
                class="fa fa-book-open text-red-600"></i> Historique Evaluat.</b></router-link>

            <router-link v-if="hasRole('ROLE_DIRECTEUR')" class="list-group-item"
                         :to="{name:'collaborateurs.index',params: { idColab: 0}}"><b class="text-gray-800"><i
                class="fa fa-users text-red-600"></i> Collaborateurs</b></router-link>
            <!--<router-link v-if="hasRole('ROLE_DIRECTEUR')" class="list-group-item"
                         :to="{name:'validations.performance.directions'}"><b class="text-gray-800"><i
                class="fa fa-users text-red-600"></i> Entretien Individuel</b></router-link>-->
            <router-link v-if="hasRole('ROLE_DIRECTEUR')" class="list-group-item"
                         :to="{name:'consultation.performance.directions'}"><b class="text-gray-800"><i
                class="fa fa-users text-red-600"></i> Validation Globale</b></router-link>
        </div>
        <!--          <button class="btn btn-default text-white dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                 <b class="text-center">FICHIER</b>
             </button>
                 <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                     <li><a class="dropdown-item" href="#"><b>Employés</b></a></li>
                     <li><a class="dropdown-item" href="#"><b>Objectifs</b></a></li>
                     <li><a class="dropdown-item" href="#"><b>Formations</b></a></li>
                     <li><a class="dropdown-item" href="#"><b>Performance annuelle</b></a></li>
                     <li><a class="dropdown-item" href="#"><b>Rapports</b></a></li>
                 </ul>
             </div>-->
    </div>

    <div v-if="hasRole('ROLE_RESPONSABLE')&&salarie.haveCollab" class="menu-item dropdown main-color-bg">
        <a class="list-group-item main-color-bg" role="button" data-bs-toggle="collapse" href="#Managementcollaborateur"
           aria-expanded="false" aria-controls="collapseExample">
            <b style="font-size: 17px"><i class="fa fa-users"></i> Gestion Collaborat.</b>
        </a>
        <div class="list-group collapse" id="Managementcollaborateur">
            <router-link v-if="hasRole('ROLE_RESPONSABLE')" class="list-group-item" :to="{name:'collaborateurs.index',params: { idColab: 0}}">
                <b class="text-gray-800"><i
                    class="fa fa-users text-red-600"></i> Collaborateurs</b></router-link>

<!--            <router-link v-if="hasRole('ROLE_RESPONSABLE')" class="list-group-item"-->
<!--                         :to="{name:(salarie.respService ||salarie.respUnite)?'validations.performance':'validation.performance.pole'}">-->
<!--                <b class="text-gray-800"><i class="fa fa-check text-red-600"></i> Entretien Individuel</b></router-link>-->

            <router-link v-if="hasRole('ROLE_RESPONSABLE')" class="list-group-item"
                         :to="{name:(salarie.respService || salarie.respUnite)?'consultations.performance':'consultations.performance.pole'}">
                <b class="text-gray-800"><i class="fa fa-eye-slash text-red-600"></i> Validation Globale</b></router-link>

        </div>
    </div>

    <div v-if="hasRole('ROLE_CODI')" class="menu-item dropdown main-color-bg">
        <a class="list-group-item main-color-bg" role="button" data-bs-toggle="collapse" href="#codi"
           aria-expanded="false" aria-controls="collapseExample">
            <b style="font-size: 17px"><i class="fa fa-users"></i> CODIR</b>
        </a>
        <div class="list-group collapse" id="codi">
            <router-link class="list-group-item" :to="{name:'validation.performance.codi'}">
                <b class="text-gray-800"><i class="fa fa-users text-red-600"></i>Pondération</b></router-link>
            <router-link class="list-group-item" :to="{name:'simulation.performance'}">
                <b class="text-gray-800"><i class="fa fa-users text-red-600"></i>Fiche de note</b></router-link>
            <router-link class="list-group-item" :to="{name:'laureat.bonus'}">
                <b class="text-gray-800"><i class="fa fa-users text-red-600"></i>Lauréat au Bonus</b></router-link>
            <router-link class="list-group-item" :to="{name:'codi.misparcours'}">
                <b class="text-gray-800"><i class="fa fa-users text-red-600"></i>Evaluation mis-parcours</b></router-link>
            <router-link class="list-group-item" :to="{name:'cloture.evaluation'}">
                <b class="text-gray-800"><i class="fa fa-users text-red-600"></i>Clôture</b></router-link>
        </div>
    </div>

    <div v-if="hasRole('ROLE_ADMIN')||hasRole('ROLE_CODI')||hasRole('ROLE_RH')" class="menu-item dropdown main-color-bg">
        <a class="list-group-item main-color-bg" role="button" data-bs-toggle="collapse" href="#administrationRh"
           aria-expanded="false" aria-controls="collapseExample">
            <b style="font-size: 17px"><i class="fa fa-home"></i> Administration</b>
        </a>
        <div class="list-group collapse" id="administrationRh">
            <router-link class="list-group-item"
                         :to="{name:'employes.index'}"><b class="text-gray-800"><i
                class="fa fa-user-circle text-red-600"></i> Employés</b></router-link>
            <router-link class="list-group-item"
                         :to="{name:'evaluateur.index'}"><b class="text-gray-800"><i
                class="fa fa-user-circle text-red-600"></i> Evaluateurs</b></router-link>

            <router-link v-if="hasRole('ROLE_ADMIN')" class="list-group-item" :to="{name:'formations.index'}"><b
                class="text-gray-800"><i
                class="fa fa-book text-red-600"></i> Formations</b></router-link>

            <router-link class="list-group-item" :to="{name:'importation.data'}"><b class="text-gray-800"><i
                class="fa fa-user-circle text-red-600"></i> Importations</b></router-link>

<!--            <router-link class="list-group-item" :to="{name:'calcul.performance'}"><b class="text-gray-800"><i
                class="fa fa-user-circle text-red-600"></i> Simulation Bonus</b></router-link>

            <router-link class="list-group-item" :to="{name:'laureat.bonus'}"><b class="text-gray-800"><i
                class="fa fa-user-circle text-red-600"></i> Laureat</b></router-link>-->
        </div>
    </div>

    <div v-if="hasRole('ROLE_RH')" class="menu-item dropdown main-color-bg">
        <a class="list-group-item main-color-bg" role="button" data-bs-toggle="collapse" href="#ouverture"
           aria-expanded="false" aria-controls="collapseExample">
            <b style="font-size: 17px"><i class="fa fa-calendar"></i> Ouver. campagne</b>
        </a>
        <div class="list-group collapse" id="ouverture">
            <router-link class="list-group-item" :to="{name:'campagne.objectif.index'}"><b class="text-gray-800"><i
                class="fa fa-address-card text-red-600"></i> Objectifs</b></router-link>
            <router-link class="list-group-item" :to="{name:'campagne.performance.index'}"><b class="text-gray-800"><i
                class="fa fa-address-card text-red-600"></i> Performances</b></router-link>
        </div>
    </div>

    <div v-if="hasRole('ROLE_ADMIN_TECHNIQUE')" class="menu-item dropdown main-color-bg">
        <a class="list-group-item main-color-bg" role="button" data-bs-toggle="collapse" href="#comptes"
           aria-expanded="false" aria-controls="collapseExample">
            <b style="font-size: 17px"><i class="fa fa-users"></i> Gestion des comptes</b>
        </a>
        <div class="list-group collapse" id="comptes">
            <router-link class="list-group-item" :to="{name:'roles.index'}"><b class="text-gray-800"><i
                class="fa fa-road text-red-600"></i> Roles utilisateurs </b>

            </router-link>
            <router-link class="list-group-item" :to="{name:'users.index'}"><b class="text-gray-800"><i
                class="fa fa-road text-red-600"></i> Compte utilisateurs </b></router-link>
        </div>
    </div>

    <div v-if="hasRole('ROLE_ADMIN')||hasRole('ROLE_RH')" class="menu-item dropdown main-color-bg">
        <a class="list-group-item main-color-bg" role="button" data-bs-toggle="collapse" href="#configuration"
           aria-expanded="false" aria-controls="collapseExample">
            <b style="font-size: 17px"><i class="fa fa-cog"></i> Configuration</b>
        </a>
        <div class="list-group collapse" id="configuration">
            <router-link class="list-group-item" :to="{name:'directions.index'}"><b class="text-gray-800"><i
                class="fa fa-server text-red-600"></i> Direction</b></router-link>
            <router-link class="list-group-item" :to="{name:'departements.index'}"><b class="text-gray-800"><i
                class="fa fa-hockey-puck text-red-600"></i> Pôles</b></router-link>
            <router-link class="list-group-item" :to="{name:'services.index'}"><b class="text-gray-800"><i
                class="fa fa-hospital-user text-red-600"></i> Services</b></router-link>
            <router-link class="list-group-item" :to="{name:'unites.index'}"><b class="text-gray-800"><i
                class="fa fa-hospital-user text-red-600"></i> Unités</b></router-link>
            <router-link class="list-group-item" :to="{name:'fonctions.index'}"><b class="text-gray-800"><i
                class="fa fa-hospital-user text-red-600"></i> Poste</b></router-link>
            <router-link class="list-group-item" :to="{name:'categorie.employe.index'}"><b class="text-gray-800"><i
                class="fa fa-shopping-cart text-red-600"></i> Groupe professionnel</b></router-link>
            <router-link class="list-group-item" :to="{name:'categorie.professionnelles.index'}"><b
                class="text-gray-800"><i class="fa fa-shopping-cart text-red-600"></i> Catégorie profess.</b>
            </router-link>
            <router-link class="list-group-item" :to="{name:'typeevaluations.index'}"><b class="text-gray-800"><i
                class="fa fa-hospital-user text-red-600"></i> Type évaluation</b></router-link>
            <router-link class="list-group-item" :to="{name:'categories.cirtere.index'}"><b class="text-gray-800"><i
                class="fa fa-shopping-cart text-red-600"></i> Critère évaluation</b></router-link>
            <router-link class="list-group-item" :to="{name:'cirteres.evaluation.index'}"><b
                class="text-gray-800"><i class="fa fa-shopping-cart text-red-600"></i>Elément d'appréciation </b>
            </router-link>
            <router-link class="list-group-item" :to="{name:'niveau.performances.index'}"><b
                class="text-gray-800"><i class="fa fa-shopping-cart text-red-600"></i> Niveau Performance</b>
            </router-link>
            <router-link class="list-group-item" :to="{name:'ponderation.criteres.index'}"><b class="text-gray-800"><i
                class="fa fa-shopping-cart text-red-600"></i>Pondération Critère</b></router-link>
            <router-link class="list-group-item" :to="{name:'niveau.execution.objectifs.index'}"><b
                class="text-gray-800"><i class="fa fa-shopping-cart text-red-600"></i>Niveau exécution objectifs</b>
            </router-link>
        </div>
    </div>

</template>

<script>
import {useStore} from "vuex"
import appService from "../../services/appService";

export default {
    name: "MenuApp",
    setup() {
        const store = useStore();

        const {hasRole} = appService();

        let salarie = JSON.parse(sessionStorage.getItem('salarie'));

        const isUserRole = (role) => {
            store.dispatch('isUserRole', role);
        }

        return {
            store,
            salarie,
            hasRole,
            isUserRole
        }
    }
}
</script>

<style scoped>

</style>
