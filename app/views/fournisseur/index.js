var $table = $('#fournisseur-table'),
    $alertBtn = $('#alertBtn'),
    full_screen = false,
    nameFournisseur = '';

$().ready(function() {

    // ajout d'un fournisseur
    $('#formAddFournisseur').submit(function (e) {
        e.preventDefault();
        var valideFormAdd = true;

        if ($('#nomAddFournisseur').val() == '') {
            fieldForm($('#nomAddFournisseur'), 'Ce champs est obligatoire', 1);
            valideFormAdd = false;
        }else {
            fieldForm($('#nomAddFournisseur'), '', 0);
        }

        if ($('#prenomAddFournisseur').val() == '') {
            fieldForm($('#prenomAddFournisseur'), 'Ce champs est vide', 1);
        }else {
            fieldForm($('#prenomAddFournisseur'), '', 0);
        }

        if ($('#nomAddFournisseur').val() == '') {
            fieldForm($('#nomAddFournisseur'), 'Ce champs est obligatoire', 1);
            valideFormAdd = false;
        }else {
            fieldForm($('#nomAddFournisseur'), '', 0);
        }

        if ($('#telephone1AddFournisseur').val() == '') {
            fieldForm($('#telephone1AddFournisseur'), 'Ce champs est obligatoire', 1);
            valideFormAdd = false;
        }else {
            fieldForm($('#telephone1AddFournisseur'), '', 0);
        }

        if ($('#telephone2AddFournisseur').val() == '') {
            fieldForm($('#telephone2AddFournisseur'), 'Ce champs est vide', 1);
        }else {
            fieldForm($('#telephone2AddFournisseur'), '', 0);
        }


        if ($('#villeAddFournisseur').val() == '') {
            fieldForm($('#villeAddFournisseur'), 'Ce champs est obligatoire', 1);
            valideFormAdd = false;
        }else {
            fieldForm($('#villeAddFournisseur'), '', 0);
        }


        if (valideFormAdd) {
            $.ajax({
                url: 'fournisseur/add',
                type: 'post',
                dataType: 'json',
                data: $(this).serialize(),
                success: function (data) {
                    console.log(data);
                    if (data.error == 0) {
                        $('#bodyTableFournisseur').html(data.bodyTableFournisseur);
                        $('#addFournisseur .modal-body form').html('<p>Un nouveau fournisseur a été ajouté avec succès</p>');
                        $('#addFournisseur .modal-content').append(data.modalFooter);
                        $('#addFournisseur .modal-dialog').addClass('small-modal');
                    }else if (data.error == 1) {
                        $('#addFournisseur .errorAdd').html(data.mssge);
                    }else {
                        $('#addFournisseur .modal-header h4').html('Echec');
                        $('#addFournisseur .modal-body').html('<p>Erreur de serveur. Le fournisseur n\'a pas été ajouté. <br>Veillez réessayer plutard</p>');
                        $('#addFournisseur .modal-content').append(data.modalFooter);
                        $('#addFournisseur .modal-dialog').addClass('small-modal');
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

function confirmAdd(status) {
    console.log(status);
    if (status == 1) {
        $.gritter.add({
            title: 'Ajout',
            text: 'Erreur de serveur. Le fournisseur n\a pas été ajouté. <br> Veillez réessayer plutard',
            image: 'assets/img/un.png'
        });
    }else {
        $.gritter.add({
            title: 'Ajout',
            text: 'Vous avez ajouté un fournisseur',
            // image: 'assets/img/un.png'
            image: 'assets/img/confirm.png'
        });
    }

    $('#addProuit .modal-dialog').removeClass('small-modal');

}

function laodDataForDeleteFournisseur(idFournisseur) {
    $('#hiddenIdFournisseur').val(idFournisseur);

    $.ajax({
        url: 'fournisseur/laodDataForDeleteFournisseur',
        type: 'post',
        dataType: 'json',
        data: {idFournisseur: idFournisseur},
        success: function (data) {
            nameFournisseur = data.nameFournisseur;
            $('#deletFournisseur .modal-body').html(data.modalBody);
            $('#deletFournisseur .modal-content .modal-header h4').html('Suppression');
        },
        error: function () {
            alert('Erreur de chargement');
        }
    })
}

function deleteFournisseur() {
    $.ajax({
        url: 'fournisseur/delete',
        type: 'post',
        dataType: 'json',
        data: {idFournisseur: $('#hiddenIdFournisseur').val()},
        success: function (data) {
            if (data.succes == 0) {
                $('#bodyTableFournisseur').html(data.tbodyFournisseur);
                $.gritter.add({
                    title: 'Suppression',
                    text: 'Le fournisseur ' + nameFournisseur + ' a été supprimé',
                    image: 'assets/img/confirm.png'
                });
            }else {
                $.gritter.add({
                    title: 'Suppression',
                    text: 'Echec: Le fournisseur ' + nameFournisseur + ' n\a pas été supprimé. <br> Veillez réessayer plutard',
                    image: 'assets/img/un.png'
                });
            }

        },
        error: function (xhr) {
            alert('Erreur de chargement. Veillez réessayer plutard');
        }
    })
}

function laodForEditFournisseur(idFournisseur) {
    $('#hiddenIdFournisseur').val(idFournisseur);

    $.ajax({
        url: 'fournisseur/laodForEditFournisseur',
        type: 'post',
        dataType: 'json',
        data: {idFournisseur: idFournisseur},
        success: function (data) {
            nameFournisseur = data.nameFournisseur;
            $('#editeFournisseur .modal-body').html(data.modalBody);
            $('#editeFournisseur .modal-header h4').html('Editer un Fournisseur');
        },
        error: function (xhr) {
            alert('Erreur de chargement. Veillez réessayer plutard');
        }
    })
}

function contentModalAdd() {
    var contentModal = '<div class="form-group">\n' +
        '                                    <label for="nomAddFournisseur">Nom</label>\n' +
        '                                    <input type="text" name="nomAddFournisseur" id="nomAddFournisseur" class="form-control" placeholder="nom du fournisseur" required>\n' +
        '                                    <span class="help-block"></span>\n' +
        '                                </div>\n' +
        '                                <div class="form-group">\n' +
        '                                    <label for="prenomAddFournisseur">Prénom</label>\n' +
        '                                    <input type="text" name="prenomAddFournisseur" id="prenomAddFournisseur" class="form-control" placeholder="prénom du fournisseur" required>\n' +
        '                                    <span class="help-block"></span>\n' +
        '                                </div>\n' +
        '\n' +
        '                                <div class="row">\n' +
        '                                    <div class="col-md-6">\n' +
        '                                        <div class="form-group">\n' +
        '                                            <label for="telephone1AddFournisseur">Téléphone</label>\n' +
        '                                            <input type="text" name="telephone1AddFournisseur" id="telephone1AddFournisseur" class="form-control" placeholder="numéro de téléphone" required>\n' +
        '                                            <span class="help-block"></span>\n' +
        '                                        </div>\n' +
        '                                    </div>\n' +
        '                                    <div class="col-md-6">\n' +
        '                                        <div class="form-group">\n' +
        '                                            <label for="telephone2AddFournisseur">Téléphone 2</label>\n' +
        '                                            <input type="text" name="telephone2AddFournisseur" id="telephone2AddFournisseur" class="form-control">\n' +
        '                                            <span class="help-block"></span>\n' +
        '                                        </div>\n' +
        '                                    </div>\n' +
        '                                </div>\n' +
        '\n' +
        '                                <div class="row">\n' +
        '                                    <div class="col-md-10">\n' +
        '                                        <div class="form-group">\n' +
        '                                            <label for="villeAddFournisseur">Ville</label>\n' +
        '                                            <input type="text" name="villeAddFournisseur" id="villeAddFournisseur" class="form-control" placeholder="ville de residence" required>\n' +
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

    $('#addFournisseur .modal-body .container-form-AddFournisseur form').html(contentModal);
    $('#addFournisseur .modal-content .modal-header h4').html('Nouveau Fournisseur');
    $('#addFournisseur .modal-content').children('.modal-footer').remove();
    $('#addFournisseur .modal-dialog').removeClass('small-modal');
}