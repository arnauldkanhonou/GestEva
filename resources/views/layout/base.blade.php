<!DOCTYPE html>
<html>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') </title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{--    <link rel="stylesheet" href="{{asset('front/css/adminlte.min.css')}}">--}}
    <link rel="stylesheet" href="{{asset('front/css/bootstrap.min.css') }}"/>

    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <link rel="stylesheet" href="{{asset('front/css/fontawesome-free/css/all.min.css')}}">

    {{--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">--}}
    <link rel="stylesheet" href="{{asset('front/css/style.css') }}"/>
    <link rel="stylesheet" href="{{ asset('front/js/plugins/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/js/plugins/chosen/css/bootstrap-chosen.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('front/css/dataTables.bootstrap.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('front/css/select2-bootstrap4.min.css') }}"/>
    <link rel="icon" type="image/x-icon" href="{{ asset('imageSCB1.jpg') }}"/>
    <link rel="stylesheet" href="{{asset('css/custom/app.css')}}"/>

</head>
<body>

<div id="app">
    <app/>
</div>
{{--<nav class="fixed-top navbar navbar-expand-sm navbar-default" style="height: 55px">
    @include('blocks.nav')
</nav>

<div id="main" class="mt-8 card-body">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-2 pb-64" style="background-color: #dbdde3;margin-left: -3px">
                <br>
                @include('blocks.menu')
            </div>
            <div class="col-md-10 mt-4">
                --}}{{--<div id="header">
                    @yield('titlepage')
                </div>--}}{{--
                <div class="col-md-12 card">
                    <div id="ancre" class="p-2" style="padding-top: 15px">
                        @yield('content')
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>--}}

<script src="{{ asset('front/js/jquery.js') }}"></script>
{{--<script src="{{ asset('front/js/main.js') }}"></script>
<script src="{{ asset('front/js/jquery.steps.js') }}"></script>--}}
<script src="{{ asset('front/js/popper.min.js') }}"></script>
<script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{asset('js/app.js?version=1.39')}}"></script>
<script type="text/javascript" src="{{asset('css/custom/app.js')}}"></script>
<script src="{{asset('front/js/Chart.js')}}"></script>
{{--FlatPickr--}}
<script type="text/javascript" src="{{ asset('front/js/plugins/flatpickr/flatpickr.min.js') }}"></script>

{{--Plugin pour le separateur de millier--}}
<script type="text/javascript" src="{{ asset('front/js/plugins/Number-Input-Formatting-Mask-Number/jquery.masknumber.js') }}"></script>

{{--Plugin pour chosen select bootstrap--}}
<script type="text/javascript" src="{{ asset('front/js/plugins/chosen/js/chosen.jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('front/js/plugins/chosen/js/init.js') }}"></script>
<script type="text/javascript" src="{{ asset('front/js/select2.full.min.js') }}"></script>
<script>
    $('.dateFlatPickr').flatpickr({
        defaultDate: "today",
        maxDate: "today",
        dateFormat: "d-m-Y"
    });
    $('.select2')

    $(function (){
        $('#myTab a').on('click', function (e) {
            e.preventDefault()
            $(this).tab('show')
        })

        $('#myTab a[href="#cadres"]').tab('show') // Select tab by name
    })
</script>
</body>
</html>
<script>

</script>
