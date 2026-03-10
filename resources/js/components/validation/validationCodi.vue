<template>
    <div>
        <div class=" flex flex-col">
            <titre-application big-title="PONDERATION DE NOTE AU CODI" small-title="Validation"
                               name-page="Performances"></titre-application>
        </div>
        <div class="row">
            <div class="col-md-3">
                <label class="uppercase mb-1 block text-base font-medium text-[#07074D]">
                    <b>Direction<i class="text-red-600">*</i></b>
                </label>
                <select @change="getServiceByDirction" v-model="form.direction" class="rounded-md form-control">
                    <option value="" readonly>Sélectionnez une direction</option>
                    <option v-for="direction in directions" :value="direction.id" :key="direction.id">
                        {{direction.libelle}}
                    </option>
                </select>
            </div>
<!--            <div class="col-md-3">
                <label class="uppercase mb-1 block text-base font-medium text-[#07074D]">
                    <b>Bloc/pole<i class="text-red-600">*</i></b>
                </label>
                <select v-model="form.bloc" @change="getserviceByBloc" class="rounded-md form-control">
                    <option value="" readonly>Sélectionnez un bloc d'usine</option>
                    <option v-for="bloc in form.blocs" :value="bloc.id" :key="bloc.id">
                        {{bloc.libelle}}
                    </option>
                </select>
            </div>-->
            <div class="col-md-3">
                <label class="uppercase mb-1 block text-base font-medium text-[#07074D]">
                    <b>Services/Entités<i class="text-red-600">*</i></b>
                </label>
                <select @change="getPerformanceService"  v-model="form.service" class="rounded-md form-control">
                    <option value="" readonly>Choisissez un service</option>
                    <option v-for="service in form.services" :value="service.id" :key="service.id">
                        {{service.libelle}}
                    </option>
                </select>
            </div>
<!--            <div class="col-md-3">
                <label class="uppercase mb-1 block text-base font-medium text-[#07074D]">
                    <b>Années<i class="text-red-600">*</i></b>
                </label>
                <select @change="getPerformanceService" v-model="form.annee" class="rounded-md form-control">
                    <option value="" readonly>Choisissez une année</option>
                    <option v-for="annee in annees" :value="annee.id" :key="annee.id">
                        {{annee.libelle}}
                    </option>
                </select>
            </div>-->
        </div>
        <div class="row">
            <div v-if="moyenneUsine!==0" class="offset-4 col-md-4 mt-3">
                <strong style="font-size: 19px;">PERFORMANCE ENTREPRISE INITIALE : <b class="text-danger">{{moyenneInitialeUsine}}</b></strong>
            </div>

            <div v-if="moyenneUsine!==0" class="col-md-4 mt-3">
                <strong style="font-size: 19px;">PERFORMANCE ENTREPRISE AJUSTEE : <b class="text-danger">{{moyenneUsine}}</b></strong>
            </div>
        </div>
        <div v-if="form.start" class="col-md-2 offset-5 mt-4"><br>
            <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
        </div>
        <div v-if="performances.length>0">
           <div class="mt-3 row">
                <div class="col-md-8">
                   <table style="border: white 2px solid; font-size: 13px;" class="table table-bordered table-striped">
                       <thead style="background-color: #057e72;color: white" class="uppercase">
                       <tr>
                           <td>Matricule</td>
                           <td>Nom & Prénoms</td>
                           <td>Poste</td>
                           <td>Cat.</td>
                           <td>Note</td>
                           <td>Perf.</td>
                           <td>Note Pondé.</td>
                           <td>Perf.Apr.Pondé.</td>
                           <td>Action</td>
                       </tr>
                       </thead>
                       <tbody>
                       <template v-for="val in performances">
                           <tr style="border: black 1px solid" :style="val.notePondere!==0?'color: green':''">
                               <td><b v-text="val.matricule"></b></td>
                               <td><b>{{val.nom}} {{val.prenoms}}</b></td>
                               <td><b v-text="val.poste"></b></td>
                               <td><b v-text="val.cateprofe"></b></td>
                               <td><b v-text="val.performanceRealiser"></b></td>
                               <td><b v-text="val.niveauPerf"></b></td>
                               <td><b v-text="val.performanceFinal"></b></td>
                               <td><b v-text="val.niveauPerfApresPonde"></b></td>
                               <td class="py-2 border-b border-gray-200 bg-white text-md">
                                   <button @click="displayCurrentAssesment(val.id,true)" type="button" title="Consulter L'evaluation"
                                           class="btn-sm bg-green-600 text-white px-2 py-1 rounded"
                                           data-bs-toggle="modal" data-bs-target="#staticBackdropEyes">
                                       <i class="fa fa-eye-slash"></i>
                                   </button>&nbsp;&nbsp;
                                   <button v-if="val.clotureCodi==0" @click="validationUnitaire(val,false)" type="button" title="Valider L'évaluation" class="btn-sm bg-blue-600 text-white px-2 py-1 rounded"
                                           data-bs-toggle="modal" data-bs-target="#staticBackdropValid">
                                       <i class="fa fa-check-circle"></i>
                                   </button>
                               </td>
                           </tr>
