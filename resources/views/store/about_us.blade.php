@extends('store.layouts.index')
@section('content')

{{-- <section class="blog-details-hero set-bg" data-setbg="/store/img/blog/details/details-hero.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="blog__details__hero__text">
                    <h2>The Moment You Need To Remove Garlic From The Menu</h2>
                    <ul>
                        <li>By Michael Scofield</li>
                        <li>January 14, 2019</li>
                        <li>8 Comments</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section> --}}


<!-- Blog Details Section Begin -->
<section class="blog-details spad header-shadow">
    <div class="container">
        <div class="checkout__form">
            <h4>Nosotros</h4>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-5 order-md-1 order-2">
            	<img src="{{ $company->image }}" alt="">

            </div>
            <div class="col-lg-8 col-md-7 order-md-1 order-1">
                <div class="blog__details__text">
                    <p>{{ $company->description }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

@stop

@section('plugins-js')
@stop
