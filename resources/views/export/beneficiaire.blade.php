<!DOCTYPE html>
<html>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.5/css/buttons.dataTables.css">

</head>
<body>

<div class="container">
    <table id="example" style="border: white 2px solid; font-size: 13px;"
           class="display nowrap">
        <thead style="background-color: #057e72;color: white" class="uppercase">
        <tr>
            <td class="text-uppercase">Nom & Prénoms</td>
            <td class="text-uppercase">Poste</td>
            <td class="text-uppercase">Categorie</td>
            <td class="text-uppercase">Note Pondé.</td>
            <td class="text-uppercase">Perf.Apr.Pondé.</td>
            <td class="text-uppercase">SMC</td>
            <td class="text-uppercase">Montant taux appliqué</td>
            <td class="text-uppercase">Montant brut bonus</td>
        </tr>
        </thead>
        <tbody>
        <tr style="border: black 1px solid">
            <td> </td>
            <td> </td>
            <td> </td>
            <td> </td>
            <td> </td>
            <td> </td>
            <td align="right"><b style="font-size: 17px;" class="text-red-600">Total Bonus</b></td>
            <td align="right"><b style="font-size: 17px;background-color: green;padding: 3px" class="text-black">{{$dataCagnotte['sommeBonus'] }} FCFA</b></td>
        </tr>
        <tr style="border: black 1px solid">
            <td> </td>
            <td> </td>
            <td> </td>
            <td> </td>
            <td> </td>
            <td> </td>
            <td align="right"><b style="font-size: 17px;" class="text-red-600">Prime Exceptionnelle</b></td>
            <td align="right"><b style="font-size: 17px;background-color: green;padding: 3px" class="text-black">{{$dataCagnotte['sommePrime'] }} FCFA</b>
            </td>
        </tr>
        <tr style="border: black 1px solid">
            <td> </td>
            <td> </td>
            <td> </td>
            <td> </td>
            <td> </td>
            <td> </td>
            <td align="right"><b style="font-size: 17px;" class="text-red-600">Budget</b></td>
            <td align="right"><b style="font-size: 17px;background-color: green;padding: 3px" class="text-black">{{$dataCagnotte['budget'] }} FCFA</b></td>
        </tr>
        <tr style="border: black 1px solid">
            <td> </td>
            <td> </td>
            <td> </td>
            <td> </td>
            <td> </td>
            <td> </td>
            <td align="right"><b style="font-size: 17px;" class="text-red-600">Ecart</b></td>
            <td align="right"><b style="font-size: 17px;background-color: green;padding: 3px" class="text-black">{{$dataCagnotte['budget'] - $dataCagnotte['sommeBonus'] - $dataCagnotte['sommePrime']  }} FCFA</b></td>
        </tr>
        @foreach($beneficiaires as $index=>$val)
            <tr style="border: black 1px solid" @if(!$val['isBonus']) class="alert alert-warning" @endif>
                <td><b>{{$val['salarie']}}</b></td>
                <td><b>{{$val['poste']}}</b></td>
                <td><b>{{$val['categorie']}}</b></td>
                <td><b>{{$val['performanceFinal']}}</b></td>
                <td><b>{{$val['niveauPerformanceApresPonderation']}}</b></td>
                <td align="right"><b>{{ $val['smc']}}</b></td>
                @if($val['isBonus'])
                    <td align="right">
                        <b>{{ $val['tauxAppliqueSmc']}}</b>
                    </td>
                @else
                <td align="right">
                    <b>-</b>
                </td>
                @endif
                @if($val['isBonus'])
                <td align="right">
                    <b>{{ $val['montantBonus']}}</b>
                </td>
                @else
                <td align="right">
                    <b>{{$val['montantPrime']}}</b>
                </td>
                @endif
            </tr>
        @endforeach

        </tbody>
    </table>
</div>


<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/2.3.5/js/dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.5/js/dataTables.buttons.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.5/js/buttons.dataTables.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.5/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.5/js/buttons.print.min.js"></script>

<script>
    $(function () {
        new DataTable('#example', {
            layout: {
                topStart: {
                    buttons: ['excel', 'pdf', 'print']
                }
            }
        });
    })
</script>
</body>
</html>
