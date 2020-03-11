var $table = $('#commande-table'),
    $alertBtn = $('#alertBtn'),
    full_screen = false,
    nameClient = '';

$().ready(function() {

    $table.bootstrapTable({
        toolbar: ".toolbar",
        showRefresh: true,
        search: true,
        showColumns: true,
        pagination: true,
        striped: true,
        pageSize: 25,
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
        laodForShowCommande (row.num);
    },
    'click .edit-offre': function (e, value, row, index) {
        rowProduit = row;
        valueProduit = value;
        indexProduit = index;
        actionEven = e;
        $.ajax({
            url: 'achat/loadForEdit',
            type: 'post',
            dataType: 'json',
            data: {idOffre: row.num},
            success: function (data) {
                $('#editOffre .modal-body').html(data.bodyModal);
                $('#editOffre').modal('show');
            },
            error: function (xhr) {
                alert('Une erreur s\'est produite. Veillez ressayer plutard');
            }
        })
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
        '<a rel="tooltip" title="Voir plus" class="table-action edit-offre text-success" href="javascript:void(0)">',
        '<i class="fa fa-pencil"></i>',
        '</a>',
        '<a rel="tooltip" title="Editer" class="table-action remove-offre text-danger" href="javascript:void(0)">',
        '<i class="fa fa-trash"></i>',
        '</a>'
    ].join('');
}


function laodForShowCommande(idOffre) {
    $.ajax({
        url: 'achat/laodForShowCommande',
        type: 'post',
        dataType: 'json',
        data: {idOffre: idOffre},
        success: function (data) {
            $('#showCommande .modal-body').html(data.bodyModal);
            $('#showCommande .modal-header h4').html(data.headerModal);
            $('#showCommande').modal('show');
        },
        error: function (xhr) {
            alert('Erreur de chargement. Veillez réessayer plutard');
        }
    })
}

function loadProduitOffre() {
    $('#addProduitOffre .modal-dialog').addClass('modal-lg');
    $('#addProduitOffre .modal-dialog').removeClass('small-modal');
    $.ajax({
        url: 'achat/loadProduitOffre',
        type: 'post',
        dataType: 'json',
        data: {},
        success: function (data) {
            $('#addProduitOffre .modal-body').html(data.bodyModal);
        },
        error: function (xhr) {
            alert('Erreur de chargement. Veillez réessayer plutard ici');
        }
    })
}

function loadProduitForAddOffre(idProduit) {
    console.log(idProduit);

    $.ajax({
        url: 'achat/loadProduitForAddOffre',
        type: 'post',
        dataType: 'json',
        data: {idProduit: idProduit},
        success: function (data) {
            $('#addProduitOffre .modal-body').html(data.bodyModal);
            $('#addProduitOffre .modal-header h4').html(data.headerModal);
            $('#addProduitOffre .modal-dialog').removeClass('modal-lg');
            $('#addProduitOffre .modal-dialog').addClass('small-modal');
        },
        error: function (xhr) {
            alert('Erreur de chargement. Veillez réessayer plutard');
        }
    })
}

function addProduitInCommand(idProduit) {
    $.ajax({
        url: 'achat/addProduitInCommand',
        type: 'post',
        dataType: 'json',
        data: {
            idProduit: idProduit,
            idOffre: $('#hiddenIdOffre').val(),
            quantite: $('#qtiteProduitOffre').val()
        },
        success: function (data) {
            $('#tbodyProduitOffre').append(data.lineTable);
            $('#btnNextProcessAddOffre').removeAttr('disabled');
            $('#btnNextProcessAddOffre').removeClass('disabled');
            $('#btnNextProcessAddOffre').removeClass('btn-default');
            $('#btnNextProcessAddOffre').addClass('btn-success');
        },
        error: function (xhr) {
            alert('Erreur de chargement. Veillez réessyer plutard');
        }
    })
}

function removeProduitOffre(idProduit) {
    $.ajax({
        url: 'achat/removeProduitOffre',
        type: 'post',
        dataType: 'json',
        data: {idProduit: idProduit},
        success: function (data) {
            $('#tbodyProduitOffre').children('#line' + idProduit).remove();

            if ($('#tbodyProduitOffre').children('tr').length == 0) {
                $('#btnNextProcessAddOffre').attr('disabled', '');
                $('#btnNextProcessAddOffre').addClass('disabled');
                $('#btnNextProcessAddOffre').removeClass('btn-success');
                $('#btnNextProcessAddOffre').addClass('btn-default');
            }

        },
        error: function (xhr) {
            alert('Erreur de chargement. Veillez réessayer plutard');
        }
    })

}

function trieAchat(idTrie) {
    console.log(idTrie);
    $.ajax({
        url: 'achat/trieAchat',
        type: 'post',
        dataType: 'json',
        data: {idTrie: idTrie},
        success: function (data) {
            $table.bootstrapTable('removeAll');
            $table.bootstrapTable('append', data.bodyTableOffre);
//            $('#bodyTableOffre').html(data.bodyTableOffre);
        }
    })
}

function ouvrirFiche(_ideleve) {
    var frm = $("<form>", {
        action: "../eleve",
        method: "post"
    }).append($("<input>", {
        name: "ideleve",
        type: "hidden",
        value: _ideleve
    })).appendTo("body");

    frm.submit();
}