<!--                           <tr>
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
                                   <b v-text="salarie.performance"></b>
                               </td>
                               <td class="py-2 border-b border-gray-200 bg-white text-md">
                                   <button @click="displayCurrentAssesment(salarie.idEval,true,salarie.idUser)" type="button" title="Consulter L'evaluation"
                                           class="btn-sm bg-green-600 text-white px-2 py-1 rounded"
                                           data-bs-toggle="modal" data-bs-target="#staticBackdropEyes">
                                       <i class="fa fa-eye-slash"></i>
                                   </button>&nbsp;&nbsp;
                                   <button @click="validationUnitaire(salarie.idEval,false)" type="button" title="Valider L'évaluation" class="btn-sm bg-blue-600 text-white px-2 py-1 rounded"
                                           data-bs-toggle="modal" data-bs-target="#staticBackdropValid">
                                       <i class="fa fa-check-circle"></i>
                                   </button>
                               </td>
                           </tr>-->
                       </template>
                       <tr style="border: black 1px solid">
                           <td align="right" colspan="5"><b style="font-size: 17px" class="text-danger text-uppercase">Moyenne service après pondération</b></td>
                           <td colspan="5">
                               <b>{{moyenneService}}</b>
                           </td>
                       </tr>
                       </tbody>
                   </table>

               </div>
               <div class="vl col-md-4">
                   <h5 class="uppercase text-danger" style="text-decoration: underline">Décision codi</h5>
                   <div class="mt-3">
                       <div class="col-md-12">
                           <label class="mb-1 block text-base font-medium text-[#07074D]">
                               <b>PONDERER UN POINT</b>
                           </label>
                           <input v-model="form.bonus" type="number"
                                  class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                       </div>

                       <div class="col-md-12 mt-3">
                           <label for="synthese" class="mb-1 block text-base font-medium text-[#07074D]">
                               <b class="uppercase"> Commentaire</b>(<b class="text-red-600"><small>Facultatif</small></b>)
                           </label>
                           <textarea v-model="form.commentaire" type="text" name="libelle" id="synthese"
                                     placeholder="saisissez votre commentaire ici"
                                     class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                       </div>

                       <div class="offset-1 col-md-10">
                           <br>
                           <button :disabled="performances.length===0" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                               AJUSTER POUR TOUS LES COLLABORATEURS
                           </button>
                       </div>
                       <br><br>
                   </div>
               </div>
           </div>
        </div>
        <div v-else class="mt-4">
            <div v-if="form.load" class="alert alert-info">
                <b>AUCUNE EVALUATION DISPPONIBLE POUR LE SERVICE SELECTIONNE</b>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
             id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                    <div style="background-color: #acb3c4" class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                        <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="exampleModalLabel">
                            <b><i class="fa fa-question-circle"></i>  Décision codi</b>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <b ><h5>Cette action va pondérer la note de toutes les évaluations du service présentes dans le tableau de <b class="text-dark">{{form.bonus}}</b></h5></b>
                        <b class="text-red-600" ><h5>Êtes-vous sûr de vouloir valider?</h5></b>
                    </div>
                    <div class="modal-footer flex items-center justify-end  border-t border-gray-200">
                        <button id="btn-fermer"  class="bg-gray-600 hover:bg-gray-600 text-white font-bold py-2 px-4"
                                data-bs-dismiss="modal">NON</button>
                        <button id="btn-confirm" @click="validationPerformance"  class="bg-red-600 hover:bg-red-600 text-white font-bold py-2 px-4">OUI</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
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
                                    <td style="border: black 1px solid" class="hauteur"><b>{{objectif.niveauExecFinal}}</b></td>
                                    <td style="border: black 1px solid" class="hauteur"><b>{{objectif.appreFinal}}</b></td>
                                    <td style="border: black 1px solid" class="hauteur"><b>{{objectif.commentaireEvaluer}}</b></td>
                                    <td style="border: black 1px solid" class="hauteur"><b>{{objectif.commentaireResp}}</b></td>
                                    <td style="border: black 1px solid" class="hauteur" align="right"><b>{{objectif.noteObtenue}}</b></td>
                                </tr>

                                <tr class="hauteurTr" style="border: black 1px solid">
                                    <td style="border: black 1px solid" class="hauteur" align="right" colspan="4">
                                        <b>Total</b></td>
                                    <td style="border: black 1px solid" class="hauteur" align="right"><b class="text-red-600">{{form1.totalperformance}}</b></td>
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
                                    <td style="border: black 1px solid" class="hauteur" align="right"><b>Total critères de compétence</b></td>
                                    <td style="border: black 1px solid" class="hauteur" align="right"><b class="text-red-600">{{form1.totalcompetence}}</b></td>
                                </tr>
                                <tr style="border: black 1px solid">
                                    <td style="border: black 1px solid" class="hauteur" align="right"><b>Total(évaluation
                                        de performances +
                                        evaluation de compétences)</b></td>
                                    <td style="border: black 1px solid" class="hauteur" align="right"><b class="text-red-600">{{ parseFloat(form1.total) }}</b></td>
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
                    </div>

                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
             id="staticBackdropValid" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                    <div style="background-color: #acb3c4" class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                        <h5 class="text-red-600 font-medium leading-normal text-uppercase text-gray-800" id="exampleModalLabelval">
                            <b><i class="fa fa-question-circle"></i>  Décision codi</b>
                        </h5>
                        <!--<button type="button" class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                                data-bs-dismiss="modal" aria-label="Close"></button>-->
                    </div>
                    <div class="modal-body">
                        <div>
                            <div class="mb-4">
                                <table>
                                    <tr>
                                        <td><b>NOM:</b></td>
                                        <td>{{currentEval.nom}}&nbsp;{{currentEval.prenoms}}&nbsp;&nbsp;</td>
                                        <td><b class="text-uppercase">NOTE PONDERéE:&nbsp;&nbsp;</b></td>
                                        <td><b class="text-danger">{{currentEval.performanceFinal}}</b></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <label class="mb-1 block text-base font-medium text-[#07074D]">
                                    <b>PONDERER LA NOTE</b>
                                </label>
                                <input type="number" v-model="form.bonusUnitaire"
                                        class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                            </div>

                            <div class="col-md-12 mt-3">
                                <label for="synthese" class="mb-1 block text-base font-medium text-[#07074D]">
                                    <b class="uppercase"> Commentaire</b>(<b class="text-red-600"><small>Facultatif</small></b>)
                                </label>
                                <textarea v-model="form.commentaireUnitaire"  type="text" name="libelle" id="syntheseVali"
                                          placeholder="saisir un commentaire"
                                          class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>

                            </div>
                        </div>
                        <div class="modal-footer flex items-center justify-end  border-t border-gray-200">
                            <button id="btn-fermerValid"  class="bg-gray-600 hover:bg-gray-600 text-white font-bold py-2 px-4"
                                    data-bs-dismiss="modal">Fermer</button>

                            <button id="btn-confirmValid" @click="validationUnitaire(currentEval,true)"  class="bg-green-600 hover:bg-green-600 text-white font-bold py-2 px-4">Valider</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>
