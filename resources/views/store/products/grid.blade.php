<div class="row">
    @foreach($products as $product)
    @php
        /*info($product);*/
    @endphp
    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="product__item">
            <div class="product__item__pic set-bg" data-setbg="{{ $product['imagen'] ? $product['imagen'] : $image_not_available }}" style="background-image: url('{{ $product['imagen'] ? $product['imagen'] : $image_not_available }}'); background-size: 200px;height: 200px;">
            </div>
            <div class="product__item__text">
                <h6><a href="/producto/{{ $product['slug'] }}">{{ $product['nombre'] }}</a></h6>
                <h5>S/{{ number_format($product['price'], 2, '.', '') }}</h5>

                <div class="">
                    <div class="quantity">
                        <div class="pro-qty">
                            <span class="dec qtybtn">-</span>
                            <input type="text" value="1" class="product_quantity">
                            <span class="inc qtybtn">+</span>
                        </div>
                    </div>
                </div>
                <a href="#" data-index="{{ $product['idarticulo'] }}" class="add_to_shopping_cart btn-cart"><i class="fa fa-shopping-cart"></i> Agregar</a>
            </div>
        </div>
    </div>
    @endforeach

</div>
<div class="product__pagination">
    {{-- $products->appends(request()->except('page'))->links() --}}
    @include('pagination.default', ['paginator' => $products])
</div>