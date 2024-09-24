
    <header style="height: auto ">
        <div class="container">
        <div class="row align-items-center" id="barra_detalle">
            <nav class="navbar navbar-expand-lg col navbar-light bg-light">

               <form class="form-inline">
                    <button
                            class="navbar-toggler btn btn-outline-success my-2 my-sm-0"
                            type="button"
                            data-toggle="collapse"
                            data-target="#navbarNavDropdown"
                            aria-controls="navbarNavDropdown"
                            aria-expanded="false"
                            aria-label="Toggle navigation"
                        >
                    <!-- <span class="navbar-toggler-icon"></span> -->
                    <i class="fa fa-bars fa-lg" style="color: #fff;"></i>
                    </button>
                    @if($search_button)
                    <div class="collapse navbar-collapse  rounded float-right" id="navbarNavDropdown">

                      <div class="logo_head py-md-0 py-1">
                        <!-- <img src="{{ $company->logo }}"> -->
                        <img src="/img/logo.png" alt="">
                        <p><b>MUNICIPALIDAD<br>DISTRITAL<br>DE PACHÍA</b></p>
                      </div>

                        <ul class="navbar-nav py-md-0 py-1">
                            <li class="nav-item active">
                                <a class="btn btn-warning font-bold" href="/busqueda-documento">Consultar</a>
                            </li>
                        </ul>
                   </div>
                   @endif
               </form>
            </nav>

        </div>


        </div>
    </header>
    <!-- Header Section Begin -->
 <!--

    <header class="header">
        {{-- <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__left">
                            <ul>
                                <li><i class="fa fa-mobile"></i> {{ $company->whatsapp }}</li>
                                <li>{{ $company->name }} </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__right">
                            <div class="header__top__right__social">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-linkedin"></i></a>
                                <a href="#"><i class="fa fa-pinterest-p"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="container">
            <div class="row">
                <div class="col-lg-3">

                </div>
                <div class="col-lg-6">
                    <nav class="header__menu">
                        <ul>
                            <li class="active"><a href="/">Inicio</a></li>
                            <li><a href="/busqueda-documento">Consultar Trámite</a></li>


                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <div class="header__cart">


                    </div>
                </div>
            </div>
            <div class="humberger__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </header>
    Header Section End -->
