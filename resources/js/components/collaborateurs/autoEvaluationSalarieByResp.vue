<template>
    <div>
        <div hidden id="ancre">rien de spécial</div>
        <titre-application big-title="AUTO-EVALUATION-DE" small-title="Collaborateur" :name-page="intitule"></titre-application>
        <div v-if="load">
            <ul class="nav nav-pills nav-justified" id="myTab" role="tablist">
                <li class="nav-item bg-red-600" id="nav1" role="presentation">
                    <button style="cursor:default" class="px-3 py-3 text-white active step" id="tab1" type="button"
                            role="tab"
                            aria-controls="home" aria-selected="true">Bilan de l'année dernière <i id="tab-i-1"></i>
                    </button>
                </li>
                <li class="nav-item" id="nav2" role="presentation">
                    <button style="cursor:default" class="px-3 py-3 bg-gray-500 step" id="tab2" type="button" role="tab"
                            aria-controls="profile" aria-selected="false">Eva. des performances <i id="tab-i-2"></i>
                    </button>
                </li>

                <li class="nav-item" id="nav3" role="presentation">
                    <button style="cursor:default" class="px-3 py-3 bg-gray-500 step" id="tab3" type="button" role="tab"
                            aria-controls="contact" aria-selected="false">Eva. des compétences <i id="tab-i-3"></i>
                    </button>
                </li>
                <li class="nav-item" id="nav4" role="presentation">
                    <button style="cursor:default" class="px-3 py-3 bg-gray-500 step" id="tab4" type="button" role="tab"
                            aria-controls="contact" aria-selected="false">Préoccupation & souhaits <i id="tab-i-4"></i>
                    </button>
                </li>
                <li class="nav-item" id="nav5" role="presentation">
                    <button style="cursor:default" class="px-3 py-3 bg-gray-500 step" id="tab5" type="button" role="tab"
                            aria-controls="contact" aria-selected="false">Objectifs & formations. <i id="tab-i-5"></i>
                    </button>
                </li>
            </ul>
            <br>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tab-content1" role="tabpanel" aria-labelledby="home-tab">
                    <h4 align="center"><strong>FAITS MARQUANTS(Résumé des réussites, difficultés et progrès)</strong>
                    </h4>
                    <br>
                    <section>
                        <h4>Accomplissements</h4>
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
                        <div>
                            <ul class="alert-success">
                                <br>
                                <li v-for="(value,index) in form.tabAccomplissement" :key="index"><i
                                    class="fa fa-check text-green-600"></i><strong>{{ value }}</strong>&nbsp;&nbsp;&nbsp;
                                    <button @click="removeFaireMarquant('accomplissement',index,true)"
                                            class="bg-green-600 hover:bg-green-600 text-white px-1 py-0 rounded"><i
                                        class="fa fa-edit"></i>&nbsp;Modifier
                                    </button>&nbsp;&nbsp;&nbsp;
                                    <button @click="removeFaireMarquant('accomplissement',index,false)"
                                            class="bg-red-600 hover:bg-red-600 text-white px-1 py-0 rounded"><i
                                        class="fa fa-minus-circle"></i>&nbsp;Retirer
                                    </button>
                                    <br><br></li>
                                <br>
                            </ul>
                        </div>
                        <hr>
                    </section>
                    <section id="sectionDifficultes">
                        <h4> Difficultés majeurs</h4>
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

                        <div>
                            <ul class="alert-success">
                                <br>
                                <li v-for="(value,index) in form.tabDifficultes" :key="index">
                                    <i class="fa fa-check text-green-600"></i><strong> {{ value }}</strong>&nbsp;&nbsp;&nbsp;
                                    <button @click="removeFaireMarquant('difficulte',index,true)"
                                            class="bg-green-600 hover:bg-green-600 text-white px-1 py-0 rounded"><i
                                        class="fa fa-edit"></i>&nbsp;Modifier
                                    </button>&nbsp;&nbsp;&nbsp;
                                    <button @click="removeFaireMarquant('difficulte',index,false)"
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
                        <h4> Progrès Réalisés </h4>
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
                                <li v-for="(value,index) in form.tabProgres" :key="index"><i
                                    class="fa fa-check text-green-600"></i>
                                    <strong> {{ value }}</strong>&nbsp;&nbsp;&nbsp;
                                    <button @click="removeFaireMarquant('progres',index,true)"
                                            class="bg-green-600 hover:bg-green-600 text-white px-1 py-0 rounded"><i
                                        class="fa fa-edit"></i>&nbsp;Modifier
                                    </button>&nbsp;&nbsp;&nbsp;
                                    <button @click="removeFaireMarquant('progres',index,false)"
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
                        <h4 align="center"><strong>Formations suivies l'année dernière</strong></h4>
                        <strong style="font-size: 15px;text-decoration: none"
                                class="small text-success">
                            <i>
                                (Vous sélectionnez une formation si et seulement si vous avez suivie cette
                                dernière.)
                            </i>
                        </strong>
                        <div class="mt-2">
                            <button data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                    class="uppercase btn-green text-decoration-none px-3 py-2 text-white"><i
                                class="fa fa-plus"></i> Sélectionnez une formation
                            </button>
                        </div>
                        <br>
                        <table v-if="form.formationSuivie.length>0"
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
                            <template v-for="(formation,index) in form.formationSuivie" :key="formation.id">
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

                </div>
                <div class="tab-pane fade" id="tab-content2" role="tabpanel" aria-labelledby="profile-tab">
                    <strong>
                        <small style="font-size: 15px;text-decoration: none"><i class="text-success">Mentionné
                            succintement
                            vos appréciations et commentaires sur les
                            résultats obtenus</i></small>
                    </strong><br><br>
                    <hr>
                    <section>
                        <h4 class="title">Niveau d'exécution de vos objectifs</h4>

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
                            <template v-for="(objectif,index) in objectifs" :key="objectif.id">
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
                                    class="text-red-600" v-text="form.pointTotalNiveau"></strong>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </section>
                </div>
                <div class="tab-pane fade" id="tab-content3" role="tabpanel" aria-labelledby="contact-tab">
                    <section>
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
                                    class="text-red-600" v-text="form.pointTotalEvaluation"></strong>
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
                </div>
                <div class="tab-pane fade" id="tab-content4" role="tabpanel" aria-labelledby="contact-tab">
                    <div class="col-md-12 mt-3">
                        <label class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Comment envisagez-vous votre avenir professionnel</b>
                        </label>
                        <textarea @blur="setPreoccupation('avenir')" v-model="form.avenirProf" cols="8" rows="5"
                                  type="text"
                                  class="mt-2 form-control"></textarea>
                    </div>
                    <hr>
                    <div class="col-md-12 mt-3">
                        <label class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Quelles sont les missions qui vous conviennent le mieux?</b>
                        </label>
                        <textarea @blur="setPreoccupation('mission')" v-model="form.convenanceMission" cols="8" rows="5"
                                  type="text" class="mt-2 form-control"></textarea>
                    </div>
                    <hr>
                    <div class="col-md-12 mt-3">
                        <label class="mb-1 block text-base font-medium text-[#07074D]">
                            <b>Quelles sont les difficultés que vous rencontrez?</b>
                        </label>
                        <textarea @blur="setPreoccupation('difficulte')" v-model="form.difficulteGlobal" cols="8"
                                  rows="5"
                                  type="text" class="mt-2 form-control"></textarea>
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="mb-1 block text-base font-medium text-[#07074D]">
                            <b> Avez-vous des compétences ou des qualités non utilisées? si oui, lesquelles?</b>
                        </label>
                        <textarea @blur="setPreoccupation('supCompetence')" v-model="form.superCompetence" cols="8"
                                  rows="5"
                                  type="text" class="mt-2 form-control"></textarea>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab-content5" role="tabpanel" aria-labelledby="contact-tab">
                    <section>
                        <h4 class="title">Objectifs pour la nouvelle année</h4>
                        <div v-if="form.messages!==''" class="alert alert-success">
                            <strong> {{ form.messages }} </strong>
                        </div>
                        <hr>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="libelle" class="mb-1 block text-base font-medium text-[#07074D]">
                                    <b>Intitulé de l'objectif<i class="text-red-600">*</i></b>
                                </label>
                                <input :class="Errors.libelle?'is-invalid':''" @keyup="validateInputlibelle"
                                       @blur="validateInputlibelle" v-model="form.libelle" type="text"
                                       name="libelle"
                                       id="libelle" placeholder="saisir l'objectif"
                                       class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                <strong v-if="Errors.libelle" class="text-red-600">{{Errors.libelle }}</strong>
                            </div>
                            <div class="col-md-7">
                                <label for="echeance" class="mb-1 block text-base font-medium text-[#07074D]">
                                    <b> Action clée(s)<i class="text-red-600">*</i></b>
                                </label>
                                <input :class="Errors.action?'is-invalid':''" @keyup="validateInputAction"
                                       @blur="validateInputAction" v-model="form.action" type="text"
                                       name="libelle"
                                       id="actions"
                                       placeholder="Définissez les actions clées qui constituent cet objectifs"
                                       class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                <strong v-if="Errors.action" class="text-red-600">{{Errors.action }}</strong>

                            </div>
                            <div class="col-md-1">
                                <label class="mb-1 block text-base font-medium text-[#07074D]"><b>Ajouter</b></label>
                                <button @click="addActions(form)"
                                        class="bg-red-600 py-2 px-4 text-white align-content-center"
                                        type="submit">
                                    <b id="btn-add-action"><i class="fa fa-plus"></i></b>
                                </button>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="resultat" class="mb-1 block text-base font-medium text-[#07074D]">
                                    <b> Résultat attendu<i class="text-red-600">*</i></b>
                                </label>
                                <input :class="Errors.resultat?'is-invalid':''" @keyup="validateInputResultat"
                                       @blur="validateInputResultat" v-model="form.resultat" type="text"
                                       name="libelle"
                                       id="resultat" placeholder="saisir l'objectif"
                                       class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                <strong v-if="Errors.resultat" class="text-red-600">{{Errors.resultat
                                    }}</strong>
                            </div>
                            <div class="col-md-5">
                                <label for="echeance" class="mb-1 block text-base font-medium text-[#07074D]">
                                    <b>Echeance<i class="text-red-600">*</i></b>
                                </label>
                                <input :class="Errors.echeance?'is-invalid':''" @keyup="validateInputEcheance"
                                       @blur="validateInputEcheance" v-model="form.echeance" type="date"
                                       name="libelle"
                                       id="echeance" placeholder="saisir l'objectif"
                                       class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                <strong v-if="Errors.echeance" class="text-red-600">{{Errors.echeance }}</strong>
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
                                        type="submit" class="btn-green col-md-6">
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

                    <section>
                        <hr>
                        <h4 class="title">Développement de compétences</h4>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="besoinF" class="mb-1 block text-base font-medium text-[#07074D]">
                                    <b> Besoin de formation<i class="text-red-600">*</i></b>
                                </label>
                                <input v-model="form.besoinFormation.libelle"
                                       type="text"
                                       name="libelle"
                                       id="besoinF" placeholder="saisir l'objectif"
                                       class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                                <!--                            <strong v-if="Errors.libelle" class="text-red-600">{{Errors.libelle }}</strong>-->
                            </div>
                            <div class="col-md-6">
                                <label for="ennoncerP" class="mb-1 block text-base font-medium text-[#07074D]">
                                    <b> Ennoncer le problème à résoudre<i class="text-red-600">*</i></b>
                                </label>
                                <input v-model="form.besoinFormation.enonceProbleme" type="text" name="libelle" id="ennoncerP" placeholder="saisir l'objectif" class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>

                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <label for="resultatA" class="mb-1 block text-base font-medium text-[#07074D]">
                                    <b> Résultat attendu<i class="text-red-600">*</i></b>
                                </label>
                                <textarea v-model="form.besoinFormation.resultat"
                                          type="text"
                                          name="libelle"
                                          id="resultatA" placeholder="saisir l'objectif"
                                          class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                            </div>
                        </div>
                        <div class="row mb-4 mt-3">
                            <div class="col-md-6">
                                <button @click="setBesoinFormation"
                                        :disabled="form.validerBesoin===true"
                                        :class="form.validerBesoin===true?'rounded btn btn-default text-white':'rounded-md py-2 px-20 text-white align-content-center'"
                                        type="submit" class="btn-green col-md-6">
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

                    <section>
                        <hr>
                        <h4 class="title">Commentaire</h4>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <label for="synthese" class="mb-1 block text-base font-medium text-[#07074D]">
                                    <b> Faire une synthèse qualitative<i class="text-red-600">*</i></b>
                                </label>
                                <textarea @blur="setCommentaireEvaluation('evaluer')" v-model="form.commentaireEvaluer"
                                          type="text"
                                          name="libelle"
                                          id="synthese" placeholder="saisir l'objectif"
                                          class="w-full rounded-md form-control bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                            </div>
                        </div>
                    </section>

                </div>
            </div>
            <br>
            <div class="row">
                <div class="offset-3 col-md-6">
                    <button data-bs-toggle="modal" data-bs-target="#staticBackdrop2" id="validationEvaluation"
                            v-show="form.nbrStep===5"
                            class="col-md-12 bg-green-600 px-4 py-2 text-white" type="button"><b>VALIDER L'EVALUATION</b>
                    </button>
                </div>
            </div>
            <br>
            <div class="mt-6">
                <div class="row">
                    <div class="col-md-4 offset-4">
                        <button v-show="form.nbrStep>=2" @click="displayStep('previous')"
                                class="bg-red-600 px-4 py-2 text-white" type="button">Etape
                            précédente
                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button v-show="form.nbrStep<5" @click="displayStep('next')"
                                class="btn-green px-4 py-2 text-white"
                                type="button">Etape suivante
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- se cond bloc à revoir -->
        <div v-else class=" mt-4">
            <br>
            <br>
            <div class="col-md-3 offset-5" v-if="code_retour===200">
                <strong style="font-size: 20px;color: #057e72;" class="mb-0">Chargement...</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                <i style="color: #057e72;" class="fas fa-spinner fa-spin fa-3x ml-5 mt-2"></i>
            </div>
            <div v-else class="col-md-12 alert alert-danger">
                {{ messageErreur }}
            </div>
        </div>

        <button hidden data-bs-toggle="modal" data-bs-target="#staticBackdrop1" id="btnAvertissement">ook</button>

        <a id="afficherDebut" hidden @click="scrollMeTo"> cliquez </a> <br>

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
                                        <option value="autre">Autres formations</option>
                                        <option v-for="formation in formations" :key="formation.id"
                                                :value="formation.id">
                                            {{formation.libelle}}
                                        </option>
                                    </select>
                                </div>
                                <br>
                                <div v-if="!form.formation.autre">
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
                                <div v-else>
                                    <div class="col-md-12">
                                        <label class="mb-1 block text-base font-medium text-[#07074D]">
                                            <b>Libelle de la formation<i class="text-red-600">*</i></b>
                                        </label>
                                        <textarea v-model="form.formation.libelle" type="text"
                                                  class="hideFormationInput mt-2 form-control"/>
                                    </div>
                                    <br>
                                    <div class="col-md-12">
                                        <label for="unite" class="mb-1 block text-base font-medium text-[#07074D]">
                                            <b>Objectif visé<i class="text-red-600">*</i></b>
                                        </label>
                                        <textarea v-model="form.formation.objectif" type="text"
                                                  class="hideFormationInput mt-2 form-control"
                                                  placeholder="Ex: En générale cette formation a beaucoup apportée pour moi"/>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="mb-1 block text-base font-medium text-[#07074D]">
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
                                        <button @click="addFormation"
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

        <!-- Modal avertissement -->
        <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                    <div style="background-color: #acb3c4"
                         class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                        <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="exampleModalLabel1">
                            <b><i class="fa fa-question-circle"></i> Avertissement</b>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <p>
                                <strong class="text-red-600" v-text="form.messageAvertissement"></strong>
                            </p>
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

        <!-- Modal niveau exécution objectif -->
        <div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                    <div style="background-color: #acb3c4"
                         class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                        <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="exampleModalLabel2">
                            <b><i class="fa fa-question-circle"></i>Avertissement</b>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <!--<div class="container">
                            <div class="col-md-12">
                                <label class="mb-1 block text-base font-medium text-[#07074D]">
                                    <b>Objectifs de l'an dernier<i class="text-red-600">*</i></b>
                                </label>
                                <select v-model="form.objectif.id" name="" class="rounded-md form-select">
                                    <option value="" readonly>Sélectionnez un objectif</option>
                                    <option v-for="objectif in objectifs" :key="objectif.id" :value="objectif.id">
                                        {{objectif.libelle}}
                                    </option>
                                </select>
                            </div>
                            <br>
                            <div>
                                <div class="col-md-12">
                                    <label for="niveau" class="mb-1 block text-base font-medium text-[#07074D]">
                                        <b>Niveau exécution<i class="text-red-600">*</i></b>
                                    </label>
                                    <select v-model="form.objectif.id" name="" id="niveau"
                                            class="rounded-md form-select">
                                        <option value="" readonly>Sélectionnez un objectif</option>
                                        <option v-for="niveau in niveauObjectifs" :key="niveau.id"
                                                :value="niveau.id">
                                            {{niveau.libelle}}
                                        </option>
                                    </select>
                                </div>
                                <br>
                                <div class="col-md-12">
                                    <label class="mb-1 block text-base font-medium text-[#07074D]">
                                        <b>Commentaire<i class="text-red-600">*</i></b>
                                    </label>
                                    <textarea v-model="form.objectif.commentaire" type="text"
                                              class="mt-2 form-control"/>
                                </div>
                                <br>

                                <div class="mt-2 col-md-12">
                                    <button @click="addObjectifs" id="btn-confirm-niveau-objectif"
                                            class="col-md-12 uppercase btn-green text-white font-bold py-2 px-4">
                                        Ajouter
                                    </button>
                                </div>
                            </div>
                        </div>-->
                        <strong class="text-red-600">
                            Cette action va valider votre évaluation et vous ne serez plus en
                            mesure d'apporter des modifications sur cette dernière.
                            <br>Voulez-vous vraiment validé l'évaluation ?
                        </strong>
                    </div>
                    <div class="modal-footer flex items-center justify-end  border-t border-gray-200">
                        <button id="btn-fermerValidation"
                                class="bg-gray-600 hover:bg-gray-600 text-white font-bold py-2 px-3"
                                data-bs-dismiss="modal">Non
                        </button> &nbsp; &nbsp; &nbsp; &nbsp;
                        <button @click="clotureEvaluation('evaluer')" id="btn-valider"
                                class="btn-green text-white font-bold py-2 px-3">Oui
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal formation -->
        <div class="modal fade" id="staticBackdrop3" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-clip-padding rounded-md outline-none text-current">
                    <div style="background-color: #acb3c4"
                         class="modal-header flex flex-shrink-0 items-center justify-between  border-b border-gray-200 rounded-t-md">
                        <h5 class="text-red-600 font-medium leading-normal text-gray-800" id="exampleModalLabel3">
                            <b><i class="fa fa-question-circle"></i> Ajout de commentaire</b>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="container">
                                <div class="col-md-12">
                                    <label for="unite" class="mb-1 block text-base font-medium text-[#07074D]">
                                        <b>Commenté votre résultat<i class="text-red-600">*</i></b>
                                    </label>
                                    <textarea type="text" class="mt-2 form-control"/>
                                </div>
                            </div>

                        </div>


                    </div>
                    <div class="modal-footer flex items-center justify-end  border-t border-gray-200">
                        <button id="btn-fermer3" class="bg-gray-600 hover:bg-gray-600 text-white font-bold py-2 px-4"
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
    import {useRouter} from "vue-router";
    import {onMounted, reactive, ref} from "vue";
    import appService from "../../services/appService";
    import axios from "axios";
    import Swal from "sweetalert2";
    import useFormation from "../../services/formationservices";
    import useNiveauExecutionObjectifs from "../../services/niveauExecutionObjectifs";
    import useCollaborateur from "../../services/collaborateurs";
    import usePonderationCritere from "../../services/ponderationcritere";
    import useFormValidation from "../../validation/useFormValidation";
    import useObjectifs from "../../services/objectifservices";

    export default {
        name: "autoEvaluationSalarieByResp",
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
                required: true,
                type: String,
            }
        },
        setup(props) {
            const router = useRouter();
            let load = ref(false);
            let code_retour = ref(200);
            const messageErreur = ref('');
            let deleteSend = ref(false);
            let deleteBesoinSend = ref(false);
            const tabObjectif = ref([]);
            const succesMessage = ref([]);
            const form = reactive({
                nbrStep: 1,
                accomplissements: '',
                accomplissement: '',
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
                    autre: '',
                },
                formationSuivie: [],
                objectif: {
                    id: '',
                    libelle: '',
                    type: '',
                    apprceiation: '',
                    commentaire: '',
                },
                objectifAppreciation: [],
                objectifExecution: [],
                libelle: '',
                resultat: '',
                echeance: '',
                action: '',
                actions: '',
                tabAction: [],
                validerObjectif: false,
                pointTotalNiveau: 0,
                pointTotalEvaluation: 0,
                pointGblobal: 0,
                avenirProf: '',
                convenanceMission: '',
                difficulteGlobal: '',
                superCompetence: '',
                besoinFormation: {
                    libelle: '',
                    enonceProbleme: '',
                    resultat: '',
                },
                tabBesoinFormation: [],
                validerBesoin: false,
                commentaireEvaluer: '',
                messages: '',
                stepLibelle: 'bilanAnnuel',
                messageAvertissement: '',
                tabCitereEvaluer: [],
                critereEvaluerExistant: '',
                pointTotalByCatgorieCritere: '',
                initialize: false,
                increment: 0,
            });
            const evaluation = ref('');
            const {dasboardName, catchErrors} = appService();
            const scrollMeTo = () => {
                let element = $("#ancre");
                let top = element.offsetTop;
                window.scrollTo(0, top);
            }
            const sleep = (ms) => {
                return new Promise(resolve => setTimeout(resolve, ms));
            }

            /** initialisation des variable au chargement du formulaire */
            onMounted(() => {
                axios.get('init/evaluation/collaborateur/' + props.id+'/autoEvaluationSalarieByResp')
                    .then((response) => {
                        if (response.data.data.code === 500) {
                            let resp = response.data.data;
                            form.accomplissements = resp.evaluation.accomplissement;
                            form.difficultes = resp.evaluation.difficulteMission;
                            form.progres = resp.evaluation.progres;
                            form.tabAccomplissement = resp.tabAccomplissement;
                            form.tabDifficultes = resp.tabDifficulte;
                            form.tabProgres = resp.tabProgres;
                            form.formationSuivie = resp.formationSuivie
                            form.critereEvaluerExistant = resp.critereEvaluer;
                            form.pointTotalByCatgorieCritere = resp.pointTotalByCatgorieCritere;
                            form.avenirProf = resp.evaluation.AvenirProfs;
                            form.convenanceMission = resp.evaluation.convenanceMission;
                            form.difficulteGlobal = resp.evaluation.difficulteGblobal;
                            form.superCompetence = resp.evaluation.superCompetence;
                            evaluation.value = resp.evaluation;
                            form.commentaireEvaluer = resp.evaluation.commentaireEvaluer;
                            form.objectifAppreciation = resp.objectifDejaApprecierNiveau;
                            form.objectifExecution = resp.objectifDejaApprecierExecution;
                            tabObjectif.value = resp.tabObjectif;
                            form.tabBesoinFormation = resp.tabBesoin;
                            form.pointTotalNiveau = resp.pointTotalObjectifExecute;
                            sleep(8000).then(() => {
                                let tabCodeCategorie = form.pointTotalByCatgorieCritere;
                                let total = 0;
                                for (let i = 0; i < tabCodeCategorie.length; ++i) {
                                    let point = tabCodeCategorie[i].pointTotal;
                                    let nbrCritere = tabCodeCategorie[i].nbreCritere;
                                    if (point === null) {
                                        point = 0;
                                    }
                                    let moyenne = parseFloat(point) / parseInt(nbrCritere);
                                    let valTotal = Math.round(moyenne * 100) / 100;
                                    $('#totalCategorie' + tabCodeCategorie[i].code).html(valTotal);
                                    total += valTotal;
                                }
                                form.pointTotalEvaluation = parseFloat(total);
                                form.pointGblobal = parseFloat(form.pointTotalNiveau) + parseFloat(form.pointTotalEvaluation);
                            })
                            load.value = true;
                            code_retour.value=500;
                        } else if (response.data.data.code === 403) {
                            //normalement orienté sur le tableau de bord du collaborateur
                            //Swal.fire(response.data.data.message);
                            code_retour.value=403;
                            messageErreur.value=response.data.data.message;
                        } else {
                            evaluation.value = response.data.data;
                            load.value = true;
                        }
                    })
                    .catch((error) => {
                        console.log(error.response)
                    });

            });

            const {formations, getFormationsForEvaluation} = useFormation();

            const {niveauObjectifs, getNiveauObjectifs} = useNiveauExecutionObjectifs();

            const {
                objectifs, critereEvaluations, tabCitereEvaluer,
                getObjectifCollaborateurValider,
                getCritereEvaluationCollaborateur,
            } = useCollaborateur();

            const {ponderationCriteres, getPonderations} = usePonderationCritere();

            onMounted(() => getFormationsForEvaluation(props.id,'autoEvaluationSalarieByResp'));

            onMounted(() => getObjectifCollaborateurValider(props.id,'autoEvaluationSalarieByResp'));

            onMounted(() => getCritereEvaluationCollaborateur(props.id,0,'autoEvaluationSalarieByResp',));

            onMounted(getNiveauObjectifs);

            onMounted(getPonderations);

            const displayStep = (type) => {
                $('#afficherDebut')[0].click();
                let count = $('.step').length;
                let found = false;
                let index = '';
                if (type === 'next') {
                    if (form.stepLibelle === 'bilanAnnuel') {
                        if (form.accomplissements === '' || form.difficultes === '' || form.progres === '') {
                            form.messageAvertissement = 'Veuillez renseigner tous les faits marquants avant de passer à l\'étape suivante.'
                            $('#btnAvertissement')[0].click();
                            return;
                        }
                    }

                    if (form.stepLibelle === 'evaluationPerformance') {
                        //let nbrObjectif = parseInt(objectifs.value.length) * 2;
                        let nbrObjectif = parseInt(objectifs.value.length);
                        //let totalGlobal = parseInt(form.objectifAppreciation.length) + parseInt(form.objectifExecution.length);
                        let totalGlobal = parseInt(form.objectifExecution.length);
                        if (totalGlobal < nbrObjectif) {
                            form.messageAvertissement = 'Veuillez donner une appréciation ainsi que le niveau d\'exécution de tout vos objectifs avant de passer à l\'étape suivante .'
                            $('#btnAvertissement')[0].click();
                            return;
                        }

                    }

                    if (form.stepLibelle === 'evaluationCpmpetence') {
                        let nbrCritere = parseInt(tabCitereEvaluer.value.length);
                        let totalGlobal = parseInt(form.tabCitereEvaluer.length);
                        if (totalGlobal < nbrCritere) {
                            form.messageAvertissement = 'Veuillez évaluer tous les critères d\'évaluation avant de passé à une autre étape.'
                            $('#btnAvertissement')[0].click();
                            return;
                        }
                    }

                    if (form.stepLibelle === 'preocupationSouhait') {
                        if (form.avenirProf === '' || form.difficulteGlobal === '' || form.convenanceMission === '' || form.superCompetence === '') {
                            form.messageAvertissement = 'Veuillez renseigné tous les champs de l\'étape préocupation & souhait avant de passé à l\'étape suivante.'
                            $('#btnAvertissement')[0].click();
                            return;
                        }
                    }

                    if (form.nbrStep === 5) {
                        return;
                    }
                    if (form.nbrStep <= count - 1) {
                        for (let i = 1; i <= count; ++i) {
                            if ($('#tab' + i).hasClass('active') && found === false) {
                                index = i;
                                found = true;
                            }
                        }

                        if (found) {
                            $('#tab' + index).removeClass('active bg-gray-500').addClass('text-white');
                            $('#tab-content' + index).removeClass('show active');
                            $('#nav' + index).addClass('btn-green');
                            $('#tab-i-' + index).addClass('fa fa-check');
                            let next = index + 1;
                            $('#tab' + next).removeClass('bg-gray-500').addClass('active text-white');
                            $('#nav' + next).addClass('bg-red-600');
                            $('#tab-content' + next).addClass('show active');
                            form.nbrStep++;
                            if (form.nbrStep === 2)
                                form.stepLibelle = 'evaluationPerformance';

                            if (form.nbrStep === 3)
                                form.stepLibelle = 'evaluationCpmpetence';

                            if (form.nbrStep === 4)
                                form.stepLibelle = 'preocupationSouhait';

                            if (form.nbrStep === 5)
                                form.stepLibelle = 'objectifFormation';

                            if (index === 4) {
                                $('#tab5').removeClass('bg-gray-500').addClass('text-white');
                                $('#nav5').addClass('btn-green');
                                /*$('#tab-i-5').addClass('fa fa-check');*/
                            }
                            index = '';
                            found = false;

                        }
                    }
                }

                if (type === 'previous') {
                    if (form.nbrStep === 1) {
                        return;
                    }
                    for (let i = count; i >= 1; --i) {
                        if ($('#tab' + i).hasClass('active') && found === false) {
                            index = i;
                            found = true;
                        }
                    }

                    if (found) {
                        $('#tab' + index).removeClass('active').addClass('bg-gray-500');
                        $('#tab-content' + index).removeClass('show active');
                        $('#nav' + index).removeClass('btn-green bg-red-600');
                        $('#tab-i-' + index).removeClass('fa fa-check');
                        let prev = index - 1;
                        $('#tab' + prev).addClass('active text-white');
                        $('#nav' + prev).removeClass('btn-green').addClass('bg-red-600');
                        $('#tab-content' + prev).addClass('show active');
                        $('#tab-i-' + prev).removeClass('fa fa-check');
                        form.nbrStep--;
                        if (form.nbrStep === 2)
                            form.stepLibelle = 'evaluationPerformance';

                        if (form.nbrStep === 3)
                            form.stepLibelle = 'evaluationCpmpetence';

                        if (form.nbrStep === 4)
                            form.stepLibelle = 'preocupationSouhait';

                        if (form.nbrStep === 5)
                            form.stepLibelle = 'objectifFormation';
                    }
                }

            }

            const checkedInitPonderation = (idCritere, libellePonderation, codeCategorie) => {
                if (form.stepLibelle === 'evaluationCpmpetence') {
                    //console.log('ook');
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
                    if (found) {
                        form.tabCitereEvaluer.push(critere);
                    }
                    return found;
                }

            }

            const showInput = (type) => {
                if (type === 'accomplissement') {
                    $('.hideInputAccomplissement').show('slow');
                    $('#hideBtnAccomplissement').hide('slow');
                }
                if (type === 'difficulte') {
                    $('.hideInputDifficulte').show('slow');
                    $('#hideBtnDifficulte').hide('slow');
                }
                if (type === 'progres') {
                    $('.hideInputProgres').show('slow');
                    $('#hideBtnProgres').hide('slow');
                }
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

                axios.post('add/faire/marquant/collaborateur/' + props.id + '/' + evaluation.value.id+'/autoEvaluationSalarieByResp', {...data})
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

                axios.post('add/faire/marquant/collaborateur/' + props.id + '/' + evaluation.value.id+'/autoEvaluationSalarieByResp', {...data})
                    .then((response) => {
                        //console.log(response)
                    })
                    .catch(error => {
                        catchErrors(error, succesMessage);
                    })

            }

            const getFormationchoose = () => {
                if (form.formation.id === 'autre') {
                    form.formation.autre = true;
                } else {
                    form.formation.autre = false;
                    let data = formations.value;
                    let found = false;
                    let formation = '';
                    for (let i = 0; i < data.length; ++i) {
                        if (form.formation.id == data[i].id && found === false) {
                            formation = data[i];
                            found = true;
                        }
                    }
                    if (found) {
                        form.formation.objectif = formation.objectifVise;
                        form.formation.date = formation.dateFormation;
                        form.formation.libelle = formation.libelle;
                    }
                    if (form.formation.objectif) {
                        $('.hideFormationInput').show('slow');
                    }
                }
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
                    axios.post('add/formation/suivie/collaborateur/' + props.id + '/' + evaluation.value.id+'/autoEvaluationSalarieByResp', {...form.formation})
                        .then((response) => {
                            if (form.formation.autre){
                                form.formation.id = response.data.data.formation.id;
                            }
                            form.formationSuivie.push({...form.formation});
                            form.formation.libelle = '';
                            form.formation.objectif = '';
                            form.formation.date = '';
                            form.formation.id = '';
                            form.formation.autre = false;
                            $('#btn-submit').html('Ajouter');
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
                axios.post('remove/formation/suivie/collaborateur/' + props.id + '/' + evaluation.value.id+'/autoEvaluationSalarieByResp', {...data})
                    .then((response) => {
                        form.formationSuivie.splice(index, 1);
                    })
                    .catch(error => {
                        catchErrors(error, succesMessage);
                    })
            }

            const addObjectifs = (idObjectif, type) => {
                let erreur = false;
                let data = [];
                if (type === 'appreciation') {
                    data = form.objectifAppreciation;
                } else {
                    data = form.objectifExecution;
                }
                data.forEach(function (item, indexe) {
                    if (item.id === idObjectif && erreur === false) {
                        erreur = true;
                    } else {
                    }
                });

                if (!erreur) {
                    let data = objectifs.value;
                    let found = false;
                    let objectif = '';
                    for (let i = 0; i < data.length; ++i) {
                        if (parseInt(idObjectif) === data[i].id && found === false) {
                            objectif = data[i];
                            found = true;
                        }
                    }
                    if (type === 'appreciation') {
                        form.objectifAppreciation.push(objectif);
                    } else {
                        form.objectifExecution.push(objectif);
                    }

                }
            }

           /* const checkRadioButtonAppreciation = (fieldName, idObjectif) => {
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
                form.objectif.type = 'evaluer';
                addObjectifs(idObjectif, 'appreciation')
                axios.post('add/apprciation/objectif/collaborateur/' + props.id, {...form.objectif})
                    .then((response) => {
                    })
                    .catch((error) => {
                        catchErrors(error, succesMessage);
                    })

            }*/

            /*const commentObjectif = (id) => {
                form.objectif.commentaire = $('#commentaire' + id).val();
                form.objectif.type = 'evaluer';
                form.objectif.id = id;
                axios.post('add/commentaire/objectif/collaborateur/' + props.id, {...form.objectif})
                    .then((response) => {
                    })
                    .catch((error) => {
                        catchErrors(error, succesMessage);
                    })
            }*/

            const checkRadioButtonNiveau = (idObjectif, idNiveau) => {
                let data = reactive({
                    idObjectif: idObjectif,
                    idNiveau: idNiveau,
                    type: 'evaluer'
                })
                addObjectifs(idObjectif, 'niveau');
                axios.post('add/niveau/objectif/collaborateur/' + props.id + '/' + evaluation.value.id+'/autoEvaluationSalarieByResp', {...data})
                    .then((response) => {
                        form.pointTotalNiveau = response.data.data.point;
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
                    type: 'evaluer'
                })
                axios.post('add/critere/evaluer/' + props.id + '/' + evaluation.value.id+'/autoEvaluationSalarieByResp', {...data})
                    .then((response) => {
                        let moyenne = parseFloat(response.data.data.pointTotalCatgorieCritere);
                        let valTotal = Math.round(moyenne * 100) / 100;
                        $('#totalCategorie' + codeCategorie).html(valTotal);
                        form.pointTotalEvaluation = Math.round(response.data.data.pointTotalEvaluationCompetence * 100) / 100;
                        form.pointGblobal = parseFloat(form.pointTotalNiveau) + parseFloat(form.pointTotalEvaluation);
                    })
                    .catch((error) => {
                        catchErrors(error, succesMessage);
                    })
                let datas = form.tabCitereEvaluer;
                let erreur = false;

                datas.forEach(function (item, indexe) {
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
                }
            }

            const setPreoccupation = (type) => {
                let data = reactive({
                    avenirProf: form.avenirProf,
                    convenanceMission: form.convenanceMission,
                    difficulteGlobal: form.difficulteGlobal,
                    superCompetence: form.superCompetence,
                    type: type
                })
                axios.post('add/preoccupation/collaborateur/' + props.id + '/' + evaluation.value.id+'/autoEvaluationSalarieByResp', {...data})
                    .then((response) => {
                    })
                    .catch(error => {
                        catchErrors(error, succesMessage);
                    })
            }

            const {validateNameField, Errors} = useFormValidation();

            const {tabError, createObjectif, addActions, removeAction} = useObjectifs();

            const validateInputlibelle = () => {
                validateNameField('libelle', form.libelle)
            }
            const validateInputResultat = () => {
                validateNameField('resultat', form.resultat)
            }
            const validateInputEcheance = () => {
                validateNameField('echeance', form.echeance)
            }
            const validateInputAction = () => {
                validateNameField('action', form.action)
            }

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
                        'type': 'autoEvaluationSalarieByResp',
                        'idEvaluation':evaluation.value.id
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
                                    title: 'error',
                                    text:  response.data.data.message,
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
                                    text:  'Objectif ajouté avec succès dans la base',
                                    icon: 'success',
                                });
                            }
                        })
                        .catch(error => {
                            $('#btn-save').html('Ajouter Nouvelle Objectif')
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
                    axios.post('destroy/objectif/by/libelle/' + props.id + '/' + evaluation.value.id+'/autoEvaluationSalarieByResp', {...data})
                        .then((response) => {
                            form.libelle = objectif.libelle;
                            form.resultat = objectif.resultat;
                            form.echeance = objectif.echeance;
                            form.actions = objectif.actions;
                            form.tabAction = objectif.actions.split(';')
                            tabObjectif.value.splice(index, 1);
                            deleteSend.value = false;
                        })
                        .catch((error) => {
                            catchErrors(error, succesMessage);
                        })
                }

            }

            /*  const storeRessource = async () => {
                  if (tabObjectif.value.length < 5) {
                      alert('Vos objectifs n\'atteignent pas 5');
                      return;
                  }
                  if (tabObjectif.value.length > 5) {
                      alert('Vos objectifs dépasse 5');
                      return;
                  }
                  if (!confirm('Voulez-vous vraiment valider les 05 objectifs pour la nouvelle année?')) {
                      return;
                  }
                  $('#btn-save').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>');
                  let data = reactive({
                      'tabObjectif': tabObjectif.value,
                      'type': 'formAutoEvaluation'
                  })
                  await axios.post('objectifs', {...data})
                      .then((response) => {
                          if (response.data.code === 500) {

                          } else {
                              $('#btn-save').html('Enregistrer');
                              form.validerObjectif = true;
                              form.messages = 'Vos objectifs ont été bien créés dans la base'
                          }
                      }).catch(error => {
                          $('#btn-save').html('Enregistrer')
                          let createCustomerErrors = error.response.data.errors;
                          if (error.response.status === 422) {
                              for (const key in createCustomerErrors) {
                                  tabError.value += error.response.data.errors[key][0] + '| ';
                              }
                          }
                      })
              }*/

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

                    await axios.post('add/besoin/formation/collaborateur/' + props.id + '/' + evaluation.value.id+'/autoEvaluationSalarieByResp', {...data})
                        .then((response) => {
                            if (response.data.code === 500) {

                            } else {
                                form.tabBesoinFormation.push({...data});
                                form.besoinFormation.libelle = '';
                                form.besoinFormation.resultat = '';
                                form.besoinFormation.enonceProbleme = '';
                                $('#btn-save-besoin').html('Ajouter Besoin de formation');
                                form.validerBesoin = false;
                                Swal.fire({
                                    title: 'SUCCES',
                                    text:  'Besoin de formation ajouté avec succès dans la base',
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
                    axios.post('destroy/besoin/formation/by/libelle/' + props.id + '/' + evaluation.value.id+'/autoEvaluationSalarieByResp', {...data})
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
                    bonus: 0,
                    type: type
                })
                await axios.post('add/commentaire/evaluation/collaborateur/' + props.id + '/' + evaluation.value.id+'/autoEvaluationSalarieByResp', {...data})
                    .then((response) => {

                    })
                    .catch((error) => {
                        catchErrors(error, succesMessage);
                    })
            }

            const clotureEvaluation = async (type) => {
                let data = reactive({
                    type: type
                })
                $('#btn-valider').html('<i class="fas fa-circle-notch fa-spin fa-2x"></i>')
                await axios.post('cloture/evaluation/' + props.id + '/' + evaluation.value.id+'/autoEvaluationSalarieByResp', {...data})
                    .then((response) => {
                        if (response.data.data.code===500){
                            Swal.fire({
                                title: 'Erreur serveur',
                                text:  response.data.data.message,
                                icon: 'error',
                            });
                        }else {
                            $('#btn-fermerValidation')[0].click();
                            let name = dasboardName();
                            let succesMessage = 'Vous avez bien terminé l\'auto-évaluation de votre collaborateur. Vous devez passé à l\' entretien pour confirmer.'
                            router.push({name: name, params: {messages: succesMessage}});
                        }

                    })
                    .catch((error) => {
                        catchErrors(error, succesMessage);
                    })
            }

            return {
                load,
                form,
                Errors,
                tabError,
                formations,
                objectifs,
                tabObjectif,
                niveauObjectifs,
                critereEvaluations,
                ponderationCriteres,
                deleteSend,
                deleteBesoinSend,
                code_retour,
                messageErreur,
                scrollMeTo,
                addFaireMarquant,
                removeFaireMarquant,
                showInput,
                getFormationchoose,
                addFormation,
                /*addObjectifs,*/
                displayStep,
                validateInputlibelle,
                validateInputResultat,
                validateInputEcheance,
                validateInputAction,
                addObjectif,
                deleteObjectif,
                /* storeRessource,*/
                addActions,
                removeAction,
               /* checkRadioButtonAppreciation,*/
               /* commentObjectif,*/
                checkRadioButtonNiveau,
                checkRadioButtonPonderationCritere,
                setPreoccupation,
                removeFormationSuivie,
                setBesoinFormation,
                setCommentaireEvaluation,
                clotureEvaluation,
                checkedInitPonderation,
                deleteBesoinFormation
            }
        }
    }
</script>

<style scoped>
    .title {
        text-align: center;
    }
</style>
