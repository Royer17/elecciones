@extends('store.layouts.index')
@section('content')

    <!-- Breadcrumb Section Begin -->
    {{-- <section class="breadcrumb-section set-bg" data-setbg="/store/img/breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>{{ $product['nombre'] }}</h2>
                        <div class="breadcrumb__option">
                            <a href="/">Inicio</a>
                            <a href="/productos?categoria={{ $product['category']['slug'] }}">{{ $product['category']['nombre'] }}</a>
                            <span>{{ $product['nombre'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <!-- Breadcrumb Section End -->

    <!-- Product Details Section Begin -->
    <section class="product-details spad header-shadow">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="product__details__pic">
                        <div class="product__details__pic__item">
                            <img class="product__details__pic__item--large"
                                src="{{ $product['imagen'] }}" alt="">
                        </div>
                        <div class="product__details__pic__slider owl-carousel">
                            <img data-imgbigurl="{{ $product['imagen'] }}"
                                src="{{ $product['imagen'] }}" alt="">
                            {{--
                            <img data-imgbigurl="/store/img/product/details/product-details-3.jpg"
                                src="/store/img/product/details/thumb-2.jpg" alt="">
                            <img data-imgbigurl="/store/img/product/details/product-details-5.jpg"
                                src="/store/img/product/details/thumb-3.jpg" alt="">
                            <img data-imgbigurl="/store/img/product/details/product-details-4.jpg"
                                src="/store/img/product/details/thumb-4.jpg" alt="">
                            --}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="product__details__text">
                        <h3>{{ $product['nombre'] }}</h3>
                        {{--
                        <div class="product__details__rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star-half-o"></i>
                            <span>(18 reviews)</span>
                        </div>
                        --}}
                        <div class="product__details__price">S/{{ number_format($product['price'], 2, '.', '') }}</div>

                        <p>{{ $product['descripcion'] }}</p>
                        <div class="product__details__quantity">
                            <div class="quantity">
                                <div class="pro-qty">
                                    <input type="text" value="1" id="product_quantity">
                                </div>
                            </div>
                        </div>
                        <a href="#" class="primary-btn" data-index="{{ $product['idarticulo'] }}" id="add_to_cart">Agregar al carrito</a>
                        <ul>
                            <li><b>Disponibilidad</b> <span>{{ $product['stock'] }} en Stock</span></li>
                            {{--
                            <li><b>Shipping</b> <span>01 day shipping. <samp>Free pickup today</samp></span></li>
                            <li><b>Weight</b> <span>0.5 kg</span></li>
                            <li><b>Share on</b>
                                <div class="share">
                                    <a href="#"><i class="fa fa-facebook"></i></a>
                                    <a href="#"><i class="fa fa-twitter"></i></a>
                                    <a href="#"><i class="fa fa-instagram"></i></a>
                                    <a href="#"><i class="fa fa-pinterest"></i></a>
                                </div>
                            </li>
                            --}}
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- Product Details Section End -->

    <!-- Related Product Section Begin -->
    {{-- <section class="related-product">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title related__product__title">
                        <h2>Productos relacionados</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($products_related as $product)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="{{ $product['imagen'] }}">
                        </div>
                        <div class="product__item__text">
                            <h6><a href="#">{{ $product['nombre'] }}</a></h6>
                            <h5>S/{{ number_format($product['price'], 2, '.', '') }}</h5>
                            <a href="#" data-index="{{ $product['idarticulo'] }}" class="add_to_shopping_cart btn-cart"><i class="fa fa-shopping-cart"></i> Comprar</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section> --}}
    <!-- Related Product Section End -->

@stop
@section('plugins-js')

    <script type="text/javascript">
        $(document).on('keyup', '#product_quantity', function(){
            myEfficientFnQuantity($(this));
        });

        var myEfficientFnQuantity = debounce(function(_inputNumber) {
            let _input = _inputNumber, _value;

            _value = _input.val().replace(/[^0-9]/g, '');
            _value = parseInt(_value);

            _value = !_value ? 1 : _value;
            _input.val(_value);


        }, 1000);

        document.querySelector(`#add_to_cart`)
            .addEventListener('click', (e) => {
                e.preventDefault();
                let _id = document.querySelector(`#add_to_cart`).getAttribute('data-index'), _value = document.querySelector(`#product_quantity`).value;

                if (_value == 0) return;
                addingProduct(_id, _value);
                update_total([$(`.cart_total`)]);
                updateCartQuantity();
                successAddedCart();
            });
    </script>

@stop