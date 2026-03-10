    <template>
    <div>
        <div class=" flex flex-col">
            <titre-application big-title="EVALUATIONS COLLABORATEUR" small-title="Evaluations"
                               :name-page="intitule"></titre-application>
            <div>
                <div v-if="messages!==''" class="alert alert-success">
                    <strong> {{ messages }} </strong>
                </div>
                <div class="mb-2">
                    <div class="row">
                        <div class="mt-1 col-md-2">
                            <br>
                            <router-link :to="{name:'collaborateurs.index',params: { idColab: 0}}"
                                         class="uppercase btn-green text-decoration-none bg-green-600 px-3 py-2 text-white">
                                <i class="fa fa-backspace"></i> Retour
                            </router-link>
                        </div>

<!--                        <div class="col-md-1 offset-3">
                            <label for=""><strong>Filtre</strong></label>
                            <select name="" class="form-select">
                                <option value="">10</option>
                                <option value="">20</option>
                                <option value="">50</option>
                                <option value="">75</option>
                                <option value="">100</option>
                            </select>
                        </div>-->

                        <div class="col-md-3">
                            <label for=""><strong>Filtre Par type</strong></label>
                            <select @change="filtreEvaluation" id="filtre" class="form-select">
                                <option value="EA">Evaluation de fin d'année</option>
                                <option value="EMP">Evaluation à mis-pacours</option>
                            </select>
                        </div>

