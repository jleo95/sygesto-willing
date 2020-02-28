var $table      = $('#famille-table'),
    $alertBtn   = $('#alertBtn'),
    rowFamille = null,
    valueFamille = null,
    indexFamille = null,
    actionEven = null,
    full_screen = false,
    nameFamille   = '';
$(document).ready(function (e) {

})
$().ready(function(e) {

    // ajoute une famille
   $('#formAddFamille').submit(function (e) {
       e.preventDefault();

       var valideFormAdd = true;

       if ($('#designationAddFamille').val() == '') {
           fieldForm($('#designationAddFamille'), 'Ce champs est obligatoire', 1);
           valideFormAdd = false;
       }else {
           fieldForm($('#designationAddFamille'), '', 0);
       }

       if ($('#abvAddFamille').val() == '') {
           fieldForm($('#abvAddFamille'), 'Ce champs est obligatoir', 1);
           valideFormAdd = false;
       }else {
           fieldForm($('#abvAddFamille'), '', 0);
       }

       if (valideFormAdd) {
           $.ajax({
               url: 'famille/add',
               type: 'post',
               dataType: 'json',
               data: $(this).serialize(),
               success: function (data) {
                   if (data.error == 0) {
                       $table.bootstrapTable('prepend', data.famille);
                       // $('#bodyTableFamille').html(data.bodyTableFamille);
                       $('#addFamille .modal-header h4').html('Félicitation');
                       $('#addFamille .modal-body form').html('<p>La famille a été ajoutée avec succès</p>');
                       $('#addFamille .modal-content').append(data.modalFooter);
                   }else if (data.error == 1) {
                       $('#addFamille .errorAdd').html(data.mssge);
                   }else {
                       $('#addFamille .modal-header h4').html('Echec');
                       $('#addFamille .modal-body').html('<p>Erreur de serveur. L\'unité n\'a pas été ajoutée. <br>Veillez réessayer plutard</p>');
                       $('#addFamille .modal-content').append(data.modalFooter);
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
    'click .edit-famille': function (e, value, row, index) {
        // laodForEditeProduit(row.id);
        rowFamille = row;
        valueFamille = value;
        indexFamille = index;
        actionEven = e;
        $.ajax({
            url: 'famille/loadForEditFamille',
            type: 'post',
            dataType: 'json',
            data: {idFamille: row.id},
            success: function (data) {
                $('#editeFamille .modal-body').html(data.modalBody)
            },
            error: function (xhr) {
                alert('Erreur de chargement');
            }

        })
    },
    'click .remove-famille': function (e, value, row, index) {
        rowFamille = row;
        valueFamille = value;
        indexFamille = index;
        actionEven = e;

        loadDataForDeleteFamille(row.id);
    }

};

function operateFormatter(value, row, index) {

    return [
        '<a rel="tooltip" title="Editer" class="table-action edit-famille text-success" data-toggle="modal" href="#editeFamille">',
        '<i class="fa fa-pencil"></i>',
        '</a>'
    ].join('');
}

function deletFamille() {
    var idFamille = rowFamille.id,
        row = rowFamille,
        value = valueFamille,
        index = indexFamille,
        e = actionEven;

    $table.bootstrapTable('remove', {
        field: 'id',
        values: [row.id]
    });

    $.ajax({
        url: 'famille/delete',
        type: 'post',
        dataType: 'json',
        data: {idFamille: idFamille},
        success: function (data) {
            if (data.succes == 0) {
                console.log(data.tbodyProduit);
                // $('#bodyTableFamille').html(data.tbodyFamille);
                $.gritter.add({
                    title: 'Suppression',
                    text: 'L\'unité de mesure "' + nameFamille + '" a été supprimé',
                    image: 'assets/img/confirm.png'
                });
            }
        },
        error: function (xhr) {
            alert('Erreur de chargement');
        }
    })
}

function loadDataForDeleteFamille(idFamille) {
    $('#hiddenIdFamille').val(idFamille);

    $.ajax({
        url: 'famille/loadDataForDeleteFamille',
        type: 'post',
        dataType: 'json',
        data: {idFamille: idFamille},
        success: function (data) {
            nameFamille = data.nameFamille;
            $('#deletFamille .modal-body').html(data.modalBody);
            $('#deletFamille .modal-header h4').html('Suppression');
        }
    })
}

function deleteFamille() {

    var idFamille = rowFamille.id,
        row = rowFamille,
        value = valueFamille,
        index = indexFamille,
        e = actionEven;
    // $table.bootstrapTable('remove', {
    //     field: 'id',
    //     values: [row.id]
    // });
    $.ajax({
        url: 'famille/delete',
        type: 'post',
        dataType: 'json',
        data: {idFamille: idFamille},
        success: function (data) {
            if (data.succes == 0) {
                $table.bootstrapTable('load', data.tbodyFamille);
                // $('#bodyTableProduit').html(data.tbodyProduit);
                $.gritter.add({
                    title: 'Suppression',
                    text: 'La famille des produits "' + nameFamille+ '" a été supprimé',
                    image: 'assets/img/confirm.png'
                });

                $(window).resize(function () {
                    $table.bootstrapTable('resetView');
                });
            }
        },
        error: function (xhr) {
            alert('Erreur de chargement');
        }
    })
    console.log(idProduit);
}

function laodForEditFamille(idFamille) {
    $('#hiddenIdFamille').val(idFamille);

    $('#editeFamille .modal-content').children('.modal-footer').remove();
    $.ajax({
        url: 'famille/laodForEditFamille',
        type: 'post',
        dataType: 'json',
        data: {idFamille: idFamille},
        success: function (data) {
            nameFamille = data.nameFamille;
            $('#editeFamille .modal-body').html(data.modalBody);
            $('#editeFamille .modal-header h4').html('Editer une unité');
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
            text: 'Le seveur a rencontré un problème quelques problème. Votre famille n\'a pas été ajoutée. <br>Veillez réessayer plutard',
            image: 'assets/img/un.png'
        });
    }else {
        $.gritter.add({
            title: 'Ajout',
            text: 'Vous avez ajouté une nouvelle famille',
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
            text: 'La famille a été mis à jour avec succès',
            // image: 'assets/img/un.png'
            image: 'assets/img/confirm.png'
        });
    }

    $('#addProuit .modal-dialog').removeClass('small-modal');

}

function loadModalAdd() {
    contentModal = '<div class="errorAdd"></div>\n' +
        '                                <div class="row">\n' +
        '                                    <div class="col-md-8">\n' +
        '                                        <div class="form-group">\n' +
        '                                            <label for="designationAddFamille">Designation</label>\n' +
        '                                            <input type="text" name="designationAddFamille" id="designationAddFamille"  class="form-control" placeholder="Nom de la famille">\n' +
        '                                            <span class="help-block"></span>\n' +
        '                                        </div>\n' +
        '                                    </div>\n' +
        '                                </div>\n' +
        '                                <div class="clear">\n' +
        '                                    <div class="btn-group right">\n' +
        '                                        <button type="submit" class="btn btn-primary right" id="btnSubFormAdd">Ajouter</button>\n' +
        '                                    </div>\n' +
        '                                </div>';

    $('#addFamille .modal-body form').html(contentModal);
    $('#addFamille .modal-content').children('.modal-footer').remove();
    $('#addFamille .modal-content .modal-header h4').html('Ajouter une nouvelle famille');
}

function formEditFamille(formElement) {
    console.log(formElement.serialize());

    return false;
}
