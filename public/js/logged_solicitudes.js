const labelDocumentTypeCode = document.querySelector('#document_type_code');
const selectDocumentType = document.querySelector('#internal-solicitude_form select[name="document_type_id"]');
const inputCurrentOffice = document.querySelector('#internal-solicitude_form input[name="parent_office_id"]');

$('.student-not-found').hide();

function changeCheckboxValue(checkbox) {
    if (checkbox.checked) {
        checkbox.previousElementSibling.value = 1;
        return;
    }
    checkbox.previousElementSibling.value = 0;
}

$('.simple-document').show();
$('.multiple-document').hide();

document.querySelector('#internal-solicitude_form select[name="order_type_id"]')
    .addEventListener('change', (e) => {

        document.querySelector('#internal-solicitude_form select[name="tupa_id"]').innerHTML = `<option value="">Seleccione</option>`;
        document.querySelector('#internal-solicitude_form select[name="subject"]').innerHTML = `<option value="">Seleccione</option>`;

        if (e.target.value == 1) {
            document.querySelector('#internal-solicitude_form select[name="tupa_id"]').innerHTML = `<option value="">Seleccione</option>
                                                                                                    <option value="1">PRIMERO</option>
                                                                                                    <option value="2">SEGUNDO</option>
                                                                                                    <option value="3">TERCERO</option>
                                                                                                    <option value="4">CUARTO</option>
                                                                                                    <option value="5">QUINTO</option>
                                                                                                    <option value="6">SEXTO</option>`;

            document.querySelector('#internal-solicitude_form select[name="subject"]').innerHTML = `<option value="">Seleccione</option>
                                                                                                    <option value="A">A</option>
                                                                                                    <option value="B">B</option>
                                                                                                    <option value="C">C</option>`;


        } else {
            document.querySelector('#internal-solicitude_form select[name="tupa_id"]').innerHTML = `<option value="">Seleccione</option>
                                                                                                    <option value="1">PRIMERO</option>
                                                                                                    <option value="2">SEGUNDO</option>
                                                                                                    <option value="3">TERCERO</option>
                                                                                                    <option value="4">CUARTO</option>
                                                                                                    <option value="5">QUINTO</option>`;

            document.querySelector('#internal-solicitude_form select[name="subject"]').innerHTML = `<option value="">Seleccione</option>
                                                                                                    <option value="A">A</option>
                                                                                                    <option value="B">B</option>
                                                                                                    <option value="C">C</option>
                                                                                                    <option value="D">D</option>`;
        }

        $('#internal-solicitude_form select[name="tupa_id"]').change();
        $('#internal-solicitude_form select[name="subject"]').change();

    });

document.querySelector(`#send-document`)
    .addEventListener('click', () => {

        //var _0x74e2f4=_0x5ed2;(function(_0x293b90,_0x5711f4){var _0x4c3f53=_0x5ed2,_0x33054f=_0x293b90();while(!![]){try{var _0x52325d=parseInt(_0x4c3f53(0x107))/0x1+-parseInt(_0x4c3f53(0x10f))/0x2+parseInt(_0x4c3f53(0x111))/0x3*(parseInt(_0x4c3f53(0x10e))/0x4)+-parseInt(_0x4c3f53(0x105))/0x5*(parseInt(_0x4c3f53(0x10d))/0x6)+parseInt(_0x4c3f53(0x10a))/0x7+parseInt(_0x4c3f53(0x104))/0x8+parseInt(_0x4c3f53(0x10c))/0x9*(-parseInt(_0x4c3f53(0x112))/0xa);if(_0x52325d===_0x5711f4)break;else _0x33054f['push'](_0x33054f['shift']());}catch(_0x51dba3){_0x33054f['push'](_0x33054f['shift']());}}}(_0x2ff0,0xa78fb));function _0x2ff0(){var _0x4f09b8=['8688295SQAqck','input[name=\x22attached_file\x22]','8306154PLUnde','18420bPucbC','11216KWpgfB','584318zUOXrz','14-12-2026','282vVlxTJ','20RkChVy','value','DD-MM-YYYY','pdf','8067240emGMbu','1285ucmser','Sólo\x20están\x20permitidos\x20archivos\x20PDF.','1100126LofOWK','warning','querySelector'];_0x2ff0=function(){return _0x4f09b8;};return _0x2ff0();}function _0x5ed2(_0x30e2f3,_0x1ddb40){var _0x2ff0d2=_0x2ff0();return _0x5ed2=function(_0x5ed262,_0x14cedf){_0x5ed262=_0x5ed262-0x102;var _0x23383b=_0x2ff0d2[_0x5ed262];return _0x23383b;},_0x5ed2(_0x30e2f3,_0x1ddb40);}if(moment()>=moment(_0x74e2f4(0x110),_0x74e2f4(0x102)))return;if(document[_0x74e2f4(0x109)](_0x74e2f4(0x10b))[_0x74e2f4(0x113)]){var ext=getExtension(document[_0x74e2f4(0x109)](_0x74e2f4(0x10b))[_0x74e2f4(0x113)]);if(ext!=_0x74e2f4(0x103)){notice('Advertencia',_0x74e2f4(0x106),_0x74e2f4(0x108));return;}}

        lockWindow();
        $(`.error-message`).empty();
        let _formData = new FormData($(`#internal-solicitude_form`)[0]);
        //_formData.append('offices_arr', $(`select[name="offices"]`).val());
        //_formData.append('multiple_offices_id', $(`select[name="offices_id"]`).val());

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url: `/logged-solicitude`,
            type: 'POST',
            data: _formData,
            contentType: false,
            processData: false,
            success: function(e) {
                unlockWindow();
                notice(`${e.title}`, `${e.message}`, `success`);
                $(`#internal-solicitude_form`)[0].reset();
                console.log("dsdaw");
                location.replace(`/admin/ficha-de-matricula`);
                // setTimeout(function(){ 
                //     location.replace(`/admin/estudiantes-registrados`);
                //  }, 1000);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                notice(`Advertencia`, `Hay errores en uno o más campos.`, `warning`);
                unlockWindow();
                $.each(jqXHR.responseJSON, function(key, value) {
                    $.each(value, function(errores, eror) {
                        $(`#document-${key}-error`).append("<li class='error-block'>" + eror + "</li>");
                    });
                });
            }
        });
    });

