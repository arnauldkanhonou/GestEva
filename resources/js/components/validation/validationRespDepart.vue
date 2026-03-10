<template>
    <div>
        <div class=" flex flex-col">
            <titre-application big-title="VALIDATION PERFORMANCES" small-title="Validation"
                               name-page="Performances"></titre-application>

            <div class="mt-3 row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="mb-1 block text-base font-medium text-[#07074D]">
                                <b>Année<i class="text-red-600">*</i></b>
                            </label>
                            <select v-model="form.annee" name=""
                                    class="rounded-md form-control">
                                <option value="" readonly>Sélectionnez une année</option>
                                <option v-for="annee in annees" :value="annee.id" :key="annee.id">
                                    {{annee.libelle}}
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="mb-1 block text-base font-medium text-[#07074D]">
                                <b>Services<i class="text-red-600">*</i></b>
                            </label>
                            <select @change="getCollaborateurServices" v-model="form.service" name="" id=""
                                    class="rounded-md form-control">
                                <option value="" readonly>Sélectionnez un service</option>
                                <option v-for="service in services" :value="service.id"
                                        :key="service.id">
                                    {{service.libelle}}
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="mb-1 block text-base font-medium text-[#07074D]">
                                <b>Collaborateurs<i class="text-red-600">*</i></b>
                            </label>
                            <select @change="getEvaluationSalarie" v-model="form.collaborateur" class="rounded-md form-control">
                                <option value="" readonly>Sélectionnez un collaborateur</option>
                                <option v-for="collaborateur in collaborateurs" :value="collaborateur.id"
                                        :key="collaborateur.id">
                                    {{collaborateur.nom}} {{collaborateur.prenoms}}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="mb-1 block text-base font-medium text-[#07074D]">
                                <b>Rubrique<i class="text-red-600">*</i></b>
                            </label>
                            <select @change="getEvaluationSalarie" v-model="form.rubrique" name="" id="rubriq"
                                    class="rounded-md form-control">
                                <option value="" readonly>Sélectionnez une section</option>
                                <option value="mission">Missions principale</option>
                                <option value="bilan">Bilans de l'année dernière</option>
                                <option value="formationSuivie">formations suivie</option>
                                <option value="EvalPerformance">Evaluation performance</option>
                                <option value="EvalCompetence">Evaluation compétence</option>
                                <option value="preocupation">Préocupations & Souhaits</option>
                                <option value="nouvelObjectif">Nouvels Objectifs</option>
                                <option value="developCompetence">Développement de compétences</option>
                                <option value="commentaire">Commentaires</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4" v-if="load">
                        <!-- COMMENTAIRE ET PERFORMANCE REALISEE -->
                        <div class="row">
                            <div class="col-md-3">
                                <h6><b>EVALUATION PERFORMANCE:</b> &nbsp;&nbsp;&nbsp; <b class="text-red-600"
                                                                                         v-text="form.totalPerformance"></b>
                                </h6>
                            </div>
                            <div class="col-md-3">
                                <h6><b>EVALUATION COMPETENCE:</b> &nbsp;&nbsp;&nbsp; <b class="text-red-600"
                                                                                        v-text="form.totalCompetence"></b>
                                </h6>
                            </div>
                            <div class="col-md-2">
                                <h6><b>BONUS OBTENU:</b> &nbsp;&nbsp;&nbsp; <b v-text="form.sommeBonus" class="text-red-600"></b></h6>
                            </div>
                            <div class="col-md-4">
                                <h6><b>PERFORMANCE REALISEE:</b> &nbsp;&nbsp;&nbsp; <b class="text-red-600"
                                                                                       v-text="form.pointGblobal"></b>
                                </h6>
                            </div>
                        </div>
                        <hr>

                        <!--PRINCIPALE MISSIONS-->
                        <section v-if="form.missions.length>0">
                            <br>
                            <h5 class="ml-5" style="text-decoration: underline">MISSIONS</h5>
                            <ul>
                                <li v-for="(mission,index) in form.missions" :key="index">
                                    <i class="fa fa-check text-green-600"></i>
                                    <b>{{ mission.libelle }}</b><br><br>
                                </li>
                            </ul>
                        </section>

                        <!-- BILAN DE L'ANNEE -->
                        <div v-if="form.bilans.length!==0">
                            <br>
                            <section>
                                <h5 class="ml-5" style="text-decoration: underline">ACCOMPLISSEMENTS</h5>
                                <strong style="font-size: 15px;text-decoration: none"
                                        class="small text-success"><i>(Quelles sont les
                                    principales réussites du collaborateurs(missions réussies,objectifs réussis...) au cours
                                    de
                                    l'année?)</i></strong>
                                <br>
                                <div class="row">
                                    <div class="col-md-9 hideInputAccomplissement">
                                        <input v-model="form.accomplissement" type="text"
                                               placeholder="Veuillez saisir votre accomplissement"
                                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
                                    </div>
                                    <div class="col-md-3 hideInputAccomplissement">
                                        <button @click="addFaireMarquant('accomplissement')"
                                                class="btn-green text-decoration-none px-3 py-2 text-white"><i
                                                class="fa fa-plus"></i> Ajouter à la liste
                                        </button>
                                    </div>
                                </div>
                                <br>
                                <!-- v-if="form.tabAccomplissement.length>0"-->
                                <div>
                                    <ul class="alert-success">
                                        <br>
                                        <li v-for="(value,index) in form.bilans.accomplissement" :key="index"><i
                                                class="fa fa-check text-green-600"></i><strong>{{ value }}</strong>&nbsp;&nbsp;&nbsp;
                                             <button @click="removeFaireMarquant('accomplissement',index)"
                                                     class="bg-red-600 hover:bg-red-600 text-white px-1 py-0 rounded"><i
                                                     class="fa fa-minus-circle"></i>&nbsp;Retirer
                                             </button>
                                            <br><br></li>
                                        <br>
                                    </ul>
                                </div>
                                <hr>
                            </section>
                            <section>
                                <h5 class="ml-5" style="text-decoration: underline"> DIFFICULTES MAJEURS</h5>
                                <strong style="font-size: 15px;text-decoration: none"
                                        class="small text-success">
                                    <i>(Quelle sont les
                                        principales difficultés rencontrées par le collaborateur dans la réalisation de ses
                                        missions(maitrise d'un outil informatique, relation client, ect...)?
                                    </i>
                                </strong>
                                <br>

                                <div class="row">
                                    <div class="col-md-9 hideInputDifficulte">
                                        <input v-model="form.difficulte" type="text"
                                               placeholder="Veuillez saisir votre difficulté"
                                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
                                    </div>
                                    <div class="col-md-3 hideInputDifficulte">
                                        <button @click="addFaireMarquant('difficulte')"
                                                class="btn-green text-decoration-none px-3 py-2 text-white"><i
                                                class="fa fa-plus"></i> Ajouter à la liste
                                        </button>
                                    </div>
                                </div>
                                <br>
                                <!--v-if="form.tabDifficultes.length>0"-->
                                <div>
                                    <ul class="alert-success">
                                        <br>
                                        <li v-for="(value,index) in form.bilans.difficultes" :key="index">
                                            <i class="fa fa-check text-green-600"></i><strong> {{ value }}</strong>&nbsp;&nbsp;&nbsp;
                                             <button @click="removeFaireMarquant('difficulte',index)"
                                                     class="bg-red-600 hover:bg-red-600 text-white px-1 py-0 rounded"><i
                                                     class="fa fa-minus-circle"></i>&nbsp;Retirer
                                             </button>
                                            <br><br></li>
                                        <br>
                                    </ul>
                                </div>
                                <hr>
                            </section>
                            <section>
                                <h5 class="ml-5" style="text-decoration: underline"> PROGRES REALISES </h5>
                                <strong style="font-size: 15px;text-decoration: none"
                                        class="small text-success">
                                    <i>
                                        (Quels sont les
                                        principaux progrès réalisé depuis le dernier entretien?)
                                    </i>
                                </strong>
                                <br>

                                <div class="row">
                                    <div class="col-md-9 hideInputProgres">
                                        <input v-model="form.progre" type="text"
                                               placeholder="Veuillez saisir votre accomplissement"
                                               class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
                                    </div>
                                    <div class="col-md-3 hideInputProgres">
                                        <button @click="addFaireMarquant('progres')"
                                                class="btn-green text-decoration-none px-3 py-2 text-white"><i
                                                class="fa fa-plus"></i> Ajouter à la liste
                                        </button>
                                    </div>
                                </div>
                                <br>

                                <div>
                                    <ul class="alert-success">
                                        <br>
                                        <li v-for="(value,index) in form.bilans.progres" :key="index"><i
                                                class="fa fa-check text-green-600"></i>
                                            <strong> {{ value }}</strong>&nbsp;&nbsp;&nbsp;
                                               <button @click="removeFaireMarquant('progres',index)"
                                                       class="bg-red-600 hover:bg-red-600 text-white px-1 py-0 rounded"><i
                                                       class="fa fa-minus-circle"></i>&nbsp;Retirer
                                               </button>
                                            <br><br></li>
                                        <br>
                                    </ul>
                                </div>
                                <hr>
                            </section>
                        </div>

                        <!-- FROMATION SUIVIE -->
                        <section v-if="form.formationsSuivie.length>0">
                            <div class="mt-2">
                                <button data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                        class="uppercase btn-green text-decoration-none px-3 py-2 text-white"><i
                                    class="fa fa-plus"></i> Sélectionnez une formation
                                </button>
                            </div>
                            <table
                                    class="mt-4 min-w-full leading-normal table table-bordered table-striped"
                                    style="background-color:#acb3c4 ">
                                <thead>
                                <tr>
                                    <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                        <b class="text-white">Libelle</b>
                                    </th>
                                    <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                        <b class="text-white">Objectif Visé</b>
                                    </th>
                                    <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                        <b class="text-white">Date</b>
                                    </th>
                                    <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                        <b class="text-white">Bilan</b>
                                    </th>
                                     <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                         <b class="text-white">Action</b>
                                     </th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="(formation,index) in form.formationsSuivie" :key="formation.id">
                                    <tr>
                                        <td class="py-1 border-b border-gray-200 bg-white text-md"
                                            v-text="formation.libelle"></td>
                                        <td class="py-1 border-b border-gray-200 bg-white text-md"
                                            v-text="formation.objectif"></td>
                                        <td class="py-1 border-b border-gray-200 bg-white text-md"
                                            v-text="formation.date"></td>
                                        <td class="py-1 border-b border-gray-200 bg-white text-md"
                                            v-text="formation.bilan"></td>
                                        <td class=" py-1 border-b border-gray-200 bg-white text-sm">
                                            &nbsp;&nbsp;<button @click="removeFormationSuivie(index,formation.id)"
                                                                class="bg-red-600 hover:bg-red-600 text-white px-3 py-2 rounded">
                                            <i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </section>

                        <!-- EVALUATION PERFORMANCE -->
                        <section v-if="form.objectifs.length>0">
                            <section>
                                <br>
                                <strong class="ml-5">
                                    <small style="font-size: 15px;text-decoration: none"><i class="text-success">Mentionné
                                        succintement
                                        vos appréciations et commentaires sur les
                                        résultats obtenus</i></small>
                                </strong>
                                <!--<h5 class="ml-5" style="text-decoration: underline">APPRECIATION DES OBJECTIFS</h5>-->
                                <!-- v-if="form.formationSuivie.length>0" <button data-bs-toggle="modal" data-bs-target="#staticBackdrop1"
                                         class="uppercase btn-green text-decoration-none px-3 py-2 text-white"><i
                                         class="fa fa-plus"></i> Sélectionnez un objectif
                                 </button>
                                 <br>-->
                                <!--<table class="mt-4 min-w-full leading-normal table table-bordered table-striped"
                                       style="background-color:#acb3c4 ">
                                    <thead>
                                    <tr>
                                        <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                            <b class="text-white">Libelle</b>
                                        </th>
                                        <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                            <b class="text-white">Appréciation résultat</b>
                                        </th>
                                        <th class="py-2 px-6 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                            <b class="text-white">Commenter votre résulatat </b>
                                        </th>
                                        &lt;!&ndash;<th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                            <b class="text-white">Action</b>
                                        </th>&ndash;&gt;
                                    </tr>
                                    </thead>
                                    <tbody>
                                    &lt;!&ndash;    <template v-for="(objectif,index) in form.objectifAppreciation" :key="objectif.id">
                                            <tr>
                                                <td class="py-1 border-b border-gray-200 bg-white text-md"
                                                    v-text="objectif.libelle"></td>
                                                <td class="py-1 border-b border-gray-200 bg-white text-md"
                                                    v-text="objectif.apprceiation"></td>
                                                <td class="py-1 border-b border-gray-200 bg-white text-md"
                                                    v-text="objectif.commentaire"></td>
                                                <td class=" py-1 border-b border-gray-200 bg-white text-sm">
                                                    &nbsp;&nbsp;<button @click="deleteAppreciation(index)"
                                                                        class="bg-red-600 hover:bg-red-600 text-white px-3 py-2 rounded">
                                                    <i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        </template>&ndash;&gt;
                                    <template v-for="(objectif,index) in form.objectifs" :key="objectif.id">
                                        <tr>
                                            <td class="py-2 border-b border-gray-200 bg-white text-md"
                                                v-text="objectif.libelle"></td>
                                            <td class="py-2 border-b border-gray-200 bg-white text-md">
                                                <input @click="checkRadioButtonAppreciation('mauvais',objectif.id)"
                                                       class="w-4 h-4"
                                                       type="radio" :name="'appr'+objectif.id"
                                                       :checked="objectif.appreFinal==='Mauvais résultats'"
                                                       value="Mauvais"><b>Mauvais</b>&nbsp;
                                                <input @click="checkRadioButtonAppreciation('moyen',objectif.id)"
                                                       class="w-4 h-4"
                                                       type="radio" :name="'appr'+objectif.id"
                                                       :checked="objectif.appreFinal==='Résultat moyens'"
                                                       value="Moyens"><b>Moyen</b>&nbsp;
                                                <input @click="checkRadioButtonAppreciation('bon',objectif.id)"
                                                       class="w-4 h-4"
                                                       type="radio" :name="'appr'+objectif.id"
                                                       :checked="objectif.appreFinal==='Bons résultats'"
                                                       value="Bons"><b>Bons</b>&nbsp;
                                                <input @click="checkRadioButtonAppreciation('superieur',objectif.id)"
                                                       class="w-4 h-4" type="radio" :name="'appr'+objectif.id"
                                                       :checked="objectif.appreFinal==='Résultats supérieurs aux attentes'"
                                                       value="Superieur aux attentes"><b>Superieur aux attentes</b>
                                            </td>
                                            <td class="py-2 px-6 border-b border-gray-200 bg-white text-md">
                                    <textarea @blur="commentObjectif(objectif.id)" :id="'commentaire'+objectif.id"
                                              class="form-control">{{ objectif.commentaireEvaluer }}</textarea>
                                            </td>
                                        </tr>
                                    </template>
                                    </tbody>
                                </table>
-->
                            </section>
                            <hr>
                            <section>
                                <br>
                                <h5 class="ml-5" style="text-decoration: underline">NIVEAU D'EXECUTION DES OBJECTIFS</h5>
                                <table class="mt-4 min-w-full leading-normal table table-bordered table-striped"
                                       style="background-color:#acb3c4 ">
                                    <thead>
                                    <tr>
                                        <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                            <b class="text-white">Libelle</b>
                                        </th>
                                        <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                            <b class="text-white">Niveau de réalisation des objectifs</b>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <template v-for="(objectif,index) in form.objectifs" :key="objectif.id">
                                        <tr>
                                            <td class="py-2 border-b border-gray-200 bg-white text-md"
                                                v-text="objectif.libelle"></td>
                                            <td class="py-2 border-b border-gray-200 bg-white text-md">
                                                <template v-for="niveau in niveauObjectifs" :key="niveau.id">
                                                    <input @click="checkRadioButtonNiveau(objectif.id,niveau.id)"
                                                           class="w-4 h-4"
                                                           type="radio" :name="'niveauObj'+parseInt(objectif.id)"
                                                           :checked="objectif.niveauExecFinal===niveau.libelle"
                                                           :value="niveau.id"><b>{{ niveau.libelle
                                                    }}(<b class="text-red-600">{{ niveau.point}} point</b>)</b>&nbsp;&nbsp;&nbsp;
                                                </template>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr>
                                        <td class="py-1 border-b border-gray-200 bg-white text-md" align="right">
                                            <strong>Total:</strong></td>
                                        <td class="py-1 border-b border-gray-200 bg-white text-md" align="right"><strong
                                                class="text-red-600" v-text="form.totalPerformance"></strong>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                            </section>
                        </section>

                        <!-- EVALUATION COMPETENCE -->
                        <section v-show="form.loadCritere">
                            <br>
                            <table class="mt-4 min-w-full leading-normal table table-bordered table-striped"
                                   style="background-color:#acb3c4 ">
                                <thead>
                                <tr>
                                    <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                        <b class="text-white">Critères</b>
                                    </th>
                                    <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                        <b class="text-white">Pondération</b>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="(datas,indexCritere) in critereEvaluations" :key="indexCritere">
                                    <template v-for="(critere,index) in datas" :key="index">
                                        <tr>
                                            <td colspan="2" class="bg-gray-600 py-3 border-b border-gray-200 text-md"
                                            ><b v-text="index"></b></td>
                                        </tr>
                                        <template v-for="item in critere" :key="item.id">
                                            <tr>
                                                <td class="py-3 border-b border-gray-200 bg-white text-md"
                                                    v-text="item.libelle"></td>
                                                <td class="py-3 border-b border-gray-200 bg-white text-md">
                                                    <template v-for="ponderation in ponderationCriteres"
                                                              :key="ponderation.id">
                                                        <input @click="checkRadioButtonPonderationCritere(item.id,ponderation.id,indexCritere)"
                                                               class="w-4 h-4" type="radio" :name="'critere'+item.id"
                                                               :checked="checkedInitPonderation(item.id,ponderation.libelle,indexCritere)"
                                                               :value="ponderation.id"><b>{{
                                                        ponderation.libelle }}(<b class="text-red-600">{{
                                                            ponderation.point}}
                                                            point</b>)</b>&nbsp;&nbsp;&nbsp;
                                                    </template>
                                                </td>
                                            </tr>
                                        </template>
                                        <tr>
                                            <td class="py-1 border-b border-gray-200 bg-white text-md" align="right">
                                                <strong>Total:</strong></td>
                                            <td class="py-1 border-b border-gray-200 bg-white text-md" align="right"><strong
                                                    class="text-red-600" :id="'totalCategorie'+indexCritere">0</strong>
                                            </td>
                                        </tr>
                                    </template>
                                </template>
                                <tr>
                                    <td colspan="2" class="bg-gray-600 py-3 border-b border-gray-200 text-md"
                                    ><b>TOTAL GLOBAL</b></td>
                                </tr>
                                <tr>
                                    <td class="py-1 border-b border-gray-200 bg-white text-md" align="right">
                                        <strong>TOTAL CRITERE DE COMPETENCE:</strong></td>
                                    <td class="py-1 border-b border-gray-200 bg-white text-md" align="right"><strong
                                            class="text-red-600" v-text="form.totalCompetence"></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-1 border-b border-gray-200 bg-white text-md" align="right">
                                        <strong>TOTAL(EVALUATION DE PERFORMANCE + EVALUATION DE COMPETENCE):</strong></td>
                                    <td class="py-1 border-b border-gray-200 bg-white text-md" align="right"><strong
                                            class="text-red-600" v-text="form.pointGblobal"></strong>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                        </section>

                        <!-- PREOCUPATIONS ET SOUHAITS -->
                        <section v-if="form.preocupation.length!==0">
                            <br>
                            <h5 style="text-decoration: underline">PREOCUPATIONS ET SOUHAITS</h5>
                            <h6>Comment envisagez-vous votre avenir professionel?</h6>
                            <ul class="ulFaireMarquant alert-success">
                                <li>
                                    <br>
                                </li>
                                <li>
                                    <i class="fa fa-check text-green-600"></i>
                                    {{form.preocupation.avenirProf}}
                                </li>
                                <li>
                                    <br>
                                </li>
                            </ul>
                            <h6>Quelles sont les missions qui vous conviennent le mieux?</h6>

                            <ul class="ulFaireMarquant alert-success">
                                <li>
                                    <br>
                                </li>
                                <li>
                                    <i class="fa fa-check text-green-600"></i>
                                    {{form.preocupation.convenanceMission}}
                                </li>
                                <li>
                                    <br>
                                </li>
                            </ul>

                            <h6>Quelles sont les difficultés que vous rencontrez?</h6>

                            <ul class="ulFaireMarquant alert-success">
                                <li>
                                    <br>
                                </li>
                                <li>
                                    <i class="fa fa-check text-green-600"></i>
                                    {{form.preocupation.difficulteGblobal}}
                                </li>
                                <li>
                                    <br>
                                </li>
                            </ul>

                            <h6>Avez vous des compétences ou des qualités non utilisées?</h6>
                            <ul class="ulFaireMarquant alert-success">
                                <li>
                                    <br>
                                </li>
                                <li>
                                    <i class="fa fa-check text-green-600"></i>
                                    {{form.preocupation.superCompetence}}
                                </li>
                                <li>
                                    <br>
                                </li>
                            </ul>

                        </section>

                        <!-- NOUVEL OBJECTIFS -->
                        <section v-if="tabObjectif.length>0">
                            <br>
                            <h5 class="uppercase" style="text-decoration: underline">Objectifs pour la nouvelle année</h5>
                            <br>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                                        <b>Intitulé de l'objectif<i class="text-red-600">*</i></b>
                                    </label>
                                    <input v-model="form.libelle" type="text" name="libelle"
                                           id="libelle" placeholder="saisir l'objectif"
                                           class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                </div>
                                <div class="col-md-6">
                                    <label for="resultat" class="mb-1 block text-base font-medium text-[#07074D]">
                                        <b> Résultat attendu<i class="text-red-600">*</i></b>
                                    </label>
                                    <input v-model="form.resultat" type="text" name="libelle" id="resultat"
                                           placeholder="saisir l'objectif"
                                           class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label for="echeance" class="mb-1 block text-base font-medium text-[#07074D]">
                                        <b>Echeance<i class="text-red-600">*</i></b>
                                    </label>
                                    <input v-model="form.echeance" type="date" name="libelle" id="echeance"
                                           placeholder="saisir l'objectif"
                                           class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                </div>
                                <div class="col-md-6">
                                    <label for="echeance" class="mb-1 block text-base font-medium text-[#07074D]">
                                        <b> Action clée(s)<i class="text-red-600">*</i></b>
                                    </label>
                                    <input v-model="form.action" type="text" name="libelle" id="actions"
                                           placeholder="Définissez les actions clées qui constituent cet objectifs"
                                           class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                </div>
                                <div class="col-md-2">
                                    <label class="mb-1 block text-base font-medium text-[#07074D]"><b>Ajouter</b></label>
                                    <button @click="addActions(form)"
                                            class="bg-red-600 py-2 px-4 text-white align-content-center"
                                            type="submit">
                                        <b id="btn-add-action"><i class="fa fa-plus"></i></b>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div v-if="form.tabAction.length>0" class="col-md-9 alert-success">
                                    <textarea hidden v-model="form.actions"></textarea><br>
                                    <strong v-for="(action,index) in form.tabAction" :key="index">&nbsp;&nbsp;&nbsp;&nbsp;<i
                                            class="fa fa-check"></i>&nbsp;&nbsp;&nbsp;{{action}} &nbsp;&nbsp;&nbsp;&nbsp;
                                        <button @click="removeAction(form,index)"
                                                class="bg-red-600 hover:bg-red-600 text-white px-1 py-0 rounded"><i
                                                class="fa fa-minus-circle"></i></button>
                                        <br><br></strong>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="col-md-6">
                                    <button @click="addObjectif"
                                            :disabled="form.validerObjectif===true"
                                            :class="form.validerObjectif===true?'rounded btn btn-default text-white':'rounded-md py-2 px-20 text-white align-content-center'"
                                            type="submit" class="btn-green col-md-12">
                                        <b id="btn-save"><i class="fa fa-plus"></i> Ajouter Nouvelle Objectif</b>
                                    </button>
                                </div>
                            </div>
                            <br>
                            <table v-if="tabObjectif.length>0"
                                   class="min-w-full leading-normal table table-bordered table-striped"
                                   style="background-color: #556e6e">
                                <thead>
                                <tr>
                                    <th class="py-2 border-b-2 border-gray-600  text-left text-md font-semibold uppercase tracking-wider">
                                        <b class="text-white">Libelle</b>
                                    </th>
                                    <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                        <b class="text-white">Resultat attendu</b>
                                    </th>
                                    <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                        <b class="text-white">Echeance</b>
                                    </th>
                                    <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                        <b class="text-white">Action clées</b>
                                    </th>
                                    <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                        <b class="text-white">Action</b>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="(objectif,index) in tabObjectif" :key="objectif.id">
                                    <tr>
                                        <td class="py-1 border-b border-gray-200 bg-white text-md"
                                            v-text="objectif.libelle"></td>
                                        <td class="py-1 border-b border-gray-200 bg-white text-md"
                                            v-text="objectif.resultat"></td>
                                        <td class="py-1 border-b border-gray-200 bg-white text-md"
                                            v-text="objectif.echeance"></td>
                                        <td class="py-1 border-b border-gray-200 bg-white text-md"
                                            v-text="objectif.actions"></td>
                                        <td class=" py-1 border-b border-gray-200 bg-white text-sm">
                                            &nbsp;&nbsp;<button v-if="!deleteSend" title="Modifier l'objectif"
                                                                @click="deleteObjectif(objectif.libelle,index)"
                                                                class="bg-green-600 hover:bg-green-600 text-white px-3 py-2 rounded">
                                            <i class="fa fa-edit"></i></button>
                                            &nbsp;&nbsp;<button v-if="!deleteSend" title="Valider l'objectif"
                                                                :disabled="objectif.valider===true"
                                                                @click="validerObjectif(objectif.libelle)"
                                                                class="btn-sm bg-blue-600 hover:bg-blue-600 text-white px-3 py-2 rounded">
                                            <i class="fa fa-check"></i></button>
                                            &nbsp;&nbsp;<button v-else title="Modifier l'objectif"
                                                                class="bg-green-600 hover:bg-green-600 text-white px-3 py-2 rounded">
                                            <i class="fas fa-circle-notch fa-spin fa-2x"></i></button>
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                            <br>
                        </section>

                        <!-- DEVELOPPEMENT DE COMPETENCE -->
                        <section v-if="form.tabBesoinFormation.length>0">
                            <h5 class="uppercase" style="text-decoration: underline">Développement de compétences</h5>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="besoinF" class="mb-1 block text-base font-medium text-[#07074D]">
                                        <b> Besoin de formation<i class="text-red-600">*</i></b>
                                    </label>
                                    <input v-model="form.besoinFormation.libelle" type="text"
                                           name="libelle" id="besoinF" placeholder="saisir l'objectif"
                                           class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                </div>
                                <div class="col-md-6">
                                    <label for="ennoncerP" class="mb-1 block text-base font-medium text-[#07074D]">
                                        <b> Ennoncer le problème à résoudre<i class="text-red-600">*</i></b>
                                    </label>
                                    <input v-model="form.besoinFormation.enonceProbleme" type="text"
                                           name="libelle" id="ennoncerP" placeholder="saisir l'objectif"
                                           class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <label for="resultatA" class="mb-1 block text-base font-medium text-[#07074D]">
                                        <b> Résultat attendu<i class="text-red-600">*</i></b>
                                    </label>
                                    <textarea v-model="form.besoinFormation.resultat" type="text"
                                              name="libelle" id="resultatA" placeholder="saisir l'objectif"
                                              class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                </div>
                            </div>
                            <div class="row mb-4 mt-3">
                                <div class="col-md-7">
                                    <button @click="setBesoinFormation"
                                            :disabled="form.validerBesoin===true"
                                            :class="form.validerBesoin===true?'rounded btn btn-default text-white':'rounded-md py-2 px-20 text-white align-content-center'"
                                            type="submit" class="btn-green col-md-12">
                                        <b id="btn-save-besoin"><i class="fa fa-plus"></i> Ajouter Besoin de formation</b>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <table v-if="form.tabBesoinFormation.length>0"
                                       class="min-w-full leading-normal table table-bordered table-striped"
                                       style="background-color: #556e6e">
                                    <thead>
                                    <tr>
                                        <th class="py-2 border-b-2 border-gray-600  text-left text-md font-semibold uppercase tracking-wider">
                                            <b class="text-white">Besoin</b>
                                        </th>
                                        <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                            <b class="text-white">Enoncé</b>
                                        </th>
                                        <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                            <b class="text-white">Resultat attendu</b>
                                        </th>
                                        <th class="py-2 border-b-2 border-gray-200  text-left text-md font-semibold uppercase tracking-wider">
                                            <b class="text-white">Action</b>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <template v-for="(besoin,index) in form.tabBesoinFormation" :key="besoin.id">
                                        <tr>
                                            <td class="py-1 border-b border-gray-200 bg-white text-md"
                                                v-text="besoin.libelle"></td>
                                            <td class="py-1 border-b border-gray-200 bg-white text-md"
                                                v-text="besoin.enonce"></td>
                                            <td class="py-1 border-b border-gray-200 bg-white text-md"
                                                v-text="besoin.resultat"></td>
                                            <td class=" py-1 border-b border-gray-200 bg-white text-sm">
                                                &nbsp;&nbsp;<button v-if="!deleteBesoinSend" title="Modifier l'objectif"
                                                                    @click="deleteBesoinFormation(besoin.libelle,index)"
                                                                    class="bg-green-600 hover:bg-green-600 text-white px-3 py-2 rounded">
                                                <i class="fa fa-edit"></i></button>
                                                &nbsp;&nbsp;<button v-else title="Modifier l'objectif"
                                                                    class="bg-green-600 hover:bg-green-600 text-white px-3 py-2 rounded">
                                                <i class="fas fa-circle-notch fa-spin fa-2x"></i></button>
                                            </td>
                                        </tr>
                                    </template>
                                    </tbody>
                                </table>
                            </div>
                        </section>

                        <!-- COMMENTAIRE -->
                        <section v-if="form.displayCommentaire">
                            <br>
                            <h5 class="uppercase" style="text-decoration: underline">Commentaires</h5>
                            <div class="row mt-4">
                                <div class="offset-1 col-md-6">
                                    <label for="synthese" class="mb-1 block text-base font-medium text-[#07074D]">
                                        <b class="uppercase"> Faire une synthèse qualitative<i class="text-red-600">*</i></b>
                                    </label>
                                    <textarea v-model="form.commentaireEvaluer" type="text" name="libelle" id="synthese" placeholder="saisir l'objectif"
                                              class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                </div>

                                <div class="col-md-2">
                                    <label class="mb-1 block text-base font-medium text-[#07074D]">
                                        <b> BONUS <i class="text-red-600">*</i></b>
                                    </label>
                                    <input v-model="form.bonus" type="number" class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                </div>
                                <div class="col-md-2">
                                    <br>
<!--                                    <button @click="setCommentaireEvaluation('evaluateur')" class="btn btn-primary">Enregistrer</button>-->


                                    <button v-if="!form.saveCommentaire" @click="setCommentaireEvaluation('evaluateur')" class="btn btn-primary">Enregistrer</button>

                                    <button v-else class="btn btn-primary btn-sm">
                                        <i class="fas fa-circle-notch fa-spin fa-2x"></i>
                                    </button></div>
                                <br><br>
                            </div>
                            <hr>
                            <div class="offset-2 col-md-8"
                                 style="border: black solid 1px;margin-top: 20px; border-radius: 40px;padding: 8px">
                                <p align="center"><b>commentaire de l'évalué</b></p>
                                <p style="margin-right: 15px;">
                                    {{form.evaluation.commentaireEvaluer}}
                                    <br>
                                </p>
                                <table>
                                    <tr style="margin-right: 15px;">
                                        <td class="hauteur"><b>Date et signature:</b></td>
                                        <td class="hauteur"></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="offset-2 col-md-8" v-for="commentaire in form.commentaires"
                                 style="border: black solid 1px;margin-top: 20px; border-radius: 40px;padding: 8px">
                                <p align="center"><b>commentaire de l'évaluateur(<b
                                        class="text-red-600">{{commentaire.profile}}</b>)</b></p>
                                <p style="margin-right: 15px;">
                                    {{commentaire.libelle}}
                                    <br>
                                </p>
                                <table>
                                    <tr>
                                        <td class="hauteur">BONUS:</td>
                                        <td class="hauteur">{{commentaire.bonus}}</td>
                                        <td class="hauteur"></td>
                                    </tr>
                                    <tr style="margin-right: 15px;">
                                        <td class="hauteur"><b>Date et signature:</b></td>
                                        <td class="hauteur"></td>
                                    </tr>
                                </table>
                                <div class="offset-4 col-md-4">
                                    <button @click="deleteCommentaire(commentaire.id)" class="btn btn-danger">Annuler votre commentaire</button>
                                </div>
                            </div>

                        </section>

                    </div>
                    <div v-else>
                        <div v-if="form.start" class="col-md-2 offset-5 mt-4"><br>
                            <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
                        </div>
                    </div>
                </div>
              <!--  <div class="vl col-md-6">
                    <h5 class="uppercase" style="text-decoration: underline">Performance service</h5>
                    <div v-if="evaluations.length>0" class="mt-3 row">
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
                    <div>
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
                                    <b class="text-white">Performance </b>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-if="evaluations.length>0">
                                <tr v-for="salarie in evaluations">
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
                                </tr>
                                <tr>
                                    <td colspan="4" class="py-2 border-b border-gray-200 bg-white text-md"><b>PERFORMANCE
                                        SERVICE</b></td>
                                    <td align="right" class="py-2 border-b border-gray-200 bg-white text-md"><b class="text-red-600">{{getTotalPerformance()}}</b>
                                    </td>
                                </tr>
                            </template>

                            <template v-else>
                                <tr>
                                    <td colspan="5" class="py-2 border-b border-gray-200 bg-white text-md">
                                        AUCUNE RESSOURCES TROUVEE
                                    </td>
                                </tr>

                            </template>
                            </tbody>
                        </table>

                    </div>
                    <div v-if="form.startEva" class="col-md-2 offset-5 mt-4"><br>
                        <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
                    </div>
                </div>-->

            </div>
        </div>
        <!-- Modal formation -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                    <div style="background-color: #acb3c4"
                         class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                        <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="exampleModalLabel">
                            <b><i class="fa fa-question-circle"></i> Formulaire de formation suivie</b>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="container">
                                <div class="col-md-12">
                                    <label for="unite" class="mb-1 block text-base font-medium text-[#07074D]">
                                        <b>Formations<i class="text-red-600">*</i></b>
                                    </label>
                                    <select @change="getFormationchoose" v-model="form.formation.id" name="" id="unite"
                                            class="rounded-md form-select">
                                        <option value="" readonly>Sélectionnez une formation suivie</option>
                                        <option v-for="formation in formations" :key="formation.id"
                                                :value="formation.id">
                                            {{formation.libelle}}
                                        </option>
                                    </select>
                                </div>
                                <br>
                                <div v-show="form.formation.objectif">
                                    <div class="col-md-12">
                                        <label for="unite" class="mb-1 block text-base font-medium text-[#07074D]">
                                            <b>Objectif Visé<i class="text-red-600">*</i></b>
                                        </label>
                                        <textarea v-model="form.formation.objectif" type="text"
                                                  class="hideFormationInput mt-2 form-control" readonly/>
                                    </div>
                                    <br>
                                    <div class="col-md-12">
                                        <label for="unite" class="mb-1 block text-base font-medium text-[#07074D]">
                                            <b>Date Formation<i class="text-red-600">*</i></b>
                                        </label>
                                        <input v-model="form.formation.date" type="date"
                                               class="hideFormationInput mt-2 form-control">
                                    </div>
                                    <br>
                                    <div class="col-md-12">
                                        <label for="unite" class="mb-1 block text-base font-medium text-[#07074D]">
                                            <b>Bilan de la formation<i class="text-red-600">*</i></b>
                                        </label>
                                        <textarea v-model="form.formation.bilan" type="text"
                                                  class="hideFormationInput mt-2 form-control"
                                                  placeholder="Ex: En générale cette formation a beaucoup apportée pour moi"/>
                                    </div>
                                    <br>
                                    <div class="mt-2 col-md-12">
                                        <button @click="addFormation" id="btn-confirm"
                                                class="col-md-12 uppercase btn-green text-white font-bold py-2 px-4">
                                            Ajouter
                                        </button>
                                    </div>
                                </div>
                            </div>

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
    </div>
</template>

<script>
    import TitreApplication from "../block/TitreApplication";
    import {reactive} from "@vue/reactivity";
    import appService from "../../services/appService";
    import {onMounted, ref} from "vue";
    import useCollaborateur from "../../services/collaborateurs";
    import useCampagneObjectif from "../../services/campagneObjectif";
   import axios from 'axios';
    import useCategorieCritere from "../../services/categorieCritere";
    import useNiveauExecutionObjectifs from "../../services/niveauExecutionObjectifs";
    import usePonderationCritere from "../../services/ponderationcritere";
    import Swal from "sweetalert2";
    import useObjectifs from "../../services/objectifservices";

    export default {
        name: "validationRespDepart",
        components: {TitreApplication},

        setup(){
            let form = reactive({
                tabAccomplissement: [],
                difficultes: '',
                difficulte: '',
                tabDifficultes: [],
                progres: '',
                progre: '',
                tabProgres: [],
                formation: {
                    id: '',
                    libelle: '',
                    objectif: '',
                    date: '',
                    bilan: '',
                },
                formationSuivie: [],
                collaborateur: '',
                service:'',
                rubrique: '',
                cateCritere: '',
                annee: '',
                start: false,
                startEva: false,
                evaluation: false,
                missions: [],
                preocupation: [],
                bilans: [],
                objectifDejaApprecierExecution: [],
                objectifDejaApprecierNiveau: [],
                objectifs: [],
                objectif: {
                    id: '',
                    apprceiation: '',
                    type: '',
                },
                totalCompetence: 0,
                totalPerformance: 0,
                pointGblobal: 0,
                critereEvaluerExistant: [],
                loadCritere: false,
                totalByCatgorieCritere: [],
                libelle: '',
                resultat: '',
                echeance: '',
                action: '',
                actions: '',
                tabAction: [],
                validerObjectif: false,
                besoinFormation: {
                    libelle: '',
                    enonceProbleme: '',
                    resultat: '',
                },
                tabBesoinFormation: [],
                validerBesoin: false,
                commentaires: [],
                commentaireEvaluer: '',
                bonus: 0,
                displayCommentaire: false,
                saveCommentaire: false,
                sommeBonus:0,
            });
            const {catchErrors} = appService();
            const succesMessage = ref([]);
            const services = ref([]);
            const evaluations = ref([]);
            const statistique = ref([]);
            let deleteSend = ref(false);
            let salaries = ref(false);
            let deleteBesoinSend = ref(false);
            const tabObjectif = ref([]);
            const load = ref(false);
            let user = JSON.parse(sessionStorage.getItem('user'));

            const sleep = (ms) => {
                return new Promise(resolve => setTimeout(resolve, ms));
            }

            const {collaborateurs, getCollaborateurs} = useCollaborateur();
            /*onMounted(() => getCollaborateurs(user.id));*/

            const {annees, getAnnes} = useCampagneObjectif();
            onMounted(getAnnes);

            onMounted(()=>{
                axios.get('services/by/departement/'+false)
                .then((response)=>{
                    services.value = response.data.data.services
                })
            });

            const getCollaborateurServices = () => {
                form.startEva = true;
                evaluations.value = [];
                axios.get('collaborateur/by/service/'+form.service+'/'+form.annee)
                .then((response)=>{
                    collaborateurs.value = response.data.data.salaries;
                    evaluations.value = response.data.data.evaluations;
                    statistique.value = response.data.data.statisticService;
                    form.startEva = false;
                })
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

            const {categorieCriteres, getCategorieCriteres} = useCategorieCritere();
            onMounted(getCategorieCriteres);

            const {niveauObjectifs, getNiveauObjectifs} = useNiveauExecutionObjectifs();

            const {
                objectifs, critereEvaluations, tabCitereEvaluer,
                getObjectifCollaborateurValider,
                getCritereEvaluationCollaborateur,
            } = useCollaborateur();

            const {ponderationCriteres, getPonderations} = usePonderationCritere();

            onMounted(getNiveauObjectifs);
            onMounted(getPonderations);

            const sendRequestEvaluation = () => {
                axios.post("validation/performance", form)
                    .then((response) => {
                        if (response.data.data.code === 500) {
                            Swal.fire({
                                title: 'Erreur serveur',
                                text: response.data.data.message,
                                icon: 'error',
                            });
                        } else {
                            form.totalPerformance = Math.round(response.data.data.performance.totalPerformance * 100) / 100;
                            form.sommeBonus = response.data.data.performance.bonus;
                            form.pointGblobal = parseFloat(response.data.data.evaluation.performanceRealiser) + form.sommeBonus;
                            form.totalCompetence = Math.round(response.data.data.performance.totalCompetence * 100) / 100;
                            form.evaluation = response.data.data.evaluation;
                            form.critereEvaluerExistant = response.data.data.critereEvaluer;
                            form.totalByCatgorieCritere = response.data.data.pointTotalByCatgorieCritere;
                            form.missions = response.data.data.missions;
                            form.bilans = response.data.data.bilans;
                            form.tabAccomplissement = response.data.data.bilans.accomplissement;
                            form.tabProgres = response.data.data.bilans.progres;
                            form.tabDifficultes = response.data.data.bilans.difficultes;
                            form.formationsSuivie = response.data.data.formationSuivie;
                            form.objectifs = response.data.data.objectifs;
                            form.pointTotalNiveau = response.data.data.pointTotalObjectifExecute;
                            form.preocupation = response.data.data.preocupation;
                            tabObjectif.value = response.data.data.tabObjectifNouvel;
                            form.tabBesoinFormation = response.data.data.developpementCompt;
                            form.commentaires = response.data.data.commentaires;
                            form.accomplissements = form.evaluation.accomplissement
                            form.difficultes = form.evaluation.difficulteMission
                            form.progres = form.evaluation.progres

                            load.value = true;
                            if (form.rubrique === 'EvalCompetence') {
                                form.start = false;
                                form.loadCritere = true;
                                sleep(300).then(() => {
                                    let tabCodeCategorie = form.totalByCatgorieCritere;
                                    for (let i = 0; i < tabCodeCategorie.length; ++i) {
                                        let point = tabCodeCategorie[i].pointTotal;
                                        let nbrCritere = tabCodeCategorie[i].nbreCritere;
                                        if (point === null) {
                                            point = 0;
                                        }
                                        let moyenne = parseFloat(point) / parseInt(nbrCritere);
                                        let valTotal = Math.round(moyenne * 100) / 100;
                                        $('#totalCategorie' + tabCodeCategorie[i].code).html(valTotal);
                                    }
                                })
                            } else {
                                if (form.rubrique !=='commentaire'){
                                    form.displayCommentaire = false;
                                }
                                form.start = false;
                            }
                        }
                    })
                    .catch((error) => {
                        catchErrors(error, succesMessage);
                    })
            }

            const getEvaluationSalarie = () => {
                if (form.annee === '') {
                    alert("veuillez choisir une année");
                    return;
                }
                if (form.collaborateur === '') {
                    alert("veuillez choisir un collaborateur");
                    return;
                }
                if (form.rubrique === '') {
                    alert("veuillez choisir une rubrique à afficher");
                    return;
                }
                if (form.rubrique ==='commentaire'){
                    form.displayCommentaire = true;
                }
                load.value = false;
                form.start = true;
                form.loadCritere = false;
                if (form.rubrique === 'EvalCompetence') {
                    getCritereEvaluationCollaborateur(user.id, form.evaluation.id)
                    sleep(300).then(() => {
                        sendRequestEvaluation();
                    })
                } else {
                    sendRequestEvaluation();
                }

            }

            const commentObjectif = (id) => {
                form.objectif.commentaire = $('#commentaire' + id).val();
                form.objectif.type = 'evaluer';
                form.objectif.id = id;
                axios.post('add/commentaire/objectif/collaborateur/' + user.id, {...form.objectif})
                    .then((response) => {
                    })
                    .catch((error) => {
                        catchErrors(error, succesMessage);
                    })
            }

            const checkRadioButtonAppreciation = (fieldName, idObjectif) => {
                switch (fieldName) {
                    case 'mauvais':
                        form.objectif.apprceiation = 'Mauvais résultats';
                        break;
                    case 'moyen':
                        form.objectif.apprceiation = 'Résultat moyens';
                        break;
                    case 'bon':
                        form.objectif.apprceiation = 'Bons résultats';
                        break;
                    case 'superieur':
                        form.objectif.apprceiation = 'Résultats supérieurs aux attentes';
                        break;
                }

                form.objectif.id = idObjectif;
                form.objectif.type = 'evaluateur';
                axios.post('add/apprciation/objectif/collaborateur/' + user.id, {...form.objectif})
                    .then((response) => {
                    })
                    .catch((error) => {
                        catchErrors(error, succesMessage);
                    })

            }

            const checkRadioButtonNiveau = (idObjectif, idNiveau) => {
                let data = reactive({
                    idObjectif: idObjectif,
                    idNiveau: idNiveau,
                    type: 'evaluer'
                })
                axios.post('add/niveau/objectif/collaborateur/' + user.id + '/' + form.evaluation.id, {...data})
                    .then((response) => {
                        if (response.data.data.code === 500) {
                            Swal.fire({
                                title: 'Erreur serveur',
                                text: response.data.data.message,
                                icon: 'error',
                            });
                        } else {
                            form.totalPerformance = Math.round(response.data.data.point[0].pointTotal * 100) / 100;
                            form.totalCompetence = Math.round(response.data.data.pointTotalEvaluationCompetence * 100) / 100;
                            form.pointGblobal = parseFloat(form.totalPerformance) + parseFloat(form.totalCompetence);
                        }

                    })
                    .catch((error) => {
                        catchErrors(error, succesMessage);
                    })

            }

            const checkRadioButtonPonderationCritere = (idCritere, idPonderation, codeCategorie) => {
                let data = reactive({
                    idCritere: idCritere,
                    idPonderation: idPonderation,
                    codeCategorie: codeCategorie,
                    type: 'evaluateur'
                })
                axios.post('add/critere/evaluer/' + user.id + '/' + form.evaluation.id, {...data})
                    .then((response) => {
                        let moyenne = parseFloat(response.data.data.pointTotalCatgorieCritere);
                        let valTotal = Math.round(moyenne * 100) / 100;
                        $('#totalCategorie' + codeCategorie).html(valTotal);
                        form.totalCompetence = Math.round(response.data.data.pointTotalEvaluationCompetence * 100) / 100;
                        form.pointGblobal = parseFloat(form.totalPerformance) + parseFloat(form.totalCompetence);
                    })
                    .catch(error => {
                        catchErrors(error, succesMessage);
                    })

                /*let datas = form.tabCitereEvaluer;
                let erreur = false;*/

                /*datas.forEach(function (item, indexe) {
                    if (item.id === idCritere && erreur === false) {
                        erreur = true;
                    } else {
                    }
                });

                if (!erreur) {
                    let data = tabCitereEvaluer.value;
                    let found = false;
                    let critere = '';
                    for (let i = 0; i < data.length; ++i) {
                        if (parseInt(idCritere) === data[i].id && found === false) {
                            critere = data[i];
                            found = true;
                        }
                    }

                    form.tabCitereEvaluer.push(critere);
                }*/

            }

            const checkedInitPonderation = (idCritere, libellePonderation, codeCategorie) => {
                let found = false;
                let data = form.critereEvaluerExistant;
                let critere = '';
                for (let i = 0; i < data.length; ++i) {
                    if (parseInt(idCritere) === parseInt(data[i].critere_evaluation_id) && data[i].apprFinal === libellePonderation) {
                        if (!found) {
                            critere = data[i];
                            found = true;
                        }
                    } else {
                    }
                }

                return found;

            }

            const {tabError, createObjectif, addActions, removeAction} = useObjectifs();

            const addObjectif = () => {
                if (form.libelle === '' || form.resultat === '' || form.actions === '' || form.echeance === '') {
                    erreur = true;
                    alert('Veuillez saisir toutes les informations de votre objectif! Merci')
                    return 0;
                }

                let erreur = false;
                tabObjectif.value.forEach(function (item, indexe) {
                    if (item.libelle === form.libelle && erreur === false) {
                        erreur = true;
                    }

                });
                if (!erreur) {
                    form.validerObjectif = true;
                    let currentObjectif = [];
                    let objectData = {
                        'resultat': form.resultat,
                        'echeance': form.echeance,
                        'actions': form.actions,
                        'libelle': form.libelle,
                    }
                    currentObjectif.push(objectData);

                    $('#btn-save').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                    let data = reactive({
                        'tabObjectif': currentObjectif,
                        'type': 'formEntretien',
                        'idEvaluation': form.evaluation.id
                    })
                    //tabObjectif.value = [];
                    axios.post('objectifs', {...data})
                        .then((response) => {
                            if (response.data.data.code === 500) {
                                form.libelle = '';
                                form.resultat = '';
                                form.actions = '';
                                form.tabAction = [];
                                form.action = '';
                                $('#btn-save').html('Ajouter Nouvelle Objectif');
                                form.validerObjectif = false;
                                Swal.fire({
                                    title: 'Erreur serveur',
                                    text: response.data.data.message,
                                    icon: 'error',
                                });
                            } else {
                                tabObjectif.value.push(objectData);
                                form.libelle = '';
                                form.resultat = '';
                                form.actions = '';
                                form.tabAction = [];
                                form.action = '';
                                $('#btn-save').html('Ajouter Nouvelle Objectif');
                                form.validerObjectif = false;
                                Swal.fire({
                                    title: 'SUCCES',
                                    text: 'Objectif ajouté avec succès dans la base',
                                    icon: 'success',
                                });
                            }
                        })
                        .catch(error => {
                            $('#btn-save').html('Ajouter Nouvel Objectif')
                            let createCustomerErrors = error.response.data.errors;
                            if (error.response.status === 422) {
                                for (const key in createCustomerErrors) {
                                    tabError.value += error.response.data.errors[key][0] + '| ';
                                }
                            }
                            catchErrors(error, succesMessage);
                        })

                } else
                    alert('Cet objectif existe déjà dans la liste. Veuillez saisir autre objectif');
            }

            const deleteObjectif = (libelle, index) => {
                deleteSend.value = true;
                let data = tabObjectif.value;
                let trouver = false;
                let objectif = '';
                for (let i = 0; i < data.length; ++i) {
                    if (libelle === data[i].libelle && !trouver) {
                        trouver = true;
                        objectif = data[i];
                    }
                }
                if (trouver) {
                    let data = reactive({
                        'libelle': objectif.libelle,
                    });
                    axios.post('destroy/objectif/by/libelle/' + user.id + '/' + form.evaluation.id, {...data})
                        .then((response) => {
                            if (response.data.data.code === 500) {
                                Swal.fire({
                                    title: 'Erreur serveur',
                                    text: response.data.data.message,
                                    icon: 'error',
                                });
                            } else {
                                form.libelle = objectif.libelle;
                                form.resultat = objectif.resultat;
                                form.echeance = objectif.echeance;
                                form.actions = objectif.actions;
                                form.tabAction = form.actions.split(';')
                                tabObjectif.value.splice(index, 1);
                            }
                            deleteSend.value = false;
                        })
                        .catch((error) => {
                            catchErrors(error, succesMessage);
                        })
                }

            }

            const validerObjectif = (libelle) => {
                deleteSend.value = true;
                let data = {
                    'libelle': libelle
                }
                axios.post('validation/nouvelle/objectifs/' + form.evaluation.id, data)
                    .then((response) => {
                        if (response.data.data.code === 500) {

                        } else {
                            deleteSend.value = false;
                            tabObjectif.value = response.data.data.objectifNouvel;
                            Swal.fire({
                                title: 'SUCCES',
                                text: 'Objectif validé avec succès',
                                icon: 'success',
                            });
                        }
                    })
                    .catch((error) => {
                        catchErrors(error, succesMessage);
                    })
            }

            const setBesoinFormation = async () => {
                if (form.besoinFormation.libelle === '' || form.besoinFormation.resultat === '' || form.besoinFormation.enonceProbleme === '') {
                    alert('Veuillez saisir toutes les informations de votre besoin de formation! Merci')
                    return 0;
                }

                let erreur = false;
                form.tabBesoinFormation.forEach(function (item, indexe) {
                    if (item.libelle === form.besoinFormation.libelle && erreur === false) {
                        erreur = true;
                    }

                });

                if (!erreur) {
                    $('#btn-save-besoin').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                    form.validerBesoin = true;
                    let data = reactive({
                        libelle: form.besoinFormation.libelle,
                        enonce: form.besoinFormation.enonceProbleme,
                        resultat: form.besoinFormation.resultat,
                    })
                    form.tabBesoinFormation.push({...data})
                    await axios.post('add/besoin/formation/collaborateur/' + user.id + '/' + form.evaluation.id, {...data})
                        .then((response) => {
                            if (response.data.code === 500) {

                            } else {
                                form.besoinFormation.libelle = '';
                                form.besoinFormation.resultat = '';
                                form.besoinFormation.enonceProbleme = '';
                                $('#btn-save-besoin').html('Ajouter Besoin de formation');
                                form.validerBesoin = false;
                                Swal.fire({
                                    title: 'SUCCES',
                                    text: 'Besoin de formation ajouté avec succès dans la base',
                                    icon: 'success',
                                });
                            }
                        })
                        .catch((error) => {
                            catchErrors(error, succesMessage);
                        })
                } else
                    alert('Ce besoin existe déjà dans la liste. Veuillez saisir autre besoin');

            }

            const deleteBesoinFormation = (libelle, index) => {
                deleteBesoinSend.value = true;
                let data = form.tabBesoinFormation;
                let trouver = false;
                let besoin = '';
                for (let i = 0; i < data.length; ++i) {
                    if (libelle === data[i].libelle && !trouver) {
                        trouver = true;
                        besoin = data[i];
                    }
                }
                if (trouver) {
                    let data = reactive({
                        'libelle': besoin.libelle,
                    });
                    axios.post('destroy/besoin/formation/by/libelle/' + user.id + '/' + form.evaluation.id, {...data})
                        .then((response) => {
                            form.besoinFormation.libelle = besoin.libelle;
                            form.besoinFormation.resultat = besoin.resultat;
                            form.besoinFormation.enonceProbleme = besoin.enonce;
                            form.tabBesoinFormation.splice(index, 1);
                            deleteBesoinSend.value = false;
                        })
                        .catch((error) => {
                            catchErrors(error, succesMessage);
                        })
                }

            }

            const setCommentaireEvaluation = async (type) => {
                let data = reactive({
                    commentaire: form.commentaireEvaluer,
                    bonus: form.bonus,
                    type: type
                })
                form.saveCommentaire = true;
                await axios.post('add/commentaire/evaluation/collaborateur/' + user.id + '/' + form.evaluation.id, {...data})
                    .then((response) => {
                        if (response.data.data.code===500){
                            Swal.fire({
                                title: 'Erreur serveur',
                                text:  response.data.data.message,
                                icon: 'error',
                            });
                        }else {
                            form.commentaires = response.data.data.commentaires;
                            form.commentaireEvaluer = '';
                            form.bonus = 0;
                            Swal.fire({
                                title: 'SUCESS',
                                text:  response.data.data.message,
                                icon: 'success',
                            });
                            form.saveCommentaire = false;
                        }
                    })
                    .catch(error => {
                        catchErrors(error, succesMessage);
                    })
            }

            const deleteCommentaire = async (idCommentaire) => {
                await axios.post('delete/commentaire/evaluation/'+idCommentaire)
                    .then((response) => {
                        if (response.data.data.code===500){
                            Swal.fire({
                                title: 'Erreur serveur',
                                text:  response.data.data.message,
                                icon: 'error',
                            });
                        }else {
                            form.commentaires = response.data.data.commentaires;
                            Swal.fire({
                                title: 'SUCCES',
                                text:  response.data.data.message,
                                icon: 'success',
                            });
                        }
                    })
                    .catch(error => {
                        catchErrors(error, succesMessage);
                    })
            }

            let data = reactive({
                faireMarquand: '',
                type: ''
            })

            const addFaireMarquant = (type) => {
                data.type = type;
                if (type === 'accomplissement') {
                    if (form.accomplissement === '') {
                        alert('Veuillez saisir votre accomplissement');
                        return;
                    }
                    form.accomplissements = (form.accomplissements ? form.accomplissements + ';' + form.accomplissement : form.accomplissement);
                    form.tabAccomplissement.push(form.accomplissement);
                    data.faireMarquand = form.accomplissements;
                    form.accomplissement = '';
                }
                if (type === 'difficulte') {
                    if (form.difficulte === '') {
                        alert('Veuillez saisir votre difficulté');
                        return;
                    }
                    form.difficultes = (form.difficultes ? form.difficultes + ';' + form.difficulte : form.difficulte);
                    form.tabDifficultes.push(form.difficulte);
                    data.faireMarquand = form.difficultes;
                    form.difficulte = '';
                }

                if (type === 'progres') {
                    if (form.progre === '') {
                        alert('Veuillez saisir votre progrès');
                        return;
                    }
                    form.progres = (form.progres ? form.progres + ';' + form.progre : form.progre);
                    form.tabProgres.push(form.progre);
                    data.faireMarquand = form.progres;
                    form.progre = '';
                }

                axios.post('add/faire/marquant/collaborateur/' + user.id + '/' + form.evaluation.id+'/param', {...data})
                    .then((response) => {
                    })
                    .catch((error) => {
                        catchErrors(error, succesMessage);
                    })

            }

            const removeFaireMarquant = (type, index,update=false) => {
                data.type = type;
                if (type === 'accomplissement') {
                    if (update){
                        form.accomplissement = form.tabAccomplissement[index];
                    }
                    form.tabAccomplissement.splice(index, 1);
                    if (form.tabAccomplissement.length > 0) {
                        form.accomplissements = '';
                        for (let i = 0; i < form.tabAccomplissement.length; ++i) {
                            if (form.accomplissements !== '') {
                                form.accomplissements += form.tabAccomplissement[i] + ';';
                            } else {
                                form.accomplissements = form.tabAccomplissement[i];
                            }
                        }
                    } else
                        form.accomplissements = '';

                    data.faireMarquand = form.accomplissements;
                }

                if (type === 'difficulte') {
                    if (update){
                        form.difficulte = form.tabDifficultes[index];
                    }
                    form.tabDifficultes.splice(index, 1);
                    if (form.tabDifficultes.length > 0) {
                        form.difficultes = '';
                        for (let i = 0; i < form.tabDifficultes.length; ++i) {
                            if (form.difficultes !== '') {
                                form.difficultes += form.tabDifficultes[i] + ';';
                            } else {
                                form.difficultes = form.tabDifficultes[i];
                            }
                        }
                    } else
                        form.difficultes = '';
                    data.faireMarquand = form.difficultes;
                }

                if (type === 'progres') {
                    if (update){
                        form.progre = form.tabProgres[index];
                    }
                    form.tabProgres.splice(index, 1);
                    if (form.tabProgres.length > 0) {
                        form.progres = '';
                        for (let i = 0; i < form.tabProgres.length; ++i) {
                            if (form.progres !== '') {
                                form.progres += form.tabProgres[i] + ';';
                            } else
                                form.progres = form.tabProgres[i];
                        }
                    } else
                        form.progres = '';
                    data.faireMarquand = form.progres;
                }

                axios.post('add/faire/marquant/collaborateur/' + user.id + '/' + form.evaluation.id+'/param', {...data})
                    .then((response) => {
                        //console.log(response)
                    })
                    .catch((error) => {
                        catchErrors(error, succesMessage);
                    })

            }

            const addFormation = () => {
                $('#btn-submit').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                if (form.formation.libelle === '' || form.formation.objectif === '' || form.formation.date === '') {
                    erreur = true;
                    alert('Veuillez saisir les informations de la formation suivie! Merci')
                    return 0;
                }
                let erreur = false;
                form.formationSuivie.forEach(function (item, indexe) {
                    if (item.libelle === form.libelle && erreur === false) {
                        erreur = true;
                    } else {
                    }
                });

                if (!erreur) {
                    axios.post('add/formation/suivie/collaborateur/' + user.id + '/' + form.evaluation.id+'/param', {...form.formation})
                        .then((response) => {
                            form.formationSuivie.push({...form.formation});
                            form.formation.id = '';
                            form.formation.libelle = '';
                            form.formation.objectif = '';
                            form.formation.date = '';
                            $('#btn-submit').html('Ajouter');
                            $('#btn-fermer')[0].click();
                        })
                        .catch((error) => {
                            catchErrors(error, succesMessage);
                        })

                } else
                    alert('Cette formation existe déjà dans la liste. Veuillez choisir une autre');
            };

            const removeFormationSuivie = (index, idFormation) => {
                let data = reactive({
                    idFormation: idFormation,
                });
                axios.post('remove/formation/suivie/collaborateur/' + user.id + '/' +form.evaluation.id+'/param', {...data})
                    .then((response) => {
                        form.formationSuivie.splice(index, 1);
                    })
                    .catch((error) => {
                        catchErrors(error, succesMessage);
                    })
            }

            return {
                form,
                load,
                collaborateurs,
                annees,
                services,
                evaluations,
                getCollaborateurServices,
                categorieCriteres,
                niveauObjectifs,
                ponderationCriteres,
                critereEvaluations,
                tabObjectif,
                deleteSend,
                deleteBesoinSend,
                statistique,
                getEvaluationSalarie,
                checkRadioButtonAppreciation,
                checkRadioButtonNiveau,
                commentObjectif,
                checkRadioButtonPonderationCritere,
                checkedInitPonderation,
                addActions,
                removeAction,
                addObjectif,
                deleteObjectif,
                validerObjectif,
                deleteBesoinFormation,
                setBesoinFormation,
                setCommentaireEvaluation,
                deleteCommentaire,
                getTotalPerformance,
                addFaireMarquant,
                removeFaireMarquant,
                addFormation,
                removeFormationSuivie
            }
        }
    }
</script>

<style scoped>
    .vl {
        border-left: 3px solid #057e72;
        height: auto;
    }
</style>
