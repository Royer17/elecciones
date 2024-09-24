@extends('store.layouts.index')
@section('content')

    <!-- Product Section Begin -->
    <section class="product spad header-shadow">
        <div class="container">
            
            <div class="row">
             
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div id="form_container" class="container">
                       
                        <form id=" " class="" method="post" action=" ">

                            <div class="card-header">Datos Estudiante</div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="tipo_persona_field" class="col-2">Tipo de persona</label>
                                    <select class="form-control form-control-sm form-control-sm-xx col-4" id="tipo_persona_field" name="tipo_persona">
                                        <option value="1">Natural</option>
                                        <option value="2">Jurídica</option>
                                    </select>
                                </div>	
                                <div id="externo_1">
                                    <div class="form-group row">
                                        <label for="dni_field" class="col-2">DNI</label>
                                        <div class="input-group col-4" style="padding: 0px;">
                                            <input type="number" class="form-control form-control-sm externo-1" id="dni_field" placeholder="" name="externo_dniruc" aria-describedby="buscar_dni_bt" required="">
                                           
                                            <div class="invalid-feedback">
                                            ---
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="nombre_field" class="col-2">Nombres</label>
                                        <input type="text" class="form-control form-control-sm col externo-1 text-uppercase" id="nombre_field" placeholder="" name="externo_nombre">
                                    </div>
                                    <div class="form-group row">
                                        <label for="apelpa_field" class="col-2">Apellido paterno</label>
                                        <input type="text" class="form-control form-control-sm col-4 externo-1 text-uppercase" id="apelpa_field" placeholder="" name="externo_apelpa">
                                        <label for="apelma_field" class="col-2">Apellido materno</label>
                                        <input type="text" class="form-control form-control-sm col-4 externo-1 text-uppercase" id="apelma_field" placeholder="" name="externo_apelma">
                                    </div>
                                </div>
                                <div id="externo_2" class="" style="display: none;">
                                    <div class="form-group row">
                                        <label for="ruc_field" class="col-2">RUC</label>
                                        <input type="number" class="form-control form-control-sm col-4 externo-2 text-uppercase" id="ruc_field" placeholder="" name="externo_dniruc" disabled="disabled">
                                    </div>
                                    <div class="form-group row">
                                        <label for="razon_social_field" class="col-2">Razon Social</label>
                                        <input type="text" class="form-control form-control-sm col externo-2 text-uppercase" id="razon_social_field" placeholder="" name="externo_nombre" disabled="disabled">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="telefono_field" class="col-2">Telefono</label>
                                    <input type="text" class="form-control form-control-sm col-4 text-uppercase" id="telefono_field" placeholder="" name="externo_telefono">
                                    <label for="email_field" class="col-2">Correo electronico</label>
                                    <input type="email" class="form-control form-control-sm col-4 text-uppercase" id="email_field" placeholder="" name="externo_email">
                                </div>
                                <div class="form-group row">
                                    <label for="direccion_field" class="col-2">Direccion</label>
                                    <input type="text" class="form-control form-control-sm col text-uppercase" id="direccion_field" placeholder="" name="externo_direccion">
                                </div>
                            </div>
                            <div class="card-header">Documento</div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="tipodoc_field" class="col-2">Tipo de Documento</label>
                                    <div class="col-4 pl-0 pr-0">
                                        <select class="form-control form-control-sm" id="tipodoc_field" name="tdoc_cod" aria-describedby="tipodoc_helptext">
                                                        <option value="130">OFICIO</option>	
                                                        <option value="129">CARTA</option>	
                                                        <option value="128">SOLICITUD</option>	
                                                        <option value="292">OTRO</option>	
                                                    </select>
                                        <div id="tipodoc_helptext" class="form-text text-muted"></div>
                                    </div>
                                    <input id="tipodoc_desc_field" type="hidden" name="tdoc_descripcion" value="OFICIO">
                                </div>
                                <div class="form-group row">
                                    <label for="documento_nrodoc_field" class="col-2">Nº de documento</label>
                                    <input type="text" class="form-control form-control-sm col-4 text-uppercase" id="documento_nrodoc_field" placeholder="" name="documento_nrodoc" value="S/N">
                                </div>
                                <div class="form-group row">
                                    <label for="documento_folios_field" class="col-2">Nº de folios</label>
                                    <input type="number" class="form-control form-control-sm col-2" id="documento_folios_field" placeholder="" name="documento_folios" value="1">
                                </div>
                                <div class="form-group row">
                                    <label for="documento_desc_field" class="col-2">Asunto</label>
                                    <div class="col pl-0 pr-0">
                                        <textarea class="form-control form-control-sm text-uppercase" id="documento_desc_field" rows="3" name="documento_desc" maxlength="500"></textarea>
                                        <div id="documento_pdf_helptext" class="form-text text-muted">
                                        Maximo 500 caracteres.
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="documento_obsev_field" class="col-2">Notas y/o referencias</label>
                                    <div class="col pl-0 pr-0">
                                        <textarea class="form-control form-control-sm" id="documento_obsev_field" rows="3" name="documento_obsev" maxlength="500"></textarea>
                                        <div id="documento_pdf_helptext" class="form-text text-muted">
                                            Maximo 500 caracteres.
                                        </div>
                                    </div>
                                </div>
                                <!--<div class="form-group row">
                                    <div class="col-2"></div>
                                    <div class="col pl-0">
                                        <div class="alert alert-warning" role="alert">
                                            <b>Importante:</b>
                                            El documento PDF, tiene
                                        </div>
                                    </div>
                                </div>-->
                                <div class="form-group row">
                                    <label for="documento_pdf_field" class="col-2">Archivo</label>
                                    <div class="col pl-0 pr-0">
                                        <input type="file" class="form-control-file text-uppercase" id="documento_pdf_field" name="documento_pdf" accept="application/pdf" aria-describedby="documento_pdf_helptext">
                                        <div id="documento_pdf_helptext" class="form-text text-muted">
                                        Solo se admite el archivo en formato PDF, y con un tamaño maximo de 15MB.
                                        </div>
                                    </div>
                                </div>	
                                
                                <!-- 
                                <div class="form-group row">
                                    <div class="col-2"></div>
                                    <div id="gr_container" class="col pl-0"><div style="width: 304px; height: 78px;"><div>
                                    <iframe src="https://www.google.com/recaptcha/api2/anchor?ar=1&amp;k=6LdrfPQUAAAAAOPd3Wz27cS_yGAjnwNdTrzUpCq0&amp;co=aHR0cDovL21lc2FkZXBhcnRlcy5yZWdpb250YWNuYS5nb2IucGU6ODA.&amp;hl=es-419&amp;v=6TWYOsKNtRFaLeFqv5xN42-l&amp;size=normal&amp;cb=krjjulqyhhh9" width="304" height="78" role="presentation" name="a-u5el37tn6h59" frameborder="0" scrolling="no" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox"></iframe></div><textarea id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid rgb(193, 193, 193); margin: 10px 25px; padding: 0px; resize: none; display: none;"></textarea></div><iframe style="display: none;"></iframe></div>
                                </div>
                                <div id="error_alert" class="alert alert-danger" role="alert" style="display: none;">
                                Error
                                </div>  --->
                                <div class="text-right">
                                    <!-- Button trigger modal -->
                                    <button id="send_trigger_bt" type="button" class="btn btn-primary" data-toggle="modal" data-target="#send_modal" data-backdrop="static">
                                        Enviar
                                    </button>
                                </div>
                            </div>
                            
                        </form>
                    </div>


                    
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->
@stop
























