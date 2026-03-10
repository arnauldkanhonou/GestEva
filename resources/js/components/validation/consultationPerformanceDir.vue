<template>
    <div>
        <div class=" flex flex-col">
            <titre-application big-title="VALIDATION PERFORMANCES GROUPEE" small-title="Consultation"
                               name-page="Performances"></titre-application>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="row">
<!--                    <div class="col-md-4">-->
<!--                        <label class="uppercase mb-1 block text-base font-medium text-[#07074D]">-->
<!--                            <b>Année Performance<i class="text-red-600">*</i></b>-->
<!--                        </label>-->
<!--                        <select  v-model="form.annee" class="rounded-md form-control">-->
<!--                            <option value="" readonly>Sélectionnez une année</option>-->
<!--                            <option v-for="annee in annees" :value="annee.id" :key="annee.id">-->
<!--                                {{annee.libelle}}-->
<!--                            </option>-->
<!--                        </select>-->
<!--                    </div>-->
                    <div class="col-md-4">
                        <label class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Services<i class="text-red-600">*</i></b>
                        </label>
                        <select @change="getCollaborateurServices" v-model="form.service_id" name="" id="" class="rounded-md form-control">
                            <option value="" readonly>Sélectionnez un service</option>
                            <option v-for="service in services" :value="service.id"
                                    :key="service.id">
                                {{service.libelle}}
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="uppercase mb-1 block text-base font-medium text-[#07074D]">
                            <b>Type Evaluation<i class="text-red-600">*</i></b>
                        </label>
                        <select @change="getCollaborateurServices" v-model="form.type" id="typeEva" class="rounded-md form-control">
                            <option value="" readonly>Sélectionnez un type évaluation</option>
                            <option v-for="item in typeevaluations" :value="item.id" :key="item.id">
                                {{item.libelle}}
                            </option>
                        </select>
                    </div>

                </div>
                <div v-if="evaluations.length>0">
                    <div v-if="!typeEvaluation.code==='EMP'" class="mt-3 row">
                        <div class="col-md-4">
                            <strong>Salariés total:</strong> <strong class="text-red-600">{{statistique.nbreCollaborateur}}</strong>
                        </div>
                        <div class="col-md-4">
                            <strong>Salariés evalué:</strong> <strong class="text-red-600">{{statistique.nbrSalarieEvaluer}}</strong>
                        </div>
                        <div class="col-md-4">
                            <strong>Salariés non evalué:</strong> <strong class="text-red-600">{{statistique.nbreCollaborateur - statistique.nbrSalarieEvaluer }}</strong>
                        </div>
                    </div>
                    <table class="mt-4 min-w-full leading-normal table table-bordered table-striped"
                           style="background-color:#acb3c4 ">
                        <thead>
                        <tr>
                            <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b class="text-white">Code</b>
                            </th>
                            <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b class="text-white">Nom</b>
                            </th>
                            <th class="py-2 px-6 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b class="text-white">Prénoms </b>
                            </th>
                            <th class="py-2 px-6 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b class="text-white">Unité </b>
                            </th>
                            <th class="py-2 px-6 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b class="text-white">Perfor. </b>
                            </th>
                            <th class="py-2 px-6 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                <b class="text-white">Action </b>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="salarie in evaluations">
                            <tr>
                                <td class="py-2 border-b border-gray-200 bg-white text-md"
                                    v-text="salarie.matricule"></td>
                                <td class="py-2 border-b border-gray-200 bg-white text-md"
                                    v-text="salarie.nom"></td>
                                <td class="py-2 border-b border-gray-200 bg-white text-md"
                                    v-text="salarie.prenoms">
                                </td>
                                <td class="py-2 border-b border-gray-200 bg-white text-md"
                                    v-text="salarie.unite">
                                </td>
                                <td align="right" class="py-2 border-b border-gray-200 bg-white text-md">
                                    <b v-if="typeEvaluation.code==='EA'">{{ salarie.performance }}</b>
                                    <b v-else>-</b>
                                </td>
                                <td class="py-2 border-b border-gray-200 bg-white text-md">
                                    <button v-if="typeEvaluation.code==='EA'" @click="displayCurrentAssesment(salarie.idEval,true,salarie.idUser)" type="button" title="Consulter L'evaluation"
                                            class="btn-sm bg-green-600 text-white px-2 py-1 rounded"
                                            data-bs-toggle="modal" data-bs-target="#staticBackdropEyes">
                                        <i class="fa fa-eye-slash"></i>
                                    </button>&nbsp;&nbsp;
                                    <button v-if="typeEvaluation.code==='EMP'" @click="showEvaluationMipacours(salarie.idEval)" type="button" title="Consulter L'evaluation"
                                            class="btn-sm bg-green-600 text-white px-2 py-1 rounded"
                                            data-bs-toggle="modal" data-bs-target="#staticBackdrop1">
                                        <i class="fa fa-eye-slash"></i>
                                    </button>&nbsp;&nbsp;
                                    <button v-if="typeEvaluation.code!=='EMP'" @click="validationUnitaire(salarie.idEval,false)" type="button" title="Valider L'évaluation" class="btn-sm bg-blue-600 text-white px-2 py-1 rounded"
                                            data-bs-toggle="modal" data-bs-target="#staticBackdropValid">
                                        <i class="fa fa-check-circle"></i>
                                    </button>
                                </td>
                            </tr>

                        </template>
                        <tr>
                            <td colspan="5" class="py-2 border-b border-gray-200 bg-white text-md"><b>PERFORMANCE
                                SERVICE</b></td>
                            <td align="right" class="py-2 border-b border-gray-200 bg-white text-md"><b
                                    class="text-red-600">{{getTotalPerformance()}}</b>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
                <div v-else>
                    <div v-if="form.start" class="col-md-2 offset-5 mt-4"><br>
                        <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
                    </div>
                </div>
            </div>

            <div class="vl col-md-4">
                <h5 class="uppercase" style="text-decoration: underline">Commentaires</h5>
                <div class="mt-4">
                    <div class="col-md-12">
                        <label for="synthese" class="mb-1 block text-base font-medium text-[#07074D]">
                            <b class="uppercase"> Commentaire</b>(<b class="text-red-600"><small>Facultatif</small></b>)
                        </label>
                        <textarea v-model="form.commentaire" type="text" name="libelle" id="synthese"
                                  placeholder="saisir l'objectif"
                                  class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                    </div>

