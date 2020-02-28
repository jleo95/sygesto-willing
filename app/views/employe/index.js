var $table = $('#employe-table'),
    $alertBtn = $('#alertBtn'),
    full_screen = false,
    nameEmploye = '';

$().ready(function() {

    // ajout d'un fournisseur
    $('#formAddEmploye').submit(function (e) {
        e.preventDefault();
        var valideFormAdd = true;

        if ($('#nomAddEmploye').val() == '') {
            fieldForm($('#nomAddEmploye'), 'Ce champs est obligatoire', 1);
            valideFormAdd = false;
        }else {
            fieldForm($('#nomAddEmploye'), '', 0);
        }

        if ($('#prenomAddEmploye').val() == '') {
            fieldForm($('#prenomAddEmploye'), 'Ce champs est vide', 1);
        }else {
            fieldForm($('#prenomAddEmploye'), '', 0);
        }

        if ($('#nomAddEmploye').val() == '') {
            fieldForm($('#nomAddEmploye'), 'Ce champs est obligatoire', 1);
            valideFormAdd = false;
        }else {
            fieldForm($('#nomAddEmploye'), '', 0);
        }

        if ($('#telephone1AddEmploye').val() == '') {
            fieldForm($('#telephone1AddEmploye'), 'Ce champs est obligatoire', 1);
            valideFormAdd = false;
        }else {
            fieldForm($('#telephone1AddEmploye'), '', 0);
        }

        if ($('#telephone2AddEmploye').val() == '') {
            fieldForm($('#telephone2AddEmploye'), 'Ce champs est vide', 1);
        }else {
            fieldForm($('#telephone2AddEmploye'), '', 0);
        }


        if ($('#sexeAddEmploye:checked').val() == '') {
            fieldForm($('#sexeAddEmploye'), 'Ce champs est obligatoire', 1);
            valideFormAdd = false;
            console.log()
        }else {
            fieldForm($('#sexeAddEmploye'), '', 0);
        }
        console.log($('input [name=sexeAddEmploye]:checked').val());

        // valideFormAdd = false;


        if (valideFormAdd) {
            $.ajax({
                url: 'employe/add',
                type: 'post',
                dataType: 'json',
                data: $(this).serialize(),
                success: function (data) {
                    console.log(data);
                    if (data.error == 0) {
                        $('#bodyTableEmploye').html(data.bodyTableEmploye);
                        $('#addEmploye .modal-body form').html('<p>Un nouveau fournisseur a été ajouté à ligne #1</p>');
                        $('#addEmploye .modal-content').append(data.modalFooter);
                        $('#addEmploye .modal-dialog').addClass('small-modal');
                    }else if (data.error == 1) {
                        $('#addEmploye .errorAdd').html(data.mssge);
                    }else {
                        $('#addEmploye .modal-header h4').html('Echec');
                        $('#addEmploye .modal-body').html('<p>Erreur de serveur. Le fournisseur n\'a pas été ajouté. <br>Veillez réessayer plutard</p>');
                        $('#addEmploye .modal-content').append(data.modalFooter);
                        $('#addEmploye .modal-dialog').addClass('small-modal');
                    }

                },
                error: function (data) {
                    alert('Erreur de chargement reessayer plutard');
                }
            })
        }

    });

    $table.bootstrapTable({
        toolbar: ".toolbar",

        showRefresh: true,
        search: true,
        showColumns: true,
        pagination: true,
        striped: true,
        pageSize: 8,
        pageList: [8, 10, 25, 50, 100],

        formatShowingRows: function (pageFrom, pageTo, totalRows) {
            //do nothing here, we don't want to show the text "showing x of y from..."
        },
        formatRecordsPerPage: function (pageNumber) {
            return pageNumber + " lignes visible";
        },
        icons: {
            refresh: 'fa fa-refresh',
            toggle: 'fa fa-th-list',
            columns: 'fa fa-columns',
            detailOpen: 'fa fa-plus-circle',
            detailClose: 'fa fa-minus-circle'
        }
    });

});

function laodDataForDeleteEmploye(idEmploye) {
    $('#hiddenIdEmploye').val(idEmploye);
    $.ajax({
        url: 'employe/laodDataForDeleteEmploye',
        type: 'post',
        dataType: 'json',
        data: {idEmploye: idEmploye},
        success: function (data) {
            nameEmploye = data.nameEmploye;
            $('#deletEmploye .modal-body').html(data.modalBody);
            $('#deletEmploye .modal-content .modal-header h4').html('Suppression');
        },
        error: function (xhr) {
            alert('Erreur de chargement. Veillez réessayer plutard');
        }
    })
}

