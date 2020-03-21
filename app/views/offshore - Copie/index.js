$(document).ready(function (e) {
    
alert("test ok")
    $('#formAddProduit').submit(function (e) {
        e.preventDefault();

        var formValide = true;

        if ($('#designAddProduit').val() == '') {
            fieldForm($('#designAddProduit'), 'Veillez saissir le nom du produit', 1);
            formValide = false;
        }else {
            fieldForm($('#designAddProduit'), '', 2);
        }

        /* verification du champs prix d'achat **/
        if ($('#prixachatAddProduit').val() == '') {
            fieldForm($('#prixachatAddProduit'), 'Veillez saissir le prix unitaire d\'achat', 1);
            formValide = false;
        }else {
            fieldForm($('#prixachatAddProduit'), '', 2);
        }

        // verification du prix unitaire de vente
        if ($('#prixUnitVenteAddProduit').val() == '') {
            fieldForm($('#prixUnitVenteAddProduit'), 'Veillez saissir le prix unitaire de vente', 1);
            formValide = false;
        }else {
            fieldForm($('#prixUnitVenteAddProduit'), '', 2);
        }

        // verification du prix global de vente
        if ($('#prixGlobVenteAddProduit').val() == '') {
            fieldForm($('#prixGlobVenteAddProduit'), 'Veillez saissir le prix global de vente', 1);
            formValide = false;
        }else {
            fieldForm($('#prixGlobVenteAddProduit'), '', 2);
        }

        // verication du champs date de peremption
        if ($('#peremptionAddProduit').val() == '') {
            fieldForm($('#peremptionAddProduit'), 'Veillez entrer la date de peremption', 1);
            formValide = false;
        }else {
            fieldForm($('#peremptionAddProduit'), '', 2);
        }

        // verication du champs unite de mesure
        if ($('#uniteAddProduit').val() == '') {
            fieldForm($('#uniteAddProduit'), 'Veillez chosir une unité de mesure', 1);
            formValide = false;
        }else {
            fieldForm($('#uniteAddProduit'), '', 2);
        }


        if ($('#familleAddProduit').val() == '') {
            fieldForm($('#familleAddProduit'), '', 2);
        }

        if ($('#seuilAddProduit').val() != '') {
            fieldForm($('#seuilAddProduit'), '', 2);
        }

        valueProduit = false;

        if (formValide) {
            $.ajax({
                url: 'produit/add',
                type: 'post',
                dataType: 'json',
                data: $(this).serialize(),
                success: function (data) {
                    console.log(data);
                    if (data.error == 0) {
                        // $(window).resize(function () {
                        //     $table.bootstrapTable('resetView');
                        // });
                        // $('#bodyTableProduit').html(data.bodyTableProduit);
                        $('#addProuit .modal-header h4').html('Félicitation');
                        $('#addProuit .modal-body').html('<p>Un nouveau produit a été ajouté à ligne #1</p>');
                        $('#addProuit .modal-content').append(data.modalFooter);
                        $('#addProuit .modal-dialog').addClass('small-modal');
                    }else if (data.error == 1) {
                        $('#addProuit .errorAdd').html(data.mssge);
                    }else {
                        $('#addProuit .modal-header h4').html('Echec');
                        $('#addProuit .modal-body').html('<p>Erreur de serveur. Le produit n\'a pas été ajouté. <br>Veillez réessayer plutard</p>');
                        $('#addProuit .modal-content').append(data.modalFooter);
                        $('#addProuit .modal-dialog').addClass('small-modal');
                    }

                },
                error: function (xhr, error) {
                    alert('Une erreur s\'est survnue lors du traitement');
                }
            });
            // $(window).resize(function () {
            //     $table.bootstrapTable('resetView');
            // });
        }

        return formValide;
    });


    // edite produit

});

var $table = $('#offshore-table'),
    $alertBtn = $('#alertBtn'),
    rowProduit = null,
    valueProduit = null,
    indexProduit = null,
    actionEven = null,
    full_screen = false;

