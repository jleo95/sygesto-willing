var $table      = $('#unite-table'),
    $alertBtn   = $('#alertBtn'),
    full_screen = false,
    rowUnite = null,
    valueUnite = null,
    indexUnite = null,
    actionEven = null,
    nameUnite   = '';
$(document).ready(function (e) {

})
$().ready(function(e) {

    // ajoute une unite
   $('#formAddUnite').submit(function (e) {
       e.preventDefault();

       var valideFormAdd = true;

       if ($('#designationAddUnite').val() == '') {
           fieldForm($('#designationAddUnite'), 'Ce champs est obligatoire', 1);
           valideFormAdd = false;
       }else {
           fieldForm($('#designationAddUnite'), '', 0);
       }

       if ($('#abvAddUnite').val() == '') {
           fieldForm($('#abvAddUnite'), 'Ce champs est obligatoir', 1);
           valideFormAdd = false;
       }else {
           fieldForm($('#abvAddUnite'), '', 0);
       }

       if (valideFormAdd) {
           $.ajax({
               url: 'unite/add',
               type: 'post',
               dataType: 'json',
               data: $(this).serialize(),
               success: function (data) {
                   if (data.error == 0) {
                       $('#bodyTableUnite').html(data.bodyTableUnite);
                       $('#addUnite .modal-header h4').html('Félicitation');
                       $('#addUnite .modal-body form').html('<p>Votre unité de mesure a été enregistrée</p>');
                       $('#addUnite .modal-content').append(data.modalFooter);
                       $table.bootstrapTable('prepend', data.unite);
                   }else if (data.error == 1) {
                       $('#addUnite .errorAdd').html(data.mssge);
                   }else {
                       $('#addUnite .modal-header h4').html('Echec');
                       $('#addUnite .modal-body').html('<p>Erreur de serveur. L\'unité n\'a pas été ajoutée. <br>Veillez réessayer plutard</p>');
                       $('#addUnite .modal-content').append(data.modalFooter);
                   }
               },
               error: function (xhr) {
                   alert('Erreur de serveur veillez réessayer plutard');
               }
           });
       }
       return valideFormAdd;
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

window.operateEvents = {
    'click .edit-unite': function (e, value, row, index) {
        rowUnite = row;
        valueUnite = value;
        indexUnite = index;
        actionEven = e;
        laodForEditUnite(row.id);
    },
    'click .remove-unite': function (e, value, row, index) {
        rowUnite = row;
        valueUnite = value;
        indexUnite = index;
        actionEven = e;
        laodDataForDeleteUnite (row.id);
    }

};

function operateFormatter(value, row, index) {
    return [
        '<a rel="tooltip" title="Editer" class="table-action edit-unite text-success" data-toggle="modal" href="#editeUnite">',
        '<i class="fa fa-pencil"></i>',
        '</a>'
    ].join('');
}

function deletUnite() {
    var idUnite = rowUnite.id,
        row = rowUnite,
        value = valueUnite,
        index = indexUnite,
        e = actionEven;

    $table.bootstrapTable('remove', {
        field: 'id',
        values: [row.id]
    });
    $.ajax({
        url: 'unite/delete',
        type: 'post',
        dataType: 'json',
        data: {idUnite: idUnite},
        success: function (data) {
            if (data.succes == 0) {
                console.log(data.tbodyUnite);
                //$('#bodyTableUnite').html(data.tbodyUnite);
                $.gritter.add({
                    title: 'Suppression',
                    text: 'L\'unité de mesure "' + nameUnite + '" a été supprimé',
                    image: 'assets/img/confirm.png'
                });
            }
        },
        error: function (xhr) {
            alert('Erreur de chargement');
        }
    })
}

function laodDataForDeleteUnite(idUnite) {
    $('#hiddenIdUnite').val(idUnite);

    $.ajax({
        url: 'unite/laodDataForDeleteUnite',
        type: 'post',
        dataType: 'json',
        data: {idUnite: idUnite},
        success: function (data) {
            nameUnite = data.nameUnite;
            $('#deletUnite .modal-body').html(data.modalBody);
            $('#deletUnite .modal-header h4').html('Suppression');
        }
    })
}

function laodForEditUnite(idUnite) {
    $('#hiddenIdUnite').val(idUnite);

    $('#editeUnite .modal-content').children('.modal-footer').remove();
    $.ajax({
        url: 'unite/laodForEditUnite',
        type: 'post',
        dataType: 'json',
        data: {idUnite: idUnite},
        success: function (data) {
            nameUnite = data.nameUnite;
            $('#editeUnite .modal-body').html(data.modalBody);
            $('#editeUnite .modal-header h4').html('Editer une unité');
        },
        error: function (xhr) {
            alert('Erreur de chargement');
        }
    });
}

function confirmAdd(status) {
    console.log(status);
    if (status == 1) {
        $.gritter.add({
            title: 'Echec',
            text: 'Le seveur a rencontré un problème quelques problème. Votre unité n\' a pas été ajoutée. <br>Veillez réessayer plutard',
            image: 'assets/img/un.png'
        });
    }else {
        $.gritter.add({
            title: 'Ajout',
            text: 'Vous avez ajouté une nouvelle unité',
            // image: 'assets/img/un.png'
            image: 'assets/img/confirm.png'
        });
    }

    $('#addProuit .modal-dialog').removeClass('small-modal');

}

function confirmEdit(status) {
    console.log(status);
    if (status == 1) {
        $.gritter.add({
            title: 'Echec',
            text: 'Le seveur a rencontré un problème quelques problème. Votre unité n\' a pas été mis à jour. <br>Veillez réessayer plutard',
            image: 'assets/img/un.png'
        });
    }else {
        $.gritter.add({
            title: 'Mis à jour',
            text: 'L\'unité de mesure a été mis à jour avec succès',
            // image: 'assets/img/un.png'
            image: 'assets/img/confirm.png'
        });
    }

    $('#addProuit .modal-dialog').removeClass('small-modal');

}

function loadModalAdd() {
    contentModal = '<form action="" method="post" id="formAddUnite">\n' +
        '                                <div class="errorAdd"></div>\n' +
        '                                <div class="row">\n' +
        '                                    <div class="col-md-7">\n' +
        '                                        <div class="form-group">\n' +
        '                                            <label for="designationAddUnite">Designation</label>\n' +
        '                                            <input type="text" name="designationAddUnite" id="designationAddUnite" class="form-control" placeholder="nom de l\'unté" required>\n' +
        '                                            <span class="help-block"></span>\n' +
        '                                        </div>\n' +
        '                                    </div>\n' +
        '                                    <div class="col-md-5">\n' +
        '                                        <div class="form-group">\n' +
        '                                            <label for="abvAddUnite">Abreviation</label>\n' +
        '                                            <input type="text" name="abvAddUnite" id="abvAddUnite" class="form-control" placeholder="l\'abreviation de l\'unté" >\n' +
        '                                            <span class="help-block"></span>\n' +
        '                                        </div>\n' +
        '                                    </div>\n' +
        '                                </div>\n' +
        '                                <div class="clear">\n' +
        '                                    <div class="btn-group right">\n' +
        '                                        <button type="submit" class="btn btn-primary right" id="btnSubFormAdd">Ajouter</button>\n' +
        '                                    </div>\n' +
        '                                </div>\n' +
        '                            </form>'
    $('#addUnite .modal-body form').html(contentModal);
    $('#addUnite .modal-content').children('.modal-footer').remove();
    $('#addUnite .modal-content .modal-header h4').html('Ajouter une novelle unité');
}

function formEditUnite(formElement) {
    console.log(formElement.serialize());

    return false;
}