<!--                        <div class="col-md-3">
                            <label for=""><strong>Filtrer Par Années</strong></label>
                            <select name="" id="" class="form-select">
                                <option selected value="" disabled>Sélectionnez une annéé</option>
                                <option v-for="annee in annees" :key="annee.id" :value=annee.id>
                                    {{ annee.libelle }}
                                </option>
                            </select>
                        </div>-->
                    </div>

                </div>
                <div class="py-1">
                    <div v-if="!loadRessource" class="col-md-2 offset-5 mt-4">
                        <strong style="font-size: 20px;color: #057e72;" class="mb-0">Chargement...</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                        <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
                    </div>
                    <div v-else-if="evaluationsCollaborateur.length>0" class="shadow2">
                        <table class="min-w-full leading-normal table table-bordered table-striped"
                               style="background-color: #acb3c4">
                            <thead>
                            <tr>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider"><b>Année</b></th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider"><b>Type</b></th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider"><b>Accomplissement</b></th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider"><b>Difficultés</b></th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider"><b>Progrès</b></th>
                                <th class="px-10 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider"><b>Valider</b></th>
                                <th class="px-5 py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider"><b>Action</b></th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="evaluation in evaluationsCollaborateur" :key="evaluation.id">
                                <tr>
                                    <td class="px-3 py-2 border-b border-gray-200 bg-white text-md"
                                        v-text="evaluation.annee"></td>
                                    <td class="px-3 py-2 border-b border-gray-200 bg-white text-md"
                                        v-text="evaluation.typeEva"></td>
                                    <td class="px-3 py-2 border-b border-gray-200 bg-white text-md"
                                        v-text="evaluation.accomplissement"></td>
                                    <td class="px-3 py-2 border-b border-gray-200 bg-white text-md"
                                        v-text="evaluation.difficulteMission"></td>
                                    <td class="px-3 py-2 border-b border-gray-200 bg-white text-md"
                                        v-text="evaluation.progres"></td>
                                    <td class="px-3 py-2 border-b border-gray-200 bg-white text-md">
                                        <strong class="bg-green-300 text-green-800 px-2" v-if="evaluation.clotureResp">
                                            Oui</strong>
                                        <strong class="bg-red-300 text-red-800 px-2" v-else> En attente</strong>
                                    </td>
                                    <td class="px-3 py-2 border-b border-gray-200 bg-white text-sm">
                                        <button v-if="!load" type="button"
                                                @click="rapportEvaluation(evaluation.id,false)"
                                                title="Télécharger le rapport"
                                                class="btn-sm bg-blue-600 text-white px-2 py-1 rounded">
                                            <i class="fa fa-file-pdf"></i></button>

                                      <!--  <button v-else type="button" title="Télécharger le rapport"
                                                class="btn-sm bg-blue-600 text-white px-2 py-1 rounded">
                                            <i class="fa fa-file-pdf"></i></button>-->&nbsp;&nbsp;&nbsp;&nbsp;

                                        <button v-if="typeEva==='EA'"
                                                @click="displayCurrentAssesment(evaluation.id,true)" type="button"
                                                title="Consulter l'evaluation"
                                                class="btn-sm bg-green-600 text-white px-2 py-1 rounded"
                                                data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                            <i class="fa fa-eye-slash"></i>
                                        </button>

                                        <button v-else @click="showEvaluationMipacours(evaluation.id)" type="button"
                                                title="Consulter votre evaluation"
                                                class="btn-sm bg-green-600 text-white px-2 py-1 rounded"
                                                data-bs-toggle="modal" data-bs-target="#staticBackdrop1">
                                            <i class="fa fa-eye-slash"></i>
                                        </button>
                                    </td>

                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="alert alert-info">
                        <b class="text-red-600 uppercase">Aucune ressource disponible dans la base de donnée</b>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div
                class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
                id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered relative w-auto pointer-events-none">
                    <div
                        class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                        <div style="background-color: #acb3c4"
                             class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                            <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="exampleModalLabel">
                                <b><i class="fa fa-question-circle"></i> CONSULTATION AUTO-EVALUATION xxx</b>
                            </h5>
                        </div>
                        <div class="modal-body">
                            <div v-if="!load" class="offset-5 mt-4">
                                <strong style="font-size: 20px;color: #057e72;" class="mb-0">Chargement...</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                                <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
                            </div>
                            <div v-else>
                                <h6 align="right"><b class="text-red-600"
                                                     style="border: black solid 2px;padding: 10px;">Année:
                                    {{form1.annee}}</b></h6>
                                <br>
                                <h6 align="right"><b class="text-red-600"
                                                     style="border: black solid 2px;padding: 10px;">Categorie Prof: {{
                                    form1.codeCategorie }}</b></h6>
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
                                        <td style="border: black 1px solid" class="hauteur"><b>Point</b></td>
                                    </tr>

                                    <tr v-for="objectif in form1.objectifs" style="border: black 1px solid;">
                                        <td style="border: black 1px solid" class="hauteur">{{objectif.libelle}}</td>
                                        <td style="border: black 1px solid" class="hauteur"><b>{{objectif.niveauExecFinal}}</b>
                                        </td>
                                        <td style="border: black 1px solid" class="hauteur">
                                            <b>{{objectif.appreFinal}}</b></td>
                                        <td style="border: black 1px solid" class="hauteur" align="right"><b>{{objectif.noteObtenue}}</b>
                                        </td>
                                    </tr>

                                    <tr class="hauteurTr" style="border: black 1px solid">
                                        <td style="border: black 1px solid" class="hauteur" align="right" colspan="3">
                                            <b>Total</b></td>
                                        <td style="border: black 1px solid" class="hauteur" align="right"><b>{{form1.totalperformance}}</b></td>
                                    </tr>
                                </table>
                                <hr>
                                <br>
                                <h5 align="center">4- EVALUATION DES COMPETENCES</h5>
                                <table class="table table-striped">
                                    <tr style="border: black 1px solid;">
                                        <td style="border: black 1px solid"><b>Critère</b></td>
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
                                                <td style="border: black 1px solid">
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
                                        <td style="border: black 1px solid" class="hauteur" align="right"><b
                                            class="text-red-600">{{form1.totalcompetence}}</b></td>
                                    </tr>
                                    <tr style="border: black 1px solid">
                                        <td style="border: black 1px solid" class="hauteur" align="right"><b>Total(évaluation
                                            de performances + evaluation de compétences)</b></td>
                                        <td style="border: black 1px solid" class="hauteur" align="right"><b class="text-red-600">{{ form1.total }}</b></td>
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
                                <div style="border: black solid 1px;margin-top: 20px; border-radius: 40px;padding: 8px">
                                    <p align="center"><b>commentaire de l'évaluateur</b></p>
                                    <p style="margin-right: 15px;">
                                        {{form1.evaluation.commentaireResp}}
                                        <br>
                                    </p>
                                    <br>
                                </div>

                                <!--<div style="border: black solid 1px;border-radius: 40px;padding: 8px">
                                    <p align="center"><b>commentaire de l'évaluateur</b></p>
                                    <p style="margin-right: 15px;">
                                        {{form.evaluation.commentaireResp}}
                                        <br>
                                    </p>
                                    <table>
                                        <tr style="margin-right: 15px;">
                                            <td class="hauteur"><b>Nom et Prénoms de l'évaluateur:</b></td>
                                            <td class="hauteur">{{form.evaluation.evaluateur}}</td>
                                        </tr>
                                    </table>
                                </div>-->

                            </div>
                        </div>
                        <div class="modal-footer flex items-center justify-end  border-t border-gray-200">
                            <button id="btn-fermer" class="bg-gray-600 hover:bg-gray-600 text-white font-bold py-2 px-4"
                                    data-bs-dismiss="modal">Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal evaluation mi-parcours -->
            <div
                class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
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
                                                <b class="uppercase">Autres réalisations accomplies,ou difficultés
                                                    soulignées à
                                                    mi-parcours
                                                </b>
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
                                                <b class="uppercase">COMMENTAIRE EVALUATEUR</b>
                                            </div>
                                        </td>
                                        <td align="center" style="border: #0a0a0a 2px solid">
                                            <div>
                                                <b class="uppercase">COMMENTAIRE DU SALARIE</b>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr style="border: #0a0a0a 2px solid">
                                        <td style="border: #0a0a0a 2px solid">
                                            {{form.evaluation.commentaireEvaluer}}
                                        </td>
                                        <td style="border: #0a0a0a 2px solid">
                                            {{form.evaluation.commentaireResp}}
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

                            <button v-if="!form.evaluation.clotureResp" @click="displayFormAutoEvaluation"
                                    id="btn-edit-miparcours"
                                    class="btn-sm btn-green text-white font-bold py-2 px-4"
                            ><i class="fa fa-edit"></i>Modifier
                            </button>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import {ref} from "@vue/reactivity";
    import useCollaborateur from "../../services/collaborateurs";
    import {onMounted, reactive} from "vue";
    import TitreApplication from "../block/TitreApplication";
    import axios from "axios";
    import {useStore} from "vuex";
    import Swal from "sweetalert2";
    import appService from "../../services/appService";
    import {useRouter} from "vue-router";

    export default {
        name: "evaluationCollaborateurs",
        components: {TitreApplication},
        props: {
            messages: {
                required: false,
                default: ''
            },
            id: {
                required: true,
                type: String,
            },
            intitule: {
                required: false,
                type: String,
            }
        },
        setup(props) {
            const intitule = ref(props.intitule);
            let user = JSON.parse(sessionStorage.getItem('user'));
            const {load, form1, displayRapport} = appService();

            const {loadRessource, annees, evaluationsCollaborateur, getAnnees, getEvaluationCollaborateur} = useCollaborateur();
            const store = useStore()
            const router = useRouter();
            const typeEva = ref('EA');
            const form = reactive({
                anciennete: '',
                autreRealisation: '',
                evaluation: '',
                objectifs: '',
                tabAccomplissement: '',
                tabDifficulte: '',
                employe: '',
                annee: '',
                fonction: '',
                service: '',
            })
            onMounted(getAnnees)

            onMounted(() => getEvaluationCollaborateur(props.id))
            let ressource = reactive({id: ''});
            const tabId = ref([]);

            const getIdRessource = (id) => {
                ressource.id = id;
            }

            const displayFormAutoEvaluation = () => {
                $('#btn-fermer-mipacours')[0].click();
                router.push({name: 'entretien.mis.pacours', params: {id: form.evaluation.id}});
            }

            const displayCurrentAssesment = (id, display) => {
                displayRapport(id, display);
            }

            const rapportEvaluation = (id, display = false) => {
                let baseUrl = store.state.MIX_API_URL;
                axios.get('check/mission/collaborateur/valider/' + 0 + '/' + id)
                    .then((response) => {
                        if (response.data.data.code === 500) {
                            Swal.fire({
                                title: 'Erreur',
                                text: response.data.data.message,
                                icon: 'error',
                            });
                        } else {
                            load.value = true;
                            window.location.href = baseUrl + 'api/rapport/evaluation/collaborateur/' + user.id + '/' + id + '/' + display;
                        }
                    })
                    .catch((error) => {
                        catchErrors(error, succesMessage);
                    })
            }

            const filtreEvaluation = () => {
                loadRessource.value = false;
                let filtre = $('#filtre').val()
                typeEva.value = filtre;
                axios.get('evaluation/by/type/' + props.id + '/' + filtre + '/' + true)
                    .then((response) => {
                        evaluationsCollaborateur.value = response.data.data;
                        loadRessource.value = true
                    })
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
                            form.annee = resp.annee;
                            load.value = true;
                        }

                    })
            }

            const getLibelleCritere = (libelle,type)=>{
                let libel = libelle.split(',');
                if (type==='note'){
                    return libel[1];
                }else {
                    return libel[0];
                }

            }

            return {
                tabId,
                typeEva,
                ressource,
                evaluationsCollaborateur,
                annees,
                form1,
                load,
                form,
                loadRessource,
                getIdRessource,
                rapportEvaluation,
                displayCurrentAssesment,
                filtreEvaluation,
                showEvaluationMipacours,
                displayFormAutoEvaluation,
                getLibelleCritere,

            }
        }
    }
</script>

<style scoped>
    .modal-lg {
        max-width: 65% !important;
    }

    .ulFaireMarquant {
        list-style-type: none;
        margin-left: -42px
    }

    h5 {
        color: red;
    }

    h6 {
        text-decoration: underline;
    }
</style>