<!--                    <div class="col-md-12">-->
<!--                        <label class="mb-1 block text-base font-medium text-[#07074D]">-->
<!--                            <b>BONUS</b>-->
<!--                        </label>-->
<!--                        <input v-model="form.bonus" type="number"-->
<!--                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>-->
<!--                    </div>-->
                    <div class="offset-1 col-md-10">
                        <br>
                        <button :disabled="evaluations.length===0 || typeEvaluation.code==='EMP'" data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                class="btn btn-danger">VALIDER TOUTES LES EVALUATIONS
                        </button>
                    </div>
                    <br><br>
                </div>
            </div>
        </div>

        <!-- Modal validation en bloc-->
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
             id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                    <div style="background-color: #acb3c4" class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                        <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="exampleModalLabel">
                            <b><i class="fa fa-question-circle"></i>  CONFIRMATION</b>
                        </h5>

                    </div>
                    <div class="modal-body">
                        <b><h5>Cette action va valider les évaluations des collaborateurs du service et la rendre disponible pour les rh. </h5></b>
                        <b class="text-red-600" ><h5>Êtes-vous sûr de vouloir valider?</h5></b>
                    </div>
                    <div class="modal-footer flex items-center justify-end  border-t border-gray-200">
                        <button id="btn-fermer"  class="bg-gray-600 hover:bg-gray-600 text-white font-bold py-2 px-4"
                                data-bs-dismiss="modal">NON</button>
                        <button id="btn-confirm" @click="validationPerformance"  class="bg-green-600 hover:bg-green-600 text-white font-bold py-2 px-4">OUI</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal auto evaluation-->
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
             id="staticBackdropEyes" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                    <div style="background-color: #acb3c4"
                         class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                        <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="exampleModalLabel">
                            <b><i class="fa fa-question-circle"></i> CONSULTATION AUTO-EVALUATION</b>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div v-if="!load"  class="offset-5 mt-4">
                            <strong style="font-size: 20px;color: #057e72;" class="mb-0">Chargement...</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                            <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
                        </div>
                        <div v-else>
                            <h6 align="right"><b class="text-red-600" style="border: black solid 2px;padding: 10px;">Année: {{form1.annee}}</b></h6>
                            <br>
                            <h6 align="right"><b class="text-red-600" style="border: black solid 2px;padding: 10px;">Categorie Prof: {{ form1.codeCategorie }}</b></h6>
                            <br>
                            <h6 align="right"><b class="text-red-600" style="border: black solid 2px;padding: 10px;">Collaborateur: {{ form1.employe.nom }} {{ form1.employe.prenoms }}</b></h6>
                            <br>
                            <h5 align="center">RAPPORT D'AUTO-EVALUATION</h5>
                            <hr>
                            <h5 align="center">1- PRINCIPALES MISSIONS</h5>

                            <ul v-for="mission in form1.missions" class="ulFaireMarquant">
                                <li>{{mission.libelle}}</li>
                            </ul>

                            <hr>
                            <h5 align="center">2- BILAN DE L'ANNEE ECOULE</h5>
                            <h6>Accomplissement</h6>

                            <ul v-for="accomplissement in form1.tabAccomplissement" class="ulFaireMarquant">
                                <li>{{accomplissement}}</li>
                            </ul>
                            <hr>

                            <h6>Difficultés majeures</h6>

                            <ul v-for="difficulte in form1.tabDifficulte" class="ulFaireMarquant">
                                <li>{{difficulte}}</li>
                            </ul>

                            <hr>
                            <h6>Progrès réalisés</h6>

                            <ul v-for="progres in form1.tabProgres" class="ulFaireMarquant">
                                <li>{{progres}}</li>
                            </ul>
                            <hr>
                            <h6>Formations suivies l'année dernière</h6>
                            <table class="table table-striped ">
                                <tr style="border: black 1px solid;background-color:  #0a6779;color: white">
                                    <td style="border: black 1px solid" class="hauteur"><b>Intitulé</b></td>
                                    <td style="border: black 1px solid" class="hauteur"><b>Date</b></td>
                                    <td style="border: black 1px solid" class="hauteur"><b>Objectifs</b></td>
                                    <td style="border: black 1px solid" class="hauteur"><b>Bilan</b></td>
                                </tr>

                                <tr v-for="formation in form1.formationSuivie" style="border: black 1px solid">
                                    <td style="border: black 1px solid" class="hauteur">{{formation.libelle}}</td>
                                    <td style="border: black 1px solid" class="hauteur">{{formation.date}}</td>
                                    <td style="border: black 1px solid" class="hauteur">{{formation.objectif}}</td>
                                    <td style="border: black 1px solid" class="hauteur">{{formation.bilan}}</td>
                                </tr>
                            </table>
                            <hr>
                            <h5 align="center">3- EVALUATION DES PERFORMANCES</h5>

                            <table class="table table-striped">
                                <tr style="border: black 1px solid;background-color:  #0a6779;color: white">
                                    <td style="border: black 1px solid" class="hauteur"><b>Objectifs</b></td>
                                    <td style="border: black 1px solid" class="hauteur"><b>Réalisation</b></td>
                                    <td style="border: black 1px solid" class="hauteur"><b>Appréciation</b></td>
                                    <td style="border: black 1px solid" class="hauteur"><b>Commentaire Evaluer</b></td>
                                    <td style="border: black 1px solid" class="hauteur"><b>Commentaire Evaluateur</b></td>
                                    <td style="border: black 1px solid" class="hauteur"><b>Point</b></td>
                                </tr>

                                <tr v-for="objectif in form1.objectifs" style="border: black 1px solid;">
                                    <td style="border: black 1px solid" class="hauteur">{{objectif.libelle}}</td>
                                    <td style="border: black 1px solid" class="hauteur"><b>{{objectif.niveauExecFinal}}</b>
                                    </td>
                                    <td style="border: black 1px solid" class="hauteur">
                                        <b>{{objectif.appreFinal}}</b></td>
                                    <td style="border: black 1px solid" class="hauteur">
                                        <b>{{objectif.commentaireEvaluer}}</b></td>
                                    <td style="border: black 1px solid" class="hauteur">
                                        <b>{{objectif.commentaireResp}}</b></td>
                                    <td  style="border: black 1px solid" class="hauteur" align="right"><b>{{objectif.noteObtenue}}</b>
                                    </td>
                                </tr>

                                <tr class="hauteurTr" style="border: black 1px solid">
                                    <td style="border: black 1px solid" class="hauteur" align="right" colspan="4">
                                        <b>Total</b></td>
                                    <td style="border: black 1px solid" class="hauteur text-red-600" align="right"><b>{{form1.totalperformance}}</b></td>
                                </tr>
                            </table>
                            <hr>
                            <br>
                            <h5 align="center">4- EVALUATION DES COMPETENCES</h5>
                            <table class="table table-striped">
                                <tr style="border: black 1px solid;">
                                    <td style="border: black 1px solid" ><b>Critère</b></td>
                                    <td style="border: black 1px solid"><b>Point</b></td>
                                </tr>

                                <template v-for="(critere,key) in form1.criteres" :key="key">
                                    <tr style="border: black 1px solid ;background-color:  #0a6779;color: white">
                                        <td style="border: black 1px solid" class="border hauteur" colspan="2">
                                            <b>{{getLibelleCritere(key,'libelle')}}</b>
                                        </td>
                                    </tr>
                                    <template v-for="(value,index) in critere" :key="index">
                                        <tr style="border: black 1px solid">
                                            <td style="border: black 1px solid" >
                                                {{value.libelle}}
                                            </td>
                                            <td style="border: black 1px solid" align="right">
                                                <b>{{value.pointObtenu}}</b>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr>
                                        <td style="border: black 1px solid" class="hauteur" align="right">
                                            <b class="text-red-600">Moyenne</b>
                                        </td>
                                        <td style="border: black 1px solid" class="hauteur" align="right">
                                            <b class="text-red-600">{{getLibelleCritere(key,'note')}}</b>
                                        </td>
                                    </tr>
                                </template>
                                <tr style="border: black 1px solid">
                                    <td style="border: black 1px solid" class="hauteur" align="right"><b>Total
                                        critères de compétence</b></td>
                                    <td style="border: black 1px solid" class="hauteur" align="right"><b class="text-red-600">{{form1.totalcompetence}}</b></td>
                                </tr>
                                <tr style="border: black 1px solid">
                                    <td style="border: black 1px solid" class="hauteur" align="right"><b>Total(évaluation
                                        de performances +
                                        evaluation de compétences)</b></td>
                                    <td style="border: black 1px solid" class="hauteur" align="right"><b class="text-red-600">{{form1.total}}</b></td>
                                </tr>
                            </table>
                            <hr>
                            <h5 align="center">5- PREOCUPATIONS ET SOUHAITS</h5>
                            <br>
                            <h6>Comment envisagez-vous votre avenir professionel?</h6>

                            <ul class="ulFaireMarquant">
                                <li>{{form1.evaluation.AvenirProfs}}</li>
                            </ul>
                            <h6>Quelles sont les missions qui vous conviennent le mieux?</h6>

                            <ul class="ulFaireMarquant">
                                <li>{{form1.evaluation.convenanceMission}}</li>
                            </ul>

                            <h6>Quelles sont les difficultés que vous rencontrez?</h6>

                            <ul class="ulFaireMarquant">
                                <li>{{form1.evaluation.difficulteGblobal}}</li>
                            </ul>

                            <h6>Avez vous des compétences ou des qualités non utilisées?</h6>
                            <ul class="ulFaireMarquant">
                                <li>{{form1.evaluation.superCompetence}}</li>
                            </ul>
                            <hr>
                            <h5 align="center">6- OBJECTIFS POUR LA NOUVELLE ANNEE</h5>
                            <br>
                            <table class="table table-striped">
                                <tr style="border: black 1px solid;background-color:  #0a6779;color: white">
                                    <td style="border: black 1px solid" class="hauteur"><b>Objectifs</b></td>
                                    <td style="border: black 1px solid" class="hauteur"><b>Actions clées</b></td>
                                    <td style="border: black 1px solid" class="hauteur"><b>Résultats attendus</b>
                                    </td>
                                    <td style="border: black 1px solid" class="hauteur"><b>Echéances</b></td>
                                </tr>

                                <tr v-for="objectif in form1.nouvelleObjectifs" style="border: black 1px solid">
                                    <td style="border: black 1px solid" class="px-3 py-1 border-b border-gray-200 bg-white text-md"
                                        v-text="objectif.libelle"></td>
                                    <td style="border: black 1px solid" class="px-3 py-1 border-b border-gray-200 bg-white text-md">
                                       <span v-if="objectif.isjson">
                                            <span v-for="(item,index) in JSON.parse(objectif.actionCle)" :key="index">
                                                {{ index + 1 }}&nbsp;-&nbsp;&nbsp;{{ item }}
                                             <br>
                                            </span>
                                       </span>
                                        <span v-else>
                                           {{objectif.actionCle}}
                                       </span>
                                    </td>
                                    <td style="border: black 1px solid" class="px-3 py-1 border-b border-gray-200 bg-white text-md">
                                      <span v-if="objectif.isjson">
                                            <span v-for="(item,index) in JSON.parse(objectif.resultatAttendu)" :key="index">
                                                {{ index + 1 }}&nbsp;-&nbsp;&nbsp;{{ item }}
                                             <br>
                                            </span>
                                       </span>
                                        <span v-else>
                                           {{objectif.resultatAttendu}}
                                       </span>
                                    </td>
                                    <td style="border: black 1px solid" class="hauteur">{{objectif.echeance}}</td>
                                </tr>

                            </table>
                            <hr>

                            <h5 align="center">7- DEVELOPPEMENT DE COMPETENCES</h5>

                            <br>

                            <table class="table table-striped">
                                <tr style="border: black 1px solid;background-color:  #0a6779;color: white">
                                    <td style="border: black 1px solid" class="hauteur"><b>Besoins de formation</b>
                                    </td>
                                    <td style="border: black 1px solid" class="hauteur"><b>Problème à résoudre</b>
                                    </td>
                                    <td style="border: black 1px solid" class="hauteur"><b>Résultats attendus</b>
                                    </td>
                                </tr>
                                <tr v-for="besoin in form1.besoinFormations" style="border: black 1px solid">
                                    <td style="border: black 1px solid" class="hauteur">{{besoin.libelle}}</td>
                                    <td style="border: black 1px solid" class="hauteur">
                                        {{besoin.problemeEnonce}}
                                    </td>
                                    <td style="border: black 1px solid" class="hauteur">
                                        {{besoin.resultatAttendu}}
                                    </td>
                                </tr>
                            </table>
                            <hr>
                            <br>
                            <h5 align="center">8- COMMENTAIRES</h5>

                            <div style="border: black solid 1px;margin-top: 20px; border-radius: 40px;padding: 8px">
                                <p align="center"><b>commentaire de l'évalué</b></p>
                                <p style="margin-right: 15px;">
                                    {{form1.evaluation.commentaireEvaluer}}
                                    <br>
                                </p>
                                <br>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer flex items-center justify-end  border-t border-gray-200">
                        <button id="btn-fermer1" class="bg-gray-600 hover:bg-gray-600 text-white font-bold py-2 px-4"
                                data-bs-dismiss="modal">Fermer
                        </button>
