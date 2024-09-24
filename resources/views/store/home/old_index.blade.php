@extends('store.layouts.index')
@section('content')

    @foreach($categories as $key => $category)
        @if(count($category['products']))
            <!-- Categories Section Begin -->
            <section class="categories">
                <div class="container">
                    <div class="row">
                    <div class="section-title" style="margin-bottom: 25px !important; ">
                        <h2 style="font-weight: 600 !important;">{{ $category['name'] }}</h2>
                    </div>
                        <div class="categories__slider owl-carousel">
                            @foreach($category['products'] as $product)
                            <div class="featured__item col-lg-3">
                                <div class="featured__item__pic set-bg" data-setbg="{{ $product['imagen'] ? $product['image'] : $image_not_available }}">
                                    {{--
                                    <ul class="featured__item__pic__hover">
                                        <li><a href="#" data-index="{{ $product['idarticulo'] }}" class="add_to_shopping_cart"><i class="fa fa-shopping-cart"></i></a></li>
                                    </ul>
                                    --}}
                                </div>
                                <div class="featured__item__text">
                                    <h6><a href="/producto/{{ $product['slug'] }}">{{ $product['nombre'] }}</a></h6>
                                    <h5>S/ {{ $product['price'] }}</h5>
                                    <a href="#" data-index="{{ $product['idarticulo'] }}" class="add_to_shopping_cart btn-cart"><i class="fa fa-shopping-cart"></i> Comprar</a>
                                </div>
                            </div>


                            @endforeach

                           
                        </div>
                    </div>
                </div>
            </section>
        @endif
            <!-- Categories Section End -->
    @endforeach



    <!-- Featured Section Begin -->
    <section class="featured spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Productos destacados</h2>
                    </div>
                    <div class="featured__controls">
                        <ul>
                            <li class="active" data-filter="*">Todos</li>
                            @foreach($categories as $category)
                                @if(count($category['products']))
                                    <li data-filter=".{{ $category['slug'] }}">{{ $category['name'] }}</li>
                                @endif
                            @endforeach
                          
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row featured__filter">
                @foreach($categories as $category)
                    @if(count($category['products']))
                        @foreach($category['products'] as $product)

                            <div class="col-lg-3 col-md-4 col-sm-6 mix {{ $category['slug'] }}">
                                <div class="featured__item">
                                    <div class="featured__item__pic set-bg" data-setbg="{{ $product['imagen'] ? $product['imagen'] : $image_not_available }}">
                                        <ul class="featured__item__pic__hover">
                                            <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                            <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                            <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                                        </ul>
                                    </div>
                                    <div class="featured__item__text">
                                        <h6><a href="/producto/{{ $product['slug'] }}">{{ $product['nombre'] }}</a></h6>
                                        <h5>S/ {{ $product['price'] }}</h5>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                @endforeach


            </div>
        </div>
    </section>
    <!-- Featured Section End -->


@stop

@section('plugins-js')

    <script type="text/javascript">

        update_sticky_cart();

        function update_sticky_cart(){
            axios.get(`/cart-detail?ids=${localStorage.getItem("cart")}`)
                .then((response) => {
                    draw_detail(response.data);
                });
        }

        function draw_detail(products){
            document.querySelector(`#detail_tbody`).innerHTML = ``;
            let _content = "";
            products.forEach((value) => {
                _content += `
                                <tr>
                                    <td class="shoping__cart__item pl-5" style="padding-bottom: 10px;padding-top: 10px">
                                        <h5>${value.name}</h5>
                                    </td>
                                    <td class="shoping__cart__price" style="padding-bottom: 10px;padding-top: 10px">
                                        S/${value.price}
                                    </td>
                                    <td class="shoping__cart__quantity" style="padding-bottom: 10px;padding-top: 10px">
                                        ${value.quantity}
                                    </td>
                                    <td class="shoping__cart__total" style="padding-bottom: 10px;padding-top: 10px">
                                        S/${(value.price*value.quantity).toFixed(2)}
                                    </td>
                                    <td class="shoping__cart__item__close" style="padding-bottom: 10px;padding-top: 10px">
                                        <span class="icon_close product__remove" data-index="${value.id}"></span>
                                    </td>
                                </tr>
                                `;

            });

            _content += `
                        <tr>
                            <td class="shoping__cart__item" style="padding-bottom: 10px;padding-top: 10px">

                            </td>
                            <td class="shoping__cart__price" style="padding-bottom: 10px;padding-top: 10px">

                            </td>
                            <td class="shoping__cart__quantity" style="padding-bottom: 10px;padding-top: 10px">

                            </td>
                            <td class="shoping__cart__total" style="padding-bottom: 10px;padding-top: 1px">
                                <a href="/orden" class="btn-cart" style="font-weight:300;font-size:15px;">Comprar</a>
                            </td>
                            <td class="shoping__cart__item__close" style="padding-bottom: 10px;padding-top: 10px">
                            </td>
                        </tr>`;

            document.querySelector(`#detail_tbody`).innerHTML = _content;
        }

        $(document).on('click', '.product__remove', function(){
            let _id = $(this)[0].dataset.index;
            if (localStorage.getItem(`cart`) != null) {
                let _cartArray = JSON.parse(localStorage.getItem("cart"));
                delete _cartArray[_id];
                localStorage.setItem(`cart`, JSON.stringify(_cartArray));
            }

            location.reload();
        });


        var show = false;

        document.querySelector(`#show_cart`)
            .addEventListener('click', function(e){
                e.preventDefault();
                if (!show) {
                    $(`#table_cart`).fadeIn(200);
                    $(`#btn_orden`).fadeIn(200);
                    show = true;
                    return;
                }

                if (show) {
                    $(`#table_cart`).fadeOut(200);
                    $(`#btn_orden`).fadeOut(200);
                    show = false;
                    return;
                }

            });


    </script>
@stop