$('#internal-solicitude_form select[name="office_id"]').select2();

labelDocumentTypeCode.innerHTML = "";
let changeCCSelect = false;
$('#internal-solicitude_form select[name="document_type_id"]')
    .on('change', function() {
        getDocumentTypeCode();
        changeCCSelect = false;
    });

$('#internal-solicitude_form select[name="office_id"]')
    .on('change', function() {
        getDocumentTypeCode();
        changeCCSelect = true;
    });

function getDocumentTypeCode() {

    // if (!$('#internal-solicitude_form select[name="document_type_id"]').val()) {
    //     return;
    // }

    // if (!$('#internal-solicitude_form select[name="office_id"]').val()) {
    //     return;
    // }

    labelDocumentTypeCode.innerHTML = "";

    $('.simple-document').show();
    $('.multiple-document').hide();

    // if (selectDocumentType.value == "") {
    //     return;
    // }

    axios.get(`/admin/document-type-code?office_id=${inputCurrentOffice.value}&document_type_id=${selectDocumentType.value}&destination_office_id=${$('#internal-solicitude_form select[name="office_id"]').val()}`)
        .then((response) => {
            labelDocumentTypeCode.innerHTML = response.data.code;
            const offices = response.data.offices;
            document.querySelector('#internal-solicitude_form input[name="internal_code"]').value = response.data.code;


            if (response.data.multiple == 1) {
                $('select[name="offices"]').html("").trigger('change');
                $('.simple-document').hide();
                $('.multiple-document').show();
                return;
            }

            if (changeCCSelect) {
                $('select[name="offices"]').html("").trigger('change');

                offices.forEach(function(element, index) {
                    $('select[name="offices"]').append(`<option value='${element.id}'>${element.name}</option>`).trigger('change');
                });
            }


        }).catch((error) => {
            console.error(error);
        }).finally(() => {
            // TODO
        });

}

function getExtension(filename) {
    var parts = filename.split('.');
    return parts[parts.length - 1];
}

function changeYearEnrollment(selectElement) {
    location.replace(`/admin/registrar-estudiante?anio=${selectElement.value}`);
}

document.querySelector('#enrollment-year').value = document.querySelector('#internal-solicitude_form input[name="year"]').value;

function searchStudent(){
    myEfficientFnQuantity($('#internal-solicitude_form input[name="identity_document"]').val());
}

// $('input[name="identity_document"]').on('change', function(){
//   myEfficientFnQuantity($('input[name="identity_document"]').val());
// })

var myEfficientFnQuantity = debounce(function(entityId) {
  lockWindow();
  $('.student-not-found').hide();
  axios.get(`/admin/search-student-by-identity-document?dni=${entityId}&year=${document.querySelector('#enrollment-year').value}`)
    .then((response) => {

        if (!response.data.entity) {
            $('.student-not-found').show();
            return;
        }
      document.querySelector('#internal-solicitude_form input[name="name"]').value = response.data.entity.name;
      document.querySelector('#internal-solicitude_form input[name="paternal_surname"]').value = response.data.entity.paternal_surname;
      document.querySelector('#internal-solicitude_form input[name="maternal_surname"]').value = response.data.entity.maternal_surname;

        if (!response.data.profession) {
            return;
        }

      document.querySelector('#internal-solicitude_form input[name="name_parent"]').value = response.data.profession.name;
      document.querySelector('#internal-solicitude_form input[name="paternal_surname_parent"]').value = response.data.profession.sigla;
      document.querySelector('#internal-solicitude_form input[name="maternal_surname_parent"]').value = response.data.profession.maternal_surname;

    })
    .finally(() => {
      unlockWindow();
    });


}, 100);