<!--                        <button @click="displayFormEntretien(form1.evaluation.id)"  id="btn-edit" class="bg-green-600 hover:bg-green-600 text-white font-bold py-2 px-4"-->
<!--                        ><i class="fa fa-edit"></i>Modifier-->
<!--                        </button>-->
                    </div>

                </div>
            </div>
        </div>

        <!-- Modal validation unitaire-->
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
             id="staticBackdropValid" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                    <div style="background-color: #acb3c4" class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                        <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="exampleModalLabelval">
                            <b><i class="fa fa-question-circle"></i>  CONFIRMATION</b>
                        </h5>
                        <!--<button type="button" class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                                data-bs-dismiss="modal" aria-label="Close"></button>-->
                    </div>
                    <div class="modal-body">
                        <h5 class="uppercase" style="text-decoration: underline">Commentaires</h5>
                        <div class="mt-4">
                            <div class="col-md-12">
                                <label for="synthese" class="mb-1 block text-base font-medium text-[#07074D]">
                                    <b class="uppercase"> Commentaire</b>(<b class="text-red-600"><small>Facultatif</small></b>)
                                </label>
                                <textarea v-model="form.commentaireUnitaire"  type="text" name="libelle" id="syntheseVali"
                                          placeholder="saisir l'objectif"
                                          class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>

                            </div>

