{{--<div class="container">
    <div id="navbar" class="collapse navbar-collapse">
        <ul class="navbar-brand">
            <li id="nav_administration"><img height="52px" src="{{ asset('storage/images/imageSCB1.jpg') }}" alt=""></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li>
                <a href="#"> <span class="glyphicon glyphicon-home"> </span> <b style="color: white;font-size: 18px"> Tableau de bord</b></a>
            </li>
            <li>
                <a href="#"><span class="glyphicon glyphicon-user"></span> <b style="color: white;font-size: 18px"> Profil</b></a>
            </li>
            <li>
                <a href="#"> <span class="glyphicon glyphicon-log-out " aria-hidden="true"></span> <b style="color: white;font-size: 18px"> Déconnexion</b></a>
            </li>
        </ul>
    </div>
</div>--}}


<div class="container-fluid">
    <span  class="navbar-nav d-inline-block">
        <img src="{{ asset('storage/images/imageSCB1.jpg') }}" alt="" class="h-12">
    </span> &nbsp;
    <b style="color: white" class="navbar-brand"> SCB-Lafarge</b>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
       <b style="color: white"> <span class="fa fa-navicon"></span></b>
    </button>
    <div id="navbarSupportedContent" class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <router-link :to="{name:'customer.index'}" class="nav-link active"><b style="color: white;font-size: 17px"> <i class="fa fa-home"></i> Tableau de bord</b></router-link>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="#"> <b style="color: white;font-size: 17px"><i class="fa fa-user"></i> Profil</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="#"> <b style="color: white;font-size: 17px"><i class="fa fa-sign-out-alt"></i> Déconnexion</b></a>
            </li>

        </ul>
    </div>
</div>