$().ready(function() {
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
    'click .more-infos': function (e, value, row, index) {
        showAllInfosProduit (row.id);
    },
    'click .edit-produit': function (e, value, row, index) {
        rowProduit = row;
        valueProduit = value;
        indexProduit = index;
        actionEven = e;
        console.log(row);
        row.name = 'jleo';
        $table.bootstrapTable('resetView');
        // $table.bootstrapTable('prepend', {
        //     id: 77,
        //     name: 'jleo',
        //     family: 'det',
        //     country: 'jleo',
        //     prixGlobalVente: '<span style="font-style: italic;">3 500,00</span>',
        //     stockboutique: '<span style="font-style: italic; font-size: 12px;">rupture</span>'
        // });
        laodForEditeProduit(row.id);
    },
    'click .remove-produit': function (e, value, row, index) {
        rowProduit = row;
        valueProduit = value;
        indexProduit = index;
        actionEven = e;
        $.ajax({
            url: 'produit/laodDataForDeleteProduit',
            type: 'post',
            dataType: 'json',
            data: {idProduit: row.id},
            success: function (data) {
                nameProduit = data.nameProduit;
                $('#deletProuit .modal-body').html(data.modalBody);
                $('#deletProuit .modal-content .modal-header h4').html('Suppression');
                $('#deletProuit').modal('show');
            },
            error: function (xhr) {
                alert('Erreur de chargement');
            }
        });
    }

};
function operateFormatter(value, row, index) {
    return [
        '<a rel="tooltip" title="Voir plus" class="table-action more-infos text-primary" href="javascript:void(0)">',
        '<i class="fa fa-eye"></i>',
        '</a>',
        '<a rel="tooltip" title="Editer" class="table-action edit-produit text-success" href="javascript:void(0)">',
        '<i class="fa fa-edit"></i>',
        '</a>'
    ].join('');
}

var nameProduit = null;
function laodIdProduit(idProduit) {
    $('#hiddenIdProduit').val(idProduit);
    console.log(idProduit);
}

function laodDataForDeleteProduit(idProduit) {
    console.log(rowProduit);
    $('#hiddenIdProduit').val(idProduit);

    $.ajax({
        url: 'produit/laodDataForDeleteProduit',
        type: 'post',
        dataType: 'json',
        data: {idProduit: idProduit},
        success: function (data) {
            nameProduit = data.nameProduit;
            $('#deletProuit .modal-body').html(data.modalBody);
            $('#deletProuit .modal-content .modal-header h4').html('Suppression');
        },
        error: function (xhr) {
            alert('Erreur de chargement');
        }
    });
    console.log(idProduit);
}

function laodForEditeProduit(idProduit) {
    $('#hiddenIdProduit').val(idProduit);

    $.ajax({
        url: 'produit/laodForEditeProduit',
        type: 'post',
        dataType: 'json',
        data: {idProduit: idProduit},
        success: function (data) {
            nameProduit = data.nameProduit;
            $('#editeProduit .modal-body').html(data.modalBody);
            $('#editeProduit .modal-content .modal-header h4').html('Editer un produit');
            $('#editeProduit').modal('show');
        },
        error: function (xhr) {
            alert('Erreur de chargement');
        }
    })
    console.log(idProduit);
}

function deleteProduit() {

    var idProduit = rowProduit.id,
        row = rowProduit,
        value = valueProduit,
        index = indexProduit,
        e = actionEven;

    $table.bootstrapTable('remove', {
        field: 'id',
        values: [row.id]
    });

    $.ajax({
        url: 'produit/delete',
        type: 'post',
        dataType: 'json',
        data: {idProduit: idProduit},
        success: function (data) {
            if (data.succes == 0) {
                console.log(data.tbodyProduit);
                $table.bootstrapTable('load', data.tbodyProduit);
                // $('#bodyTableProduit').html(data.tbodyProduit);
                $.gritter.add({
                    title: 'Suppression',
                    text: 'Le produit ' + nameProduit + ' a été supprimé',
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
    });
}

function confirmAdd(status) {
    console.log(status);
    if (status == 1) {
        $.gritter.add({
            title: 'Echec ajout',
            text: 'Erreur de serveur. Produit non ajouté. <br> Veillez réessayer plutard',
            image: 'assets/img/un.png'
        });
    }else {
        $.gritter.add({
            title: 'Ajout',
            text: 'Vous avez ajouté un mouveau produit',
            // image: 'assets/img/un.png'
            image: 'assets/img/confirm.png'
        });
    }

    $('#addProuit .modal-dialog').removeClass('small-modal');

}

function trieProduit(idTrie) {
    $.ajax({
        url: 'produit/trieProduit',
        type: 'post',
        dataType: 'json',
        data: {idTrie: idTrie},
        success: function (data) {
            $table.bootstrapTable('removeAll');
            $table.bootstrapTable('append', data.bodyTableProduit);
//            $('#bodyTableProduit').html(data.bodyTableProduit);
        }
    })
}

function showAllInfosProduit(idProduit) {
    $.ajax({
        url: 'produit/showAllInfosProduit',
        type: 'post',
        dataType: 'json',
        data: {idProduit: idProduit},
        success: function (data) {
            $('#showAllInfosProduit .modal-body').html(data.infosProduit);
            $('#showAllInfosProduit .modal-header h4').html('Infos Produit ' + data.header_stock);
            $('#showAllInfosProduit').modal('show');
        },
        error: function (xhr) {
            alert('Erreur de chargement. Veillez réessqyer plutard');
        }
    })
}

function printProduit(_idPrinter) {
    var frm = $("<form>", {
        action: "produit/imprimer",
        method: "post",
        target: '_blank'
    }).append($("<input>", {
        name: "idPrinter",
        type: "hidden",
        value: _idPrinter
    })).appendTo("body");

    frm.submit();
}

function contentModalAdd() {
    $('#addProuit .modal-content .modal-header h4').html('Nouveau produit');
    $('#addProuit .modal-body').load('produit/loadAdd');
    $('#addProuit').modal('show');
}