<!--                            <div class="col-md-12">-->
<!--                                <label class="mb-1 block text-base font-medium text-[#07074D]">-->
<!--                                    <b>BONUS</b>-->
<!--                                </label>-->
<!--                                <input  type="number" v-model="form.bonusUnitaire"-->
<!--                                        class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>-->
<!--                            </div>-->
                        </div>
                        <div class="modal-footer flex items-center justify-end  border-t border-gray-200">
                            <button id="btn-fermerValid"  class="bg-gray-600 hover:bg-gray-600 text-white font-bold py-2 px-4"
                                    data-bs-dismiss="modal">Fermer</button>
                            <button id="btn-confirmRejet" @click="invalidEvaluation(form1.evaluation.id)" class="bg-red-600 hover:bg-red-600 text-white font-bold py-2 px-4"
                                    >Rejeter</button>
                            <button id="btn-confirmValid" @click="validationUnitaire(form1.evaluation.id,true)"  class="bg-green-600 hover:bg-green-600 text-white font-bold py-2 px-4">Valider</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <!-- Modal evaluation mi-parcours -->
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
             id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered relative w-auto pointer-events-none">
                <div
                    class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                    <div style="background-color: #acb3c4"
                         class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                        <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="examp">
                            <b><i class="fa fa-question-circle"></i> CONSULTATION EVALUATION MI-PARCOURS</b>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div v-if="!load" class="offset-5 mt-4">
                            <strong style="font-size: 20px;color: #057e72;" class="mb-0">Chargement...</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                            <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
                        </div>
                        <div v-else>
                            <div class="row">
                                <div style="border: #0a0a0a 2px solid" class="offset-4 col-md-4 text-center">
                                    <b>EVALUATION A MI-PARCOURS</b>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="offset-4 col-md-4 text-center">
                                    <b class="text-center">Année:{{form.annee.libelle}}</b>
                                </div>
                            </div>

                            <table style="border: #0a0a0a 2px solid" class="table table-bordered">
                                <tr style="border: #0a0a0a 2px solid">
                                    <td width="50%" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b>Nom :</b> <b class="text-red-600">{{form.employe.nom}}</b>&nbsp;&nbsp;&nbsp;<br>
                                            <b>Prenoms :</b> <b class="text-red-600">{{form.employe.prenoms}}</b>
                                        </div>
                                    </td>
                                    <td colspan="2" width="50%" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b>Date d'entretien:</b> <b class="text-red-600">{{form.evaluation.dateEntretien}}</b>
                                        </div>

                                    </td>
                                </tr>
                                <tr style="border: #0a0a0a 2px solid">
                                    <td width="50%" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b>Service :</b> <b class="text-red-600">{{form.service.libelle}}</b>
                                        </div>
                                    </td>
                                    <td colspan="3" width="50%" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b> Ancienneté au poste :</b> <b class="text-red-600">{{form.anciennete}}</b>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="border: #0a0a0a 2px solid">
                                    <td width="50%" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b>Fonction :</b> <b class="text-red-600">{{form.fonction.libelle}}</b>
                                        </div>
                                    </td>
                                    <td colspan="3" width="50%" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b> Nom de l'Evaluateur :</b> <b class="text-red-600">{{form.evaluation.evaluateur}}</b>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <table style="border: #0a0a0a 2px solid">
                                <tr style="background-color: #0a6779;color: white;">
                                    <td style="border: #0a0a0a 2px solid" colspan="3">
                                        <div class="offset-2 col-md-8 text-center">
                                            <b>POINT SUR LA PERIODE ECOULEE</b> <br>
                                            <b><small>Rappel des faits marquants qui ont pu influencer l'activité et
                                                les resultats</small></b>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="border: #0a0a0a 2px solid">
                                    <td style="border: #0a0a0a 2px solid" align="center" width="50%">
                                        <b>Points positifs</b>
                                    </td>
                                    <td colspan="2" style="border: #0a0a0a 2px solid" align="center" width="50%">
                                        <b>Points négatifs</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: #0a0a0a 2px solid">
                                        <div>
                                            <small v-for="accomplissement in form.tabAccomplissement">
                                                - {{accomplissement}} <br>
                                            </small>
                                        </div>
                                    </td>
                                    <td colspan="2" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <small v-for="difficulte in form.tabDifficulte">
                                                - {{difficulte}} <br>
                                            </small>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="background-color: #0a6779;color: white;border: #0a0a0a 2px solid">
                                    <td align="center" style="border: #0a0a0a 2px solid" colspan="3">
                                        <div>
                                            <b>PASSAGE EN REVUE DES OBJECTIFS</b>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="border: #0a0a0a 2px solid">
                                    <td align="center" width="42%" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b>Rappel des objectifs fixés en début d'année</b>
                                        </div>
                                    </td>
                                    <td align="center" width="25%" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b>Niveau de réalisation atteint</b>
                                        </div>
                                    </td>
                                    <td align="center" width="33%" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b>
                                                Analyse et commentaire eventuel <br>
                                                <small>(Difficultés rencontrées)</small>
                                            </b>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="objectif in form.objectifs">
                                    <td style="border: #0a0a0a 2px solid">
                                        {{objectif.libelle}}
                                    </td>
                                    <td class="text-red-600" style="border: #0a0a0a 2px solid">
                                        <b>{{objectif.NivRealisationMPEvaluer}}</b>
                                    </td>
                                    <td style="border: #0a0a0a 2px solid">
                                        {{objectif.commentaireEvaluerMP}}
                                    </td>
                                </tr>
                            </table>
                            <br>
                            <table style="border: #0a0a0a 2px solid" class="table table-bordered">
                                <tr style="background-color:#0a6779;color: white;">
                                    <td align="center" colspan="2" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b class="uppercase">Autres réalisations accomplies,ou difficultés soulignées à mi-parcours</b>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="border: #0a0a0a 2px solid">
                                    <td align="center" width="50%" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b>Réalisations</b>
                                        </div>
                                    </td>
                                    <td align="center" width="50%" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b>Difficultés</b>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="realisation in form.autreRealisation">
                                    <td style="border: #0a0a0a 2px solid">
                                        {{realisation.realisation}}
                                    </td>
                                    <td style="border: #0a0a0a 2px solid">
                                        {{realisation.difficultes}}
                                    </td>
                                </tr>
                            </table>
                            <br>
                            <table style="border: #0a0a0a 2px solid" class="table table-bordered">
                                <tr style="background-color:#0a6779;color: white;">
                                    <td align="center" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b class="uppercase">COMMENTAIRE DU SALARIE</b>
                                        </div>
                                    </td>
                                    <td align="center" style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b class="uppercase">COMMENTAIRE EVALUATEUR</b>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="border: #0a0a0a 2px solid">
                                    <td style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b>{{form.evaluation.commentaireEvaluer}}</b>
                                        </div>
                                    </td>
                                    <td style="border: #0a0a0a 2px solid">
                                        <div>
                                            <b>{{form.evaluation.commentaireResp}}</b>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer flex items-center justify-end  border-t border-gray-200">
                        <button id="btn-fermer-mipacours"
                                class="bg-gray-600 hover:bg-gray-600 text-white font-bold py-2 px-4"
                                data-bs-dismiss="modal">Fermer
                        </button>

                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import appService from "../../services/appService";
    import {onMounted, ref} from "vue";
    import {reactive} from "@vue/reactivity";
    import useCampagneObjectif from "../../services/campagneObjectif";
    import TitreApplication from "../block/TitreApplication";
    import axios from "axios";
    import Swal from "sweetalert2";
    import {useRouter} from "vue-router";
    import useTypeEvaluation from "../../services/typeEvalaution";

    export default {
        name: "consultationPerformanceRd",
        components: {TitreApplication},
        setup(){
            const router = useRouter();
            const succesMessage = ref([]);
            const salaries = ref([]);
            const services = ref([]);
            const collaborateurs = ref([]);
            const statistique = ref([]);
            const evaluations = ref([]);
            let user = JSON.parse(sessionStorage.getItem('user'));
            const form = reactive({
                commentaire: 'R.A.S',
                bonus: 0,
                commentaireUnitaire: '',
                bonusUnitaire: 0,
                annee: '',
                start:false,
                service_id:'',
                idEvaluation:'',
                type:'',
                anciennete: '',
                autreRealisation: '',
                evaluation: '',
                objectifs: '',
                tabAccomplissement: '',
                tabDifficulte: '',
                employe: '',
                fonction: '',
                service:'',
            })
            const {annees,typeevaluations, getAnnes,getTypeEvaluations} = useCampagneObjectif();
            const {load,form1,displayRapport,validationBySalirie,sleep,catchErrors} = appService();
            const {typeEvaluation,getTypeEvaluation} = useTypeEvaluation();

            onMounted(getAnnes);
            onMounted(getTypeEvaluations);

            onMounted(()=>{
                axios.get('services/by/departement/'+true)
                    .then((response)=>{
                        services.value = response.data.data.services
                    })
            });

            const getLibelleCritere = (libelle,type)=>{
                let libel = libelle.split(',');
                if (type==='note'){
                    return libel[1];
                }else {
                    return libel[0];
                }

            }
            const getTotalPerformance = () => {
                let value = 0;
                let totalEvaluation =  evaluations.value.length;
                evaluations.value.forEach(function (item) {
                    value += parseFloat(item.performance);
                })
                let moyenneService = value/totalEvaluation;

                return Math.round(moyenneService*100)/100;
            }

            const getCollaborateurServices = (displayError = true) => {
                if (form.service_id === '') {
                    Swal.fire({
                        text: 'Veuillez sélectionnez un service.',
                    });
                    return;
                }

                if (form.type === '') {
                    Swal.fire({
                        text: 'Veuillez sélectionnez le type évaluation.',
                    });
                    return;
                }

                // if (form.annee === '') {
                //     Swal.fire({
                //         text: 'Veuillez sélectionnez une année.',
                //     });
                //     return;
                // }
                form.start = true;
                evaluations.value = [];
                getTypeEvaluation(form.type)
                axios.get('collaborateur/by/service/'+form.service_id+'/'+ form.type)
                    .then((response)=>{
                        if (response.data.data.code ===500){
                            Swal.fire({
                                title: 'Erreur',
                                text: response.data.data.message,
                                icon: 'error',
                            });
                        }else {
                            collaborateurs.value = response.data.data.salaries;
                            evaluations.value = response.data.data.evaluations;
                            statistique.value = response.data.data.statisticService;
                            if(displayError){
                                if(evaluations.value.length===0){
                                    let message = "Aucune évaluations non validée disponible pour les collaborateurs du service choisi";
                                    if (typeEvaluation.code==='EMP'){

                                        message = "Aucune évaluations mis-parcours disponible pour les collaborateurs du service choisi";
                                    }
                                    Swal.fire({
                                        title: 'ALERT',
                                        text: message,
                                        icon: 'warning',
                                    });
                                }
                            }
                        }

                        form.start = false;
                    })
            }

            const validationPerformance = async () => {
                let data = reactive({
                    commentaire: form.commentaire,
                    bonus: form.bonus,
                    evaluations: evaluations.value
                });
                $('#btn-confirm').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
                await axios.post('validation/performance/service/' + form.service_id + '/' + form.type, {...data})
                    .then((response) => {
                        if (response.data.data.code === 500) {
                            $('#btn-confirm').html('OUI')
                            Swal.fire({
                                title: 'Erreur serveur',
                                text: response.data.data.message,
                                icon: 'error',
                            });
                        } else {
                            form.commentaire = '';
                            form.bonus = 0;
                            $('#btn-fermer')[0].click();
                            Swal.fire({
                                title: 'SUCCES',
                                text: response.data.data.message,
                                icon: 'success',
                            });
                            evaluations.value = [];
                            $('#btn-confirm').html('OUI')
                        }
                    })
                    .catch(error => {
                        catchErrors(error, succesMessage);
                    })
            }

            const displayCurrentAssesment = (idEval,display,idUser)=>{
                displayRapport(idEval,display,idUser);
            }

            const showEvaluationMipacours = (idEvaluation) => {
                load.value = false;
                axios.get('show/evaluation/miparcours/' + idEvaluation)
                    .then((response) => {
                        if (response.data.data.code === 200) {
                            let resp = response.data.data;
                            form.anciennete = resp.anciennete;
                            form.objectifs = resp.objectifs
                            form.autreRealisation = resp.autreRealisations;
                            form.evaluation = resp.evaluation;
                            form.tabAccomplissement = resp.tabAccomplissement;
                            form.tabDifficulte = resp.tabDifficulte;
                            form.employe = resp.employe;
                            form.fonction = resp.fonction;
                            form.service = resp.service;
                            //form.annee = resp.annee;
                            load.value = true;
                        }

                    })
            }

            const displayFormEntretien = (idEval)=>{
                $('#btn-fermer1')[0].click();
                console.log(idEval);
                router.push({name: 'entretien.evaluations',params: { id: idEval }});
            }

            const validationUnitaire = (idEval,go=false)=>{
                if (!go){
                    form.idEvaluation = idEval;
                    return 0;
                }else {
                    if(form.commentaireUnitaire===''){
                        form.commentaireUnitaire = 'R.A.S'
                    }
                    validationBySalirie(user.id,form.idEvaluation,form)
                    sleep(100).then(()=>{
                        getCollaborateurServices(false);
                        form.commentaireUnitaire = '';
                        form.bonusUnitaire = 0;
                        $('#btn-fermerValid')[0].click();
                        $('#btn-confirmValid').html('Valider')
                    })
                }

            }

            const invalidEvaluation = (idEval)=>{

                $('#btn-confirmRejet').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
                if(form.commentaireUnitaire===''){
                    form.commentaireUnitaire = 'R.A.S'
                }
                var commentaire = form.commentaireUnitaire;
                form.commentaireUnitaire = 'REJET(' + commentaire + ')';
                commentaire = '';
                axios.post('invalid/evaluation/'+form.idEvaluation,{...form})
                .then((response)=>{
                    if(response.data.data.code ===500){
                        Swal.fire({
                            title: 'Erreur',
                            text: response.data.data.message,
                            icon: 'error',
                        });
                    }else {
                        $('#btn-fermerValid')[0].click();
                        $('#btn-confirmRejet').html('REJETER')
                        form.commentaireUnitaire = '';
                        form.bonusUnitaire = 0;
                        getCollaborateurServices(false);
                        Swal.fire({
                            title: 'SUCCES',
                            text: 'Evaluation rejetée avec succès.',
                            icon: 'success',
                        });
                    }

                })
            }

            return {
                salaries,
                form,
                annees,
                services,
                evaluations,
                collaborateurs,
                statistique,
                form1,
                load,
                typeevaluations,
                typeEvaluation,
                getCollaborateurServices,
                getTotalPerformance,
                validationPerformance,
                displayCurrentAssesment,
                displayFormEntretien,
                validationUnitaire,
                invalidEvaluation,
                showEvaluationMipacours,
                getLibelleCritere,
            }
        }
    }
</script>

<style scoped>
    .vl {
        border-left: 3px solid #057e72;
        height: auto;
    }

    .modal-lg {
        max-width: 65% !important;
    }

    .ulFaireMarquant {
        list-style-type: none;
        margin-left: -42px
    }

    h5{
        color:red;
    }

    h6{
        text-decoration: underline;
    }
</style>
