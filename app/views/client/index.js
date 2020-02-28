var $table = $('#client-table'),
    $alertBtn = $('#alertBtn'),
    full_screen = false,
    nameClient = '';

$().ready(function() {

    // ajout d'un fournisseur
    $('#formAddClient').submit(function (e) {
        e.preventDefault();
        var valideFormAdd = true;

        if ($('#nomAddClient').val() == '') {
            fieldForm($('#nomAddClient'), 'Ce champs est obligatoire', 1);
            valideFormAdd = false;
        }else {
            fieldForm($('#nomAddClient'), '', 0);
        }

        if ($('#prenomAddClient').val() == '') {
            fieldForm($('#prenomAddClient'), 'Ce champs est vide', 1);
        }else {
            fieldForm($('#prenomAddClient'), '', 0);
        }

        if ($('#nomAddClient').val() == '') {
            fieldForm($('#nomAddClient'), 'Ce champs est obligatoire', 1);
            valideFormAdd = false;
        }else {
            fieldForm($('#nomAddClient'), '', 0);
        }

        if ($('#telephone1AddClient').val() == '') {
            fieldForm($('#telephone1AddClient'), 'Ce champs est obligatoire', 1);
            valideFormAdd = false;
        }else {
            fieldForm($('#telephone1AddClient'), '', 0);
        }

        if ($('#telephone2AddClient').val() == '') {
            fieldForm($('#telephone2AddClient'), 'Ce champs est vide', 1);
        }else {
            fieldForm($('#telephone2AddClient'), '', 0);
        }


        if ($('#villeAddClient').val() == '') {
            fieldForm($('#villeAddClient'), 'Ce champs est obligatoire', 1);
            valideFormAdd = false;
        }else {
            fieldForm($('#villeAddClient'), '', 0);
        }


        if (valideFormAdd) {
            $.ajax({
                url: 'client/add',
                type: 'post',
                dataType: 'json',
                data: $(this).serialize(),
                success: function (data) {
                    console.log(data);
                    if (data.error == 0) {
                        $('#bodyTableClient').html(data.bodyTableClient);
                        $('#addClient .modal-body form').html('<p>Un nouveau fournisseur a été ajouté à ligne #1</p>');
                        $('#addClient .modal-content').append(data.modalFooter);
                        $('#addClient .modal-dialog').addClass('small-modal');
                    }else if (data.error == 1) {
                        $('#addClient .errorAdd').html(data.mssge);
                    }else {
                        $('#addClient .modal-header h4').html('Echec');
                        $('#addClient .modal-body').html('<p>Erreur de serveur. Le fournisseur n\'a pas été ajouté. <br>Veillez réessayer plutard</p>');
                        $('#addClient .modal-content').append(data.modalFooter);
                        $('#addClient .modal-dialog').addClass('small-modal');
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

function deleteClient() {
    $.ajax({
        url: 'client/delete',
        type: 'post',
        dataType: 'json',
        data: {idClient: $('#hiddenIdClient').val()},
        success: function (data) {
            if (data.succes == 0) {
                $('#bodyTableClient').html(data.tbodyClient);
                $.gritter.add({
                    title: 'Suppression',
                    text: 'Le client ' + nameClient + ' a été supprimé',
                    image: 'assets/img/confirm.png'
                });
            }else {
                $.gritter.add({
                    title: 'Suppression',
                    text: 'Echec: Le client ' + nameClient + ' n\a pas été supprimé. <br> Veillez réessayer plutard',
                    image: 'assets/img/un.png'
                });
            }

        },
        error: function (xhr) {
            alert('Erreur de chargement. Veillez réessayer plutard');
        }
    })
}

function laodDataForDeleteClient(idClient) {
    $('#hiddenIdClient').val(idClient);

    $.ajax({
        url: 'client/laodDataForDeleteClient',
        type: 'post',
        dataType: 'json',
        data: {idClient: idClient},
        success: function (data) {
            nameClient = data.nameClient;
            $('#deletClient .modal-body').html(data.modalBody);
            $('#deletClient .modal-content .modal-header h4').html('Suppression');
        },
        error: function (xhr) {
            alert('Erreur de chargement. Veillez réessayer');
        }
    })
}

function laodForEditClient (idClient) {
    $('#hiddenIdClient').val(idClient);

    $.ajax({
        url: 'client/laodForEditClient ',
        type: 'post',
        dataType: 'json',
        data: {idClient: idClient},
        success: function (data) {
            nameClient = data.nameClient;
            $('#editeClient .modal-body').html(data.modalBody);
            $('#editeClient .modal-header h4').html('Editer un Fournisseur');
        },
        error: function (xhr) {
            alert('Erreur de chargement. Veillez réessayer plutard');
        }
    })
}

function confirmAdd(status) {
    console.log(status);
    if (status == 1) {
        $.gritter.add({
            title: 'Echec d\'jout',
            text: 'Erreur de serveur. Le client n\a pas été ajouté. <br> Veillez réessayer plutard',
            image: 'assets/img/un.png'
        });
    }else {
        $.gritter.add({
            title: 'Ajout',
            text: 'Vous avez ajouté un nouveau client',
            // image: 'assets/img/un.png'
            image: 'assets/img/confirm.png'
        });
    }

    $('#addProuit .modal-dialog').removeClass('small-modal');

}

function contentModalAdd () {
    var contentModal = '<div class="form-group">\n' +
        '                                    <label for="nomAddClient">Nom</label>\n' +
        '                                    <input type="text" name="nomAddClient" id="nomAddClient" value="Du Pond" class="form-control" placeholder="nom du client" required>\n' +
        '                                    <span class="help-block"></span>\n' +
        '                                </div>\n' +
        '                                <div class="form-group">\n' +
        '                                    <label for="prenomAddClient">Prénom</label>\n' +
        '                                    <input type="text" name="prenomAddClient" id="prenomAddClient" value="Bois" class="form-control" placeholder="prénom du client">\n' +
        '                                    <span class="help-block"></span>\n' +
        '                                </div>\n' +
        '\n' +
        '                                <div class="row">\n' +
        '                                    <div class="col-md-6">\n' +
        '                                        <div class="form-group">\n' +
        '                                            <label for="telephone1AddClient">Téléphone</label>\n' +
        '                                            <input type="text" name="telephone1AddClient" id="telephone1AddClient" value="698846669" class="form-control" placeholder="numéro de téléphone" required>\n' +
        '                                            <span class="help-block"></span>\n' +
        '                                        </div>\n' +
        '                                    </div>\n' +
        '                                    <div class="col-md-6">\n' +
        '                                        <div class="form-group">\n' +
        '                                            <label for="telephone2AddClient">Téléphone 2</label>\n' +
        '                                            <input type="text" name="telephone2AddClient" id="telephone2AddClient" class="form-control">\n' +
        '                                            <span class="help-block"></span>\n' +
        '                                        </div>\n' +
        '                                    </div>\n' +
        '                                </div>\n' +
        '\n' +
        '                                <div class="row">\n' +
        '                                    <div class="col-md-10">\n' +
        '                                        <div class="form-group">\n' +
        '                                            <label for="villeAddClient">Ville</label>\n' +
        '                                            <input type="text" name="villeAddClient" id="villeAddClient" value="Yaoundé" class="form-control" placeholder="ville de residence" required>\n' +
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

    $('#addClient .modal-body form').html(contentModal);
    $('#addClient .modal-header h4').html('Nouveau Client');
    $('#addClient .modal-dialog').removeClass('small-modal');
    $('#addClient .modal-content').children('.modal-footer').remove();
}