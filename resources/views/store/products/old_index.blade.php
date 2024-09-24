@extends('store.layouts.index')
@section('content')

    <!-- Breadcrumb Section Begin -->
    {{-- <section class="breadcrumb-section set-bg" data-setbg="/store/img/breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Comercial Zoyla 1 </h2>
                        <div class="breadcrumb__option">
                            <a href="./index.html"></a>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <!-- Breadcrumb Section End -->

    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-5">
                    <div class="sidebar">
                        <div class="sidebar__item">
                            <h4>Categorías</h4>
                            <ul>
                                <li><a class="category__change" data-slug="" href="#">Todos</a></li>

                                @foreach($categories as $category)
                                <li><a class="category__change" data-slug="{{ $category['slug'] }}" href="#">{{ $category['name'] }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        {{--
                        <div class="sidebar__item">
                            <h4>Precio</h4>
                            <div class="price-range-wrap">
                                <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"
                                    data-min="10" data-max="540">
                                    <div class="ui-slider-range ui-corner-all ui-widget-header"></div>
                                    <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                                    <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                                </div>
                                <div class="range-slider">
                                    <div class="price-input">
                                        <input type="text" id="minamount">
                                        <input type="text" id="maxamount">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sidebar__item sidebar__item__color--option">
                            <h4>Colors</h4>
                            <div class="sidebar__item__color sidebar__item__color--white">
                                <label for="white">
                                    White
                                    <input type="radio" id="white">
                                </label>
                            </div>
                            <div class="sidebar__item__color sidebar__item__color--gray">
                                <label for="gray">
                                    Gray
                                    <input type="radio" id="gray">
                                </label>
                            </div>
                            <div class="sidebar__item__color sidebar__item__color--red">
                                <label for="red">
                                    Red
                                    <input type="radio" id="red">
                                </label>
                            </div>
                            <div class="sidebar__item__color sidebar__item__color--black">
                                <label for="black">
                                    Black
                                    <input type="radio" id="black">
                                </label>
                            </div>
                            <div class="sidebar__item__color sidebar__item__color--blue">
                                <label for="blue">
                                    Blue
                                    <input type="radio" id="blue">
                                </label>
                            </div>
                            <div class="sidebar__item__color sidebar__item__color--green">
                                <label for="green">
                                    Green
                                    <input type="radio" id="green">
                                </label>
                            </div>
                        </div>
                        <div class="sidebar__item">
                            <h4>Popular Size</h4>
                            <div class="sidebar__item__size">
                                <label for="large">
                                    Large
                                    <input type="radio" id="large">
                                </label>
                            </div>
                            <div class="sidebar__item__size">
                                <label for="medium">
                                    Medium
                                    <input type="radio" id="medium">
                                </label>
                            </div>
                            <div class="sidebar__item__size">
                                <label for="small">
                                    Small
                                    <input type="radio" id="small">
                                </label>
                            </div>
                            <div class="sidebar__item__size">
                                <label for="tiny">
                                    Tiny
                                    <input type="radio" id="tiny">
                                </label>
                            </div>
                        </div>
                        --}}
                        <div class="sidebar__item">
                            <div class="latest-product__text">
                                <h4>Recién agregados</h4>
                                <div class="latest-product__slider owl-carousel">
                                    @php
                                        $k = 0;
                                    @endphp

                                    @for($i = 0; $i < $carousel_quantity; $i++)
                                    <div class="latest-prdouct__slider__item">
                                        @if(isset($last_products[$i+$k]))
                                        <a href="/producto/{{ $last_products[$i+$k]['slug'] }}" class="latest-product__item">
                                            <div class="latest-product__item__pic">
                                                <img src="{{ $last_products[$i+$k]['imagen'] ? $last_products[$i+$k]['imagen'] : $image_not_available }}" alt="" style="width:110px !important">
                                            </div>
                                            <div class="latest-product__item__text">
                                                <h6>{{ $last_products[$i+$k]['nombre'] }}</h6>
                                                <span>S/{{ number_format($last_products[$i+$k]['price'], 2, '.', '') }}</span>
                                            </div>
                                        </a>
                                        @endif

                                        @php
                                            $k++;
                                        @endphp

                                        @if(isset($last_products[$i+$k]))
                                        <a href="/producto/{{ $last_products[$i+$k]['slug'] }}" class="latest-product__item">
                                            <div class="latest-product__item__pic">
                                                <img src="{{ $last_products[$i+$k]['imagen'] ? $last_products[$i+$k]['imagen'] : $image_not_available }}" alt="" style="width:110px !important">
                                            </div>
                                            <div class="latest-product__item__text">
                                                <h6>{{ $last_products[$i+$k]['nombre'] }}</h6>
                                                <span>S/{{ number_format($last_products[$i+$k]['price'], 2, '.', '') }}</span>
                                            </div>
                                        </a>
                                        @endif
                                    </div>
                                    @endfor
                                    {{--
                                    <div class="latest-prdouct__slider__item">
                                        <a href="#" class="latest-product__item">
                                            <div class="latest-product__item__pic">
                                                <img src="/store/img/latest-product/lp-1.jpg" alt="">
                                            </div>
                                            <div class="latest-product__item__text">
                                                <h6>Crab Pool Security</h6>
                                                <span>$30.00</span>
                                            </div>
                                        </a>
                                        <a href="#" class="latest-product__item">
                                            <div class="latest-product__item__pic">
                                                <img src="/store/img/latest-product/lp-2.jpg" alt="">
                                            </div>
                                            <div class="latest-product__item__text">
                                                <h6>Crab Pool Security</h6>
                                                <span>$30.00</span>
                                            </div>
                                        </a>
                                        <a href="#" class="latest-product__item">
                                            <div class="latest-product__item__pic">
                                                <img src="/store/img/latest-product/lp-3.jpg" alt="">
                                            </div>
                                            <div class="latest-product__item__text">
                                                <h6>Crab Pool Security</h6>
                                                <span>$30.00</span>
                                            </div>
                                        </a>
                                    </div>
                                    --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-7">
                    <div class="product__discount">
                        <div class="section-title product__discount__title">
                            <h2 id="category_name">Productos</h2>
                        </div>
                    </div>
                    <div class="shoping__cart__table Fixed" style="margin-bottom: 15px;right: 10px;top: 70px;width: 90%">
                        <a href="#" class="btn-cart" id="show_cart" style="float: right;"><i class="fa fa-shopping-cart"></i></a>
                        <table style="display: none; background: #eee;float: right;width: 40%;" id="table_cart">
                            <thead>
                                <tr>
                                    <th class="shoping__product pl-5" style="padding-bottom: 0px;">Producto</th>
                                    <th style="padding-bottom: 0px;">Precio</th>
                                    <th style="padding-bottom: 0px;">Cantidad</th>
                                    <th style="padding-bottom: 0px;">Total</th>
                                    <th style="padding-bottom: 0px;"></th>
                                </tr>
                            </thead>
                            <tbody id="detail_tbody">
                                <tr>
                                    <td class="shoping__cart__item" style="padding-bottom: 10px;padding-top: 10px">
                                        <h5>Vegetable’s Package</h5>
                                    </td>
                                    <td class="shoping__cart__price" style="padding-bottom: 10px;padding-top: 10px">
                                        $55.00
                                    </td>
                                    <td class="shoping__cart__quantity" style="padding-bottom: 10px;padding-top: 10px">
                                        2
                                        {{--
                                        <div class="quantity">
                                            <div class="pro-qty">
                                                <input type="text" value="1">
                                            </div>
                                        </div>
                                        --}}
                                    </td>
                                    <td class="shoping__cart__total" style="padding-bottom: 10px;padding-top: 10px">
                                        $110.00
                                    </td>
                                    <td class="shoping__cart__item__close" style="padding-bottom: 10px;padding-top: 10px">
                                        <span class="icon_close"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="shoping__cart__item" style="padding-bottom: 10px;padding-top: 10px">
                                        <h5>Vegetable’s Package</h5>
                                    </td>
                                    <td class="shoping__cart__price" style="padding-bottom: 10px;padding-top: 10px">
                                        $55.00
                                    </td>
                                    <td class="shoping__cart__quantity" style="padding-bottom: 10px;padding-top: 10px">
                                        3
                                    </td>
                                    <td class="shoping__cart__total" style="padding-bottom: 10px;padding-top: 10px">
                                        $110.00
                                    </td>
                                    <td class="shoping__cart__item__close" style="padding-bottom: 10px;padding-top: 10px">
                                        <span class="icon_close"></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="products-grid">
                        @include('store.products.grid')
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->
@stop

@section('plugins-js')



<script type="text/javascript">
    var categorySelected = getValueParameter('categoria'), perPage = 9, q = getValueParameter('q');

    fetch_data(1, categorySelected, perPage, q);

    function fetch_data(page, category_slug, per_page, q) {
        // ocultar();
        $.ajax({
            url: `/products/paginated?page=${page}&category_slug=${category_slug}&per_page=${per_page}&q=${q}`,
            success: function(data) {
                $('#products-grid').html(data);
            }
        });
    }

    $(document).on('click', '.category__change', function(E) {
        E.preventDefault();
        let _that = $(this);
        let _categorySlug = _that[0].dataset.slug;
        categorySelected = _categorySlug;
        q = ``;
        newUrl = `?categoria=${_categorySlug}`;
        window.history.replaceState("", "", newUrl);
        fetch_data(1, categorySelected, perPage, q);
    });

    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        let _page = $(this).attr('href').split('&page=')[1];
        fetch_data(_page, categorySelected, perPage, q);
    });

    function getValueParameter(parameter) {
        var url_string = window.location.href;
        var url = new URL(url_string);
        let _risposta = url.searchParams.get(parameter);

        if (_risposta == null || _risposta == '') {
            return '';
        }
        return _risposta;
    }


</script>
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