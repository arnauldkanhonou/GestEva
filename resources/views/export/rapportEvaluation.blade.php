<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="front/css/adminlte.min.css">

    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 0.8em;
        }

        .hauteur {
            height: 6px;
        }

        .hauteurTr {
            height: 1em;
        }

        .border {
            border: black solid 1px;
        }

        .ulIdentification {
            display: inline-block;
            list-style-type: none;
        }

        .ulFaireMarquant {
            list-style-type: none;
            margin-left: -42px
        }

        h5 {
            color: #0a6779;
        }

        h6 {
            text-decoration: underline;
        }

    </style>
</head>

<body>
<div>
    <h6 align="right"><b style="border: black solid 2px;padding: 10px;">Année: {{$annee}}</b></h6>
    @if($evaluation->clotureResp)
        <h5 align="center">ENTRETIEN ANNUEL D'EVALUATION</h5>
    @else
        <h5 align="center">RAPPORT D'AUTO-EVALUATION</h5>
    @endif

    <h6 align="center">{{ $employe->categorie_professionnelle->categorie->libelle }}</h6>
    <hr>
    <div style="margin-top: 22px">
        <ul class="ulIdentification" style="margin-left: -42px">
            <li><strong>Nom:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
            <li><strong>Prenoms:</strong>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;</li>
            <li><strong>Matricule:</strong></li>
            <li><strong>Service:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
            <li><strong>Ancienneté:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
            <li><strong>Catégorie professionnelle:</strong></li>
            <li><strong>Date entretien:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
        </ul>
        <ul class="ulIdentification float-right">
            <li>&nbsp;&nbsp;&nbsp; <b>{{ $employe->nom }}</b></li>
            <li>&nbsp;&nbsp;&nbsp; <b>{{ $employe->prenoms }}</b></li>
            <li>&nbsp;&nbsp;&nbsp;&nbsp;<b>{{ $employe->matricule }}</b></li>
            <li>&nbsp;&nbsp;&nbsp; <b>{{ $employe->unite->libelle }}</b></li>
            <li>&nbsp;&nbsp; <b>{{ $anciennete }}</b></li>
            <li>&nbsp;&nbsp;&nbsp; <b>{{ $employe->categorie_professionnelle->code }}</b></li>
            <li>&nbsp;&nbsp;&nbsp; <b>{{ $evaluation->dateEntretien }}</b></li>
        </ul>
    </div>
    {{-- <table class="table table-striped">
         <tr>
             <td class="hauteur">Nom & Prénoms du collaborateur:</td>
             <td class="hauteur" align="right" colspan="1"><b>{{$employe->nom}} {{$employe->prenoms}}</b></td>
         </tr>
         <tr>
             <td class="hauteur">Matricule:</td>
             <td class="hauteur" align="right" colspan="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>{{$employe->matricule}}</b></td>
         </tr>
         <tr>
             <td class="hauteur">Service:</td>
             <td class="hauteur" align="right" colspan="1">&nbsp;&nbsp;&nbsp;<b>{{$employe->unite->libelle}}</b></td>
         </tr>
         <tr>
             <td class="hauteur">Ancienneté:</td>
             <td class="hauteur" align="right" colspan="1"><b>{{ $anciennete }}</b></td>
         </tr>
         <tr>
             <td class="hauteur">Catégorie professionnelle:</td>
             <td class="hauteur" align="right" colspan="1"><b>{{ $employe->categorie_professionnelle->code }}</b></td>
         </tr>
         <tr>
             <td class="hauteur">Date de l'entretien:</td>
             <td class="hauteur" align="right" colspan="1"><b>{{ $evaluation->dateEntretien }}</b></td>
         </tr>
     </table>--}}
    <hr>
    <h5 align="center">1- PRINCIPALES MISSIONS</h5>

    @foreach($missions as $mission)
        <ul class="ulFaireMarquant">
            <li>{{$mission->libelle}}</li>
        </ul>
    @endforeach

    <hr>
    <h5 align="center">2- BILAN DE L'ANNEE ECOULE</h5>
    <h6>Accomplissement</h6>
    {{--    <b>Quelles sont les principalrd réussites du collaborateur(missions réussies,objectifs réussis...) au cours de l'année</b>--}}

    @foreach($tabAccomplissement as $accomplissement)
        <ul class="ulFaireMarquant">
            <li>{{$accomplissement}}</li>
        </ul>
    @endforeach

    <hr>

    <h6>Difficultés majeures</h6>
    {{--<b>Quelles sont les principalrd réussites du collaborateur(missions réussies,objectifs réussis...) au cours de
        l'année</b>--}}

    @foreach($tabDifficulte as $difficulte)
        <ul class="ulFaireMarquant">
            <li>{{$difficulte}}</li>
        </ul>
    @endforeach

    <hr>
    <h6>Progrès réalisés</h6>
    {{--<table class="table table-striped table-bordered">--}}
    @foreach($tabProgres as $progres)
        <ul class="ulFaireMarquant">
            <li>{{$progres}}</li>
        </ul>
    @endforeach
    {{--</table>--}}
    <hr>
    <h6>Formations suivies l'année dernière</h6>
    <table class="table table-striped ">
        <tr style="line-height: 4px;border: black 1px solid;background-color:  #0a6779;color: white">
            <td style="border: black 1px solid" class="hauteur"><b>Intitulé</b></td>
            <td style="border: black 1px solid" class="hauteur"><b>Date</b></td>
            <td style="border: black 1px solid" class="hauteur"><b>Objectifs</b></td>
            <td style="border: black 1px solid" class="hauteur"><b>Bilan</b></td>
        </tr>
        @foreach($formationSuivie as $formation)
            <tr style="border: black 1px solid">
                <td style="border: black 1px solid" class="hauteur">{{$formation['libelle']}}</td>
                <td style="border: black 1px solid" class="hauteur">{{$formation['date']}}</td>
                <td style="border: black 1px solid" class="hauteur">{{$formation['objectif']}}</td>
                <td style="border: black 1px solid" class="hauteur">{{$formation['bilan']}}</td>
            </tr>
        @endforeach
    </table>
    <hr>
    <h5 align="center">3- EVALUATION DES PERFORMANCES</h5>
    {{--<table class="table table-striped">
        <tr>
            <td class="hauteur"><b>Objectifs</b></td>
            <td class="hauteur"><b>Appréciation</b></td>
        </tr>
        @foreach($objectifs as $objectif)
            <tr>
                <td class="hauteur">{{$objectif->libelle}}</td>
            </tr>
        @endforeach
    </table>
    <hr>--}}
    <table class="table table-striped">
        <tr style="line-height: 4px;border: black 1px solid;background-color:  #0a6779;color: white">
            <td style="border: black 1px solid" class="hauteur"><b>Objectifs</b></td>
            <td style="border: black 1px solid" class="hauteur"><b>Réalisation</b></td>
            <td style="border: black 1px solid" class="hauteur"><b>Appréciation</b></td>
            <td style="border: black 1px solid" class="hauteur"><b>Point</b></td>
        </tr>
        @php
            $i = 0;
        @endphp
        @foreach($objectifs as $objectif)
            @php
                $i += $objectif->noteObtenue;
            @endphp
            <tr style="border: black 1px solid;">
                <td style="border: black 1px solid" class="hauteur">{{$objectif->libelle}}</td>
                <td style="border: black 1px solid" class="hauteur"><b>{{$objectif->niveauExecFinal}}</b></td>
                <td style="border: black 1px solid" class="hauteur"><b>{{$objectif->appreFinal}}</b></td>
                <td style="border: black 1px solid" class="hauteur" align="right"><b>{{$objectif->noteObtenue}}</b></td>
            </tr>
        @endforeach
        <tr class="hauteurTr" style="border: black 1px solid">
            <td style="border: black 1px solid" class="hauteur" align="right" colspan="3"><b>Total</b></td>
            <td style="border: black 1px solid" class="hauteur" align="right"><b>{{$i}}</b></td>
        </tr>
    </table>
    <hr>
    <br>
    <h5 align="center">4- EVALUATION DES COMPETENCES</h5>
    <table class="table table-striped">
        <tr style="border: black 1px solid;line-height: 4px;">
            <td style="border: black 1px solid" class="hauteur"><b>Critère</b></td>
            <td style="border: black 1px solid" class="hauteur"><b>Point</b></td>
        </tr>
        @php
            $totalComptetence = 0;
        @endphp
        @foreach($criteres as $key => $critere)
            @php
                $p = 0;
            @endphp
            <tr style="line-height: 4px;border: black 1px solid;background-color:  #0a6779;color: white">
                <td style="border: black 1px solid" class="border hauteur" colspan="2"><b>{{$key}}</b></td>
            </tr>
            @php
                $nbrCritereByCatg = count($critere);
            @endphp
            @foreach($critere as $value)
                @php
                    $p += $value->pointObtenu;
                @endphp
                <tr style="line-height: 4px;">
                    <td style="border: black 1px solid" class="hauteur">{{$value->libelle}}</td>
                    <td style="border: black 1px solid" class="hauteur" align="right"><b>{{$value->pointObtenu}}</b>
                    </td>
                </tr>
            @endforeach
            @php
                $p = round($p/$nbrCritereByCatg,2);
                $totalComptetence +=$p;
            @endphp
            <tr style="line-height: 4px;">
                <td style="border: black 1px solid" class="hauteur" align="right"><b>Moyenne</b></td>
                <td style="border: black 1px solid" class="hauteur" align="right"><b>{{$p}}</b></td>
            </tr>
        @endforeach
        <tr style="line-height: 4px;">
            <td style="border: black 1px solid" class="hauteur" align="right"><b>Total critères de compétence</b></td>
            <td style="border: black 1px solid" class="hauteur" align="right"><b>{{ $totalComptetence }}</b></td>
        </tr>
        <tr style="line-height: 4px;border: black 1px solid">
            @php
                $som = $totalComptetence + $i;
            @endphp
            <td style="border: black 1px solid" class="hauteur" align="right"><b>Total(évaluation de performances +
                    evaluation de compétences)</b></td>
            <td style="border: black 1px solid" class="hauteur" align="right"><b>{{$som}}</b></td>
        </tr>
    </table>
    <hr>
    <h5 align="center">5- PREOCUPATIONS ET SOUHAITS</h5>
    <br>
    <h6>Comment envisagez-vous votre avenir professionel?</h6>
    <ul class="ulFaireMarquant">
        <li>{{$evaluation->AvenirProfs}}</li>
    </ul>
    <h6>Quelles sont les missions qui vous conviennent le mieux?</h6>

    <ul class="ulFaireMarquant">
        <li>{{$evaluation->convenanceMission}}</li>
    </ul>

    <h6>Quelles sont les difficultés que vous rencontrez?</h6>

    <ul class="ulFaireMarquant">
        <li>{{$evaluation->difficulteGblobal}}</li>
    </ul>

    <h6>Avez vous des compétences ou des qualités non utilisées?</h6>
    <ul class="ulFaireMarquant">
        <li>{{$evaluation->superCompetence}}</li>
    </ul>
    <hr>
    <h5 align="center">6- OBJECTIFS POUR LA NOUVELLE ANNEE</h5>
    <br>
    <table class="table table-striped">
        <tr style="border: black 1px solid;background-color:  #0a6779;color: white">
            <td style="border: black 1px solid" class="hauteur"><b>Objectifs</b></td>
            <td style="border: black 1px solid" class="hauteur"><b>Actions clées</b></td>
            <td style="border: black 1px solid" class="hauteur"><b>Résultats attendus</b></td>
            <td style="border: black 1px solid" class="hauteur"><b>Echéances</b></td>
        </tr>
        @foreach($nouvelleObjectifs as $objectif)
            <tr style="border: black 1px solid">
                <td style="border: black 1px solid" class="hauteur">{{$objectif->libelle}}</td>
                <td style="border: black 1px solid" class="hauteur">{{$objectif->actionCle}}</td>
                <td style="border: black 1px solid" class="hauteur">{{$objectif->resultatAttendu}}</td>
                <td style="border: black 1px solid" class="hauteur">{{$objectif->echeance}}</td>
            </tr>
        @endforeach
    </table>
    <hr>
    <h5 align="center">7- DEVELOPPEMENT DE COMPETENCES</h5>
    <br>
    <table class="table table-striped">
        <tr style="border: black 1px solid;background-color:  #0a6779;color: white">
            <td style="border: black 1px solid" class="hauteur"><b>Besoins de formation</b></td>
            <td style="border: black 1px solid" class="hauteur"><b>Problème à résoudre</b></td>
            <td style="border: black 1px solid" class="hauteur"><b>Résultats attendus</b></td>
        </tr>
        @foreach($besoinFormations as $besoin)
            <tr style="border: black 1px solid">
                <td style="border: black 1px solid" class="hauteur">{{$besoin->libelle}}</td>
                <td style="border: black 1px solid" class="hauteur">{{$besoin->problemeEnonce}}</td>
                <td style="border: black 1px solid" class="hauteur">{{$besoin->resultatAttendu}}</td>
            </tr>
        @endforeach
    </table>
    <hr>
    <br>
    <h5 align="center">8- COMMENTAIRES</h5>

    <div style="border: black solid 1px;margin-top: 20px; border-radius: 40px;padding: 8px">
        <p align="center"><b>commentaire de l'évalué</b></p>
        <p style="margin-right: 15px;">
            {{$evaluation->commentaireEvaluer}}
            <br>
        </p>
        <table>
            <tr style="margin-right: 15px;">
                <td class="hauteur"><b>Date et signature:</b></td>
                <td class="hauteur"></td>
            </tr>
        </table>

    </div>

    <div style="border: black solid 1px;border-radius: 40px;padding: 8px">
        <p align="center"><b>commentaire de l'évaluateur</b></p>
        <p style="margin-right: 15px;">
            {{$evaluation->commentaireResp}}
            <br>
        </p>
        <table>
            <tr style="margin-right: 15px;">
                <td class="hauteur"><b>Nom et Prénoms de l'évaluateur:</b></td>
                <td class="hauteur">{{$evaluation->evaluateur}}</td>
            </tr>
            <tr style="margin-right: 15px;">
                <td class="hauteur"><b>Date et signature:</b></td>
                <td class="hauteur"></td>
            </tr>
        </table>
    </div>

    @foreach($commentaires as $commentaire)
        <div style="border: black solid 1px;margin-top: 20px; border-radius: 40px;padding: 8px">
            <p align="center"><b>commentaire N+( <b style="color: red">{{$commentaire->profile}}</b> )</b></p>
            <p style="margin-right: 15px;">
                {{$commentaire->libelle}}
                <br>
            </p>
            <table>
                <tr style="margin-right: 15px;">
                    <td class="hauteur"><b>Bonus:</b></td>
                    <td class="hauteur">{{$commentaire->bonus}}</td>
                </tr>
            </table>
            <table>
                <tr style="margin-right: 15px;">
                    <td class="hauteur"><b>Date et signature:</b></td>
                    <td class="hauteur"></td>
                </tr>
            </table>
        </div>
    @endforeach
    <br>
    <hr>
    <table style="height: 0,4em">
        <tr style="margin-right: 15px;">
            <td class="hauteur"><b>SOMME DES BONUS:</b></td>
            <td class="hauteur">{{$sommeBonus}}</td>
        </tr>

        <tr style="margin-right: 15px;">
            <td class="hauteur"><b>PERFORMANCE REALISEE: (Total performance+ Total compétence + Bonus)</b></td>
            <td class="hauteur">{{$evaluation->performanceRealiser + abs($sommeBonus)}}</td>
        </tr>

        <tr style="margin-right: 15px;">
            <td class="hauteur"><b>PERFORMANCE REELLE (Performance Réalisée):&nbsp;&nbsp;</b></td>
            <td class="hauteur">&nbsp;&nbsp;&nbsp;{{$evaluation->performanceRealiser}}</td>
        </tr>

        <tr style="margin-right: 15px;">
            <td class="hauteur"><b>NOTE PONDEREE:</b></td>
            <td class="hauteur">{{$evaluation->notePondere}}</td>
        </tr>

        <tr style="margin-right: 15px;">
            <td class="hauteur"><b>PERFORMANCE FINALE (Performance réelle + Note Pondérée):</b></td>
            <td class="hauteur">&nbsp;&nbsp;&nbsp;{{$evaluation->performanceFinal}}</td>
        </tr>

    </table>
    <br>
    <hr>
    <table class="table table-striped">
        <tr style="border: black 1px solid;background-color:  #0a6779;color: white">
            <td style="border: black 1px solid" class="hauteur"><b>Niveau de performance</b></td>
            <td style="border: black 1px solid" class="hauteur"><b>Echelle de point</b></td>
            <td style="border: black 1px solid" class="hauteur"><b>Appréciation</b></td>
            <td style="border: black 1px solid" class="hauteur"><b>Valider au CODI</b></td>
        </tr>
        <tbody>
        @if($evaluation->performanceFinal !=0 and $evaluation->performanceFinal !=.0)
            @foreach($performances as $performance)

                @if($performance->borneInf<=$evaluation->performanceFinal && $evaluation->performanceFinal<=$performance->borneSup)
                    <tr>
                        <td>{{$performance->libelle}}</td>
                        <td> de {{$performance->borneInf}} à {{$performance->borneSup}}</td>
                        <td>{{$performance->appreciation}}</td>
                        <td>@if($performance->clotureCodi) OUI @else NON @endif</td>
                    </tr>
                @endif
            @endforeach
        @else
            <tr>
                <td style="color: darkred" colspan="3">Votre évaluation est encore à la dernière étape de validation</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>

</body>
</html>