</template>

<script>
    import TitreApplication from "../block/TitreApplication";
    import useDirection from "../../services/directionServices";
    import {onMounted,reactive,ref} from "vue";
    import axios from "axios";
    import appService from "../../services/appService";
    import useCampagneObjectif from "../../services/campagneObjectif";
    import Swal from "sweetalert2";
    export default {
        name: "validationCodi",
        components: {TitreApplication},
        setup(){
            const form = reactive({
                direction:'',
                services:'',
                blocs:'',
                start:false,
                load:false,
                service:'',
                annee:'',
                commentaire:'R.A.S',
                bonus:0,
                commentaireUnitaire: '',
                bonusUnitaire: 0,
                bloc:'',
            })
            let user = JSON.parse(sessionStorage.getItem('user'));
            const {load,form1,displayRapport,validationBySalirie,sleep,catchErrors} = appService();
            const succesMessage = ref('')
            const performances = ref([])
            const moyenneUsine = ref(0)
            const moyenneInitialeUsine = ref(0)
            const moyenneService = ref('')
            const currentEval = ref('')
            const  {getDirections,directions} = useDirection();
            const {annees, getAnnes} = useCampagneObjectif();
            onMounted(getAnnes);

            onMounted(getDirections);

            const getLibelleCritere = (libelle,type)=>{
                let libel = libelle.split(',');
                if (type==='note'){
                    return libel[1];
                }else {
                    return libel[0];
                }

            }

            const getServiceByDirction = ()=>{
                if (form.direction ===''){
                    return ;
                }
                performances.value = [];
                form.load = false;
                form.start = true;
                axios.get('service/direction/'+form.direction+'/'+true)
                .then((response)=>{
                    if (response.data.data.code ===500){

                    }else {
                        form.services = response.data.data.services;
                        form.blocs = response.data.data.blocs;
                        form.start = false;
                    }

                }).catch((error)=>{
                    catchErrors(error,succesMessage);
                })
            }

            const getserviceByBloc = () =>{
                if (form.bloc ===''){
                    return ;
                }
                performances.value = [];
                form.start = true;
                axios.get('service/direction/'+form.bloc+'/'+false)
                    .then((response)=>{
                        if (response.data.data.code ===500){

                        }else {
                            form.services = response.data.data.services;
                            form.start = false;
                        }

                    }).catch((error)=>{
                    catchErrors(error,succesMessage);
                })
            }

            const getPerformanceService = (displayError = true) => {
                /*if (form.annee === '') {
                    alert('Veuillez choisir une année.')
                    return;
                }*/
                if (form.service === '') {
                    alert('Veuillez choisir un service.')
                    return;
                }
                performances.value = [];
                form.start = true;
                form.load = false;
                //performance/service/validate
                axios.get('get/notepondere/service/'+form.service)
                    .then((response) => {
                            if (response.data.code===500){
                            form.start = false;
                            form.load = false;
                            Swal.fire({
                                title: 'Erreur serveur',
                                text: response.data.data.message,
                                icon: 'error',
                            });
                        }else {
                            performances.value = response.data.evaluations;
                            moyenneUsine.value = response.data.performanceUsine;
                            moyenneInitialeUsine.value = response.data.performanceInitialUsine;
                            moyenneService.value = response.data.moyenneService;
                            /*if(displayError){
                                if(performances.value.length===0){
                                    Swal.fire({
                                        title: 'Erreur',
                                        text: 'Aucune évaluations disponible pour validation dans ce service',
                                        icon: 'error',
                                    });
                                }
                            }*/

                            form.load = true;
                            form.start = false;
                        }

                    })
                    .catch((error) => {
                        catchErrors(error, succesMessage);
                    })
            }

            const getTotalPerformance = () => {
                let value = 0;
                let totalEvaluation =  performances.value.length;
                performances.value.forEach(function (item) {
                    value += parseFloat(item.performance);
                })
                let moyenneService = value/totalEvaluation;

                return Math.round(moyenneService*100)/100;
            }

            const validationPerformance = async () => {
                if (form.commentaire===''){
                    form.commentaire = 'R.A.S';
                }
                $('#btn-confirm').text('Patientez...');
                $('#btn-confirm').prop('disabled', true);
                let data = reactive({
                    commentaire: form.commentaire,
                    bonus: form.bonus,
                    evaluations: performances.value
                })
                await axios.post('validationCodi/performance/' + form.service +'/'+true, {...data})
                    .then((response) => {
                        if (response.data.data.code === 500) {
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
                                title: 'SUCESS',
                                text: response.data.data.message,
                                icon: 'success',
                            });
                            performances.value = [];
                            getPerformanceService(false);
                            $('#btn-confirm').text('Valider');
                            $('#btn-confirm').prop('disabled', false);
                        }
                    })
                    .catch(error => {
                        catchErrors(error, succesMessage);
                    })
            }

            const displayCurrentAssesment = (idEval,display)=>{
                displayRapport(idEval,display);
            }

            const validationUnitaire = (val,go=false)=>{
                currentEval.value = val;
                let idEval = val.id;
                if (!go){
                    form.idEvaluation = idEval;
                    return 0;
                }else {
                    if(form.commentaireUnitaire===''){
                        form.commentaireUnitaire = 'PONDERATION AU CODI'
                    }
                    let data = reactive({
                        commentaire: form.commentaireUnitaire,
                        bonus: form.bonusUnitaire,
                        evaluations: form.idEvaluation
                    })
                    $('#btn-confirmValid').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
                    axios.post('validationCodi/performance/' + form.service + '/'+false, {...data})
                        .then((response) => {
                            if (response.data.data.code === 500) {
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
                                    title: 'SUCESS',
                                    text: response.data.data.message,
                                    icon: 'success',
                                });
                                performances.value = [];
                                form.annee = '';
                            }
                        })
                        .catch(error => {
                            catchErrors(error, succesMessage);
                        })
                    sleep(100).then(()=>{
                        getPerformanceService(false);
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
                form,
                directions,
                annees,
                performances,
                load,
                form1,
                moyenneService,
                moyenneUsine,
                moyenneInitialeUsine,
                currentEval,
                getserviceByBloc,
                getServiceByDirction,
                getPerformanceService,
                getTotalPerformance,
                validationPerformance,
                displayCurrentAssesment,
                validationUnitaire,
                invalidEvaluation,
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
