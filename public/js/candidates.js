const candidateAdd = document.querySelector('#new_candidate');
const candidateSave = document.querySelector('#modal-candidate .save');
const candidateUpdate = document.querySelector('#modal-candidate .update');

candidateAdd.addEventListener('click', (e) => {
    e.preventDefault();
    $(`.error-message`).empty();

    document.querySelector('#modal-candidate form').reset();
    $('#candidate-update').remove();
    $('#modal-candidate').modal('show');

    $('#modal-candidate .save').show();
    $('#modal-candidate .update').hide();
    $('#modal-candidate .modal-title').text('Nuevo Candidato');
});

candidateSave.addEventListener('click', (e) => {

    lockWindow();
    $(`.error-message`).empty();
    let _formData = new FormData($(`#modal-candidate form`)[0]);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name=_token]').val()
        }
    });
    $.ajax({
        url: `/admin/candidate`,
        type: 'POST',
        data: _formData,
        contentType: false,
        processData: false,
        success: function(e) {
            unlockWindow();
            notice(`${e.title}`, `${e.message}`, `success`);
            $(`#modal-candidate form`)[0].reset();
            $('#modal-candidate').modal('hide');
            location.reload();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            notice(`Advertencia`, `Hay errores en uno o más campos.`, `warning`);
            unlockWindow();
            $.each(jqXHR.responseJSON, function(key, value) {
                $.each(value, function(errores, eror) {
                    $(`#candidate-${key}-error`).append("<li class='error-block'>" + eror + "</li>");
                });
            });
        }
    });

});

candidateUpdate.addEventListener('click', (e) => {
    lockWindow();
    $(`.error-message`).empty();
    let _formData = new FormData($(`#modal-candidate form`)[0]);
    const id = document.querySelector('#modal-candidate input[name=id]').value;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name=_token]').val()
        }
    });
    $.ajax({
        url: `/admin/candidate/${id}`,
        type: 'POST',
        data: _formData,
        contentType: false,
        processData: false,
        success: function(e) {
            unlockWindow();
            notice(`${e.title}`, `${e.message}`, `success`);
            $(`#modal-candidate form`)[0].reset();
            $('#modal-candidate').modal('hide');
            location.reload();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            notice(`Advertencia`, `Hay errores en uno o más campos.`, `warning`);
            unlockWindow();
            $.each(jqXHR.responseJSON, function(key, value) {
                $.each(value, function(errores, eror) {
                    $(`#candidate-${key}-error`).append("<li class='error-block'>" + eror + "</li>");
                });
            });
        }
    });

});

function editCandidate(btn) {
    const id = btn.getAttribute('data-id');
    $('#candidate-update').remove();
    
    fetch(`/admin/candidate/${id}`)
        .then(response => response.json())
        .then(data => {
            document.querySelector('#modal-candidate form').reset();
            document.querySelector('#modal-candidate input[name=id]').value = data.id;
            document.querySelector('#modal-candidate select[name=position]').value = data.position;
            document.querySelector('#modal-candidate select[name=nivel]').value = data.nivel;
            document.querySelector('#modal-candidate input[name=cedula]').value = data.cedula;
            document.querySelector('#modal-candidate input[name=firstname]').value = data.firstname;
            document.querySelector('#modal-candidate input[name=lastname]').value = data.lastname;

            $('#modal-candidate').modal('show');
        
            $('#modal-candidate .save').hide();
            $('#modal-candidate .update').show();
            $('#modal-candidate .modal-title').text('Editar Candidato');
            addInputPut($(`#modal-candidate form`), 'candidate-update');

        });
    

}

