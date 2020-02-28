var $table      = $('#marque-table'),
    $alertBtn   = $('#alertBtn'),
    full_screen = false,
    nameMarque   = '';
$(document).ready(function (e) {

})
$().ready(function(e) {

    // ajoute une unite
   $('#formAddMarque').submit(function (e) {
       e.preventDefault();

       var valideFormAdd = true;

       $('#addMarque .modal-content').children('.modal-footer').remove();

       if ($('#designationAddMarque').val() == '') {
           fieldForm($('#designationAddMarque'), 'Ce champs est obligatoire', 1);
           valideFormAdd = false;
       }else {
           fieldForm($('#designationAddMarque'), '', 0);
       }

       if ($('#descriptionAddMarque').val() == '') {
           fieldForm($('#descriptionAddMarque'), 'Ce champs est vide', 1);
       }else {
           fieldForm($('#descriptionAddMarque'), '', 0);
       }

       if (valideFormAdd) {
           $.ajax({
               url: 'marque/add',
               type: 'post',
               dataType: 'json',
               data: $(this).serialize(),
               success: function (data) {
                   if (data.error == 0) {
                       $('#bodyTableMarque').html(data.bodyTableMarque);
                       $('#addMarque .modal-header h4').html('Félicitation');
                       $('#addMarque .modal-body').html('<p>Une nouvelle unité de mesure a été ajoutée à ligne #1</p>');
                       $('#addMarque .modal-content').append(data.modalFooter);
                   }else if (data.error == 1) {
                       $('#addMarque .errorAdd').html(data.mssge);
                   }else {
                       $('#addMarque .modal-header h4').html('Echec');
                       $('#addMarque .modal-body').html('<p>Erreur de serveur. L\'unité n\'a pas été ajoutée. <br>Veillez réessayer plutard</p>');
                       $('#addMarque .modal-content').append(data.modalFooter);
                   }
               },
               error: function (xhr) {
                   alert('Erreur de serveur veillez réessayer plutard');
               }
           });
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

function deletMarque() {
    var idMarque = $('#hiddenIdMarque').val();
    $.ajax({
        url: 'marque/delete',
        type: 'post',
        dataType: 'json',
        data: {idMarque: idMarque},
        success: function (data) {
            if (data.succes == 0) {
                console.log(data.tbodyProduit);
                $('#bodyTableMarque').html(data.tbodyMarque);
                $.gritter.add({
                    title: 'Suppression',
                    text: 'La marque "' + nameMarque + '" a été supprimé',
                    image: 'assets/img/confirm.png'
                });
            }
        },
        error: function (xhr) {
            alert('Erreur de chargement');
        }
    })
}

function laodDataForDeleteMarque(idMarque) {
    $('#hiddenIdMarque').val(idMarque);

    $.ajax({
        url: 'marque/laodDataForDeleteMarque',
        type: 'post',
        dataType: 'json',
        data: {idMarque: idMarque},
        success: function (data) {
            nameMarque = data.nameMarque;
            $('#deletMarque .modal-body').html(data.modalBody);
            $('#deletMarque .modal-content .modal-header h4').html('Suppression');
        }
    })
}

function laodForEditeMarque(idMarque) {
    $('#hiddenIdMarque').val(idMarque);

    $.ajax({
        url: 'marque/laodForEditeMarque',
        type: 'post',
        dataType: 'json',
        data: {idMarque: idMarque},
        success: function (data) {
            nameMarque = data.nameMarque;
            console.log(data);
            $('#editeMarque .modal-body').html(data.modalBody);
            $('#editeMarque .modal-content .modal-header h4').html('Editer une marque');
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
            text: 'Le seveur a rencontré un problème quelques problème. Votre maraque n\'a pas été ajoutée. <br>Veillez réessayer plutard',
            image: 'assets/img/un.png'
        });
    }else {
        $.gritter.add({
            title: 'Ajout',
            text: 'Vous avez ajouté une nouvelle marque',
            // image: 'assets/img/un.png'
            image: 'assets/img/confirm.png'
        });
    }

    $('#addProuit .modal-dialog').removeClass('small-modal');

}

function loadModalAdd() {
    var contentModal = '<div class="errorAdd"></div>\n' +
        '                                <div class="row">\n' +
        '                                    <div class="col-md-12">\n' +
        '                                        <div class="form-group">\n' +
        '                                            <label for="designationAddMarque">Designation</label>\n' +
        '                                            <input type="text" name="designationAddMarque" id="designationAddMarque" value="Gramme" class="form-control" placeholder="nom de l\'unté">\n' +
        '                                            <span class="help-block"></span>\n' +
        '                                        </div>\n' +
        '                                    </div>\n' +
        '                                    <div class="col-md-12">\n' +
        '                                        <div class="form-group">\n' +
        '                                            <label for="descriptionAddMarque">Description</label>\n' +
        '                                            <textarea name="descriptionAddMarque" id="descriptionAddMarque" cols="30" rows="10" placeholder="une description" class="form-control" style="height: 90px"></textarea>\n' +
        '                                            <span class="help-block"></span>\n' +
        '                                        </div>\n' +
        '                                    </div>\n' +
        '                                </div>\n' +
        '                                <div class="clear">\n' +
        '                                    <div class="btn-group right">\n' +
        '                                        <button type="submit" class="btn btn-primary right" id="btnSubFormAdd">Ajouter</button>\n' +
        '                                    </div>\n' +
        '                                </div>';
    $('#addMarque .modal-body form').html(contentModal);
    $('#addMarque .modal-content').children('.modal-footer').remove();
    $('#addMarque .modal-content .modal-header h4').html('Ajouter une marque');
}