@section('plugins-js')

<script type="text/javascript">
    var categorySelected = getValueParameter('categoria'), perPage = 30, q = getValueParameter('q');

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
        let _page = $(this).attr('href').split('?page=')[1];
        //console.log(_page);
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

    //update_sticky_cart();

    // function update_sticky_cart(){
    //     axios.get(`/cart-detail?ids=${localStorage.getItem("cart")}`)
    //         .then((response) => {
    //             draw_detail(response.data);
    //         });
    // }

    // function draw_detail(products){
    //     document.querySelector(`#detail_tbody`).innerHTML = ``;
    //     let _content = "";
    //     products.forEach((value) => {
    //         _content += `
    //                         <tr>
    //                             <td class="shoping__cart__item pl-5" style="padding-bottom: 10px;padding-top: 10px">
    //                                 <h5>${value.name}</h5>
    //                             </td>
    //                             <td class="shoping__cart__price" style="padding-bottom: 10px;padding-top: 10px">
    //                                 S/${value.price}
    //                             </td>
    //                             <td class="shoping__cart__quantity" style="padding-bottom: 10px;padding-top: 10px">
    //                                 ${value.quantity}
    //                             </td>
    //                             <td class="shoping__cart__total" style="padding-bottom: 10px;padding-top: 10px">
    //                                 S/${(value.price*value.quantity).toFixed(2)}
    //                             </td>
    //                             <td class="shoping__cart__item__close" style="padding-bottom: 10px;padding-top: 10px">
    //                                 <span class="icon_close product__remove" data-index="${value.id}"></span>
    //                             </td>
    //                         </tr>
    //                         `;

    //     });

    //     _content += `
    //                 <tr>
    //                     <td class="shoping__cart__item" style="padding-bottom: 10px;padding-top: 10px">

    //                     </td>
    //                     <td class="shoping__cart__price" style="padding-bottom: 10px;padding-top: 10px">

    //                     </td>
    //                     <td class="shoping__cart__quantity" style="padding-bottom: 10px;padding-top: 10px">

    //                     </td>
    //                     <td class="shoping__cart__total" style="padding-bottom: 10px;padding-top: 1px">
    //                         <a href="/orden" class="btn-cart" style="font-weight:300;font-size:15px;">Comprar</a>
    //                     </td>
    //                     <td class="shoping__cart__item__close" style="padding-bottom: 10px;padding-top: 10px">
    //                     </td>
    //                 </tr>`;

    //     document.querySelector(`#detail_tbody`).innerHTML = _content;
    // }

    // $(document).on('click', '.product__remove', function(){
    //     let _id = $(this)[0].dataset.index;
    //     if (localStorage.getItem(`cart`) != null) {
    //         let _cartArray = JSON.parse(localStorage.getItem("cart"));
    //         delete _cartArray[_id];
    //         localStorage.setItem(`cart`, JSON.stringify(_cartArray));
    //     }

    //     location.reload();
    // });


    // var show = false;

    // document.querySelector(`#show_cart`)
    //     .addEventListener('click', function(e){
    //         e.preventDefault();
    //         if (!show) {
    //             $(`#table_cart`).fadeIn(200);
    //             $(`#btn_orden`).fadeIn(200);
    //             show = true;
    //             return;
    //         }

    //         if (show) {
    //             $(`#table_cart`).fadeOut(200);
    //             $(`#btn_orden`).fadeOut(200);
    //             show = false;
    //             return;
    //         }

    //     });
    $(document).on('click', '.qtybtn', function(){
        var $button = $(this);
        var oldValue = $button.parent().find('input').val();
        if ($button.hasClass('inc')) {
            var newVal = parseFloat(oldValue) + 1;
        } else {
            // Don't allow decrementing below zero
            if (oldValue > 0) {
                var newVal = parseFloat(oldValue) - 1;
            } else {
                newVal = 0;
            }
        }
        $button.parent().find('input').val(newVal);
    });

    // proQty.on('click', '.qtybtn', function () {

    // });

</script>


@stop