function deleteEmploye() {
    $.ajax({
        url: 'employe/delete',
        type: 'post',
        dataType: 'json',
        data: {idEmploye: $('#hiddenIdEmploye').val()},
        success: function (data) {
            if (data.succes == 0) {
                $('#bodyTableEmploye').html(data.tbodyEmploye);
                $.gritter.add({
                    title: 'Suppression',
                    text: 'L\'employé ' + nameEmploye + ' a été supprimé',
                    image: 'assets/img/confirm.png'
                });
            }else {
                $.gritter.add({
                    title: 'Suppression',
                    text: 'Echec: L\'employé ' + nameEmploye + ' n\a pas été supprimé. <br> Veillez réessayer plutard',
                    image: 'assets/img/un.png'
                });
            }

        },
        error: function (xhr) {
            alert('Erreur de chargement. Veillez réessayer plutard');
        }
    })
}

function laodForEditEmploye(idEmploye) {
    $('#hiddenIdEmploye').val(idEmploye);

    $.ajax({
        url: 'employe/laodForEditEmploye',
        type: 'post',
        dataType: 'json',
        data: {idEmploye: idEmploye},
        success: function (data) {
            nameEmploye = data.nameEmploye;
            $('#editeEmploye .modal-body').html(data.modalBody);
            $('#editeEmploye .modal-header h4').html('Editer un employé');
        },
        error: function (xhr) {
            alert('Erreur de chargement veillez réessayer plutard');
        }
    })
}

function contentModalAdd() {
    var contentModal = '<div class="form-group">\n' +
        '                                    <label for="nomAddEmploye">Nom de l\'employé</label>\n' +
        '                                    <input type="text" name="nomAddEmploye" id="nomAddEmploye" class="form-control" placeholder="nom du employe" required>\n' +
        '                                    <span class="help-block"></span>\n' +
        '                                </div>\n' +
        '                                <div class="form-group">\n' +
        '                                    <label for="prenomAddEmploye">Prénom</label>\n' +
        '                                    <input type="text" name="prenomAddEmploye" id="prenomAddEmploye" class="form-control" placeholder="prénom du employe">\n' +
        '                                    <span class="help-block"></span>\n' +
        '                                </div>\n' +
        '\n' +
        '                                <div class="row">\n' +
        '                                    <div class="col-md-6">\n' +
        '                                        <div class="form-group">\n' +
        '                                            <label for="telephone1AddEmploye">Téléphone</label>\n' +
        '                                            <input type="text" name="telephone1AddEmploye" id="telephone1AddEmploye" class="form-control" placeholder="numéro de téléphone" required>\n' +
        '                                            <span class="help-block"></span>\n' +
        '                                        </div>\n' +
        '                                    </div>\n' +
        '                                    <div class="col-md-6">\n' +
        '                                        <div class="form-group">\n' +
        '                                            <label for="telephone2AddEmploye">Téléphone 2</label>\n' +
        '                                            <input type="text" name="telephone2AddEmploye" id="telephone2AddEmploye" class="form-control">\n' +
        '                                            <span class="help-block"></span>\n' +
        '                                        </div>\n' +
        '                                    </div>\n' +
        '                                </div>\n' +
        '\n' +
        '                                <div class="row">\n' +
        '                                    <div class="col-md-8">\n' +
        '                                        <div class="form-group">\n' +
        '                                            <label for="residenceAddEmploye">Résidence</label>\n' +
        '                                            <input type="text" name="residenceAddEmploye" id="residenceAddEmploye" class="form-control" placeholder="ville de residence" required>\n' +
        '                                            <span class="help-block"></span>\n' +
        '                                        </div>\n' +
        '                                    </div>\n' +
        '                                    <div class="col-md-4">\n' +
        '                                        <div class="form-group">\n' +
        '                                            <label for="">Sexe: </label>&nbsp;&nbsp;&nbsp;\n' +
        '                                            <div style="padding-left: 32px;">\n' +
        '                                                <label for="hommeAddEmploye" style="cursor: pointer;"><input type="radio" name="sexeAddEmploye" id="hommeAddEmploye" value="1" required> <i class="fa fa-male" style="font-size: 22px"></i></label>&nbsp;&nbsp;\n' +
        '\n' +
        '                                                <label for="femmeAddEmploye" style="cursor: pointer;"><input type="radio" name="sexeAddEmploye" id="femmeAddEmploye" value="2" required> <i class="fa fa-female" style="font-size: 22px"></i></label>\n' +
        '                                            </div>\n' +
        '                                            <span class="help-block"></span>\n' +
        '                                        </div>\n' +
        '                                    </div>\n' +
        '                                </div>\n' +
        '\n' +
        '                                <div class="clear">\n' +
        '                                    <div class="btn-group right">\n' +
        '                                        <button type="submit" class="btn btn-primary right" id="btnSubFormAdd">Ajouter</button>\n' +
        '                                    </div>\n' +
        '                                </div>';

    $('#addEmploye .modal-body form').html(contentModal);
    $('#addEmploye .modal-header h4').html('Nouvel Employé');
    $('#addEmploye .modal-content').children('.modal-footer').remove();
    $('#addEmploye .modal-dialog').removeClass('small-modal');
}