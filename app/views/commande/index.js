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
        '<a rel="tooltip" title="Voir plus" class="table-action more-infos text-primary" href="commande/voir/{{row.id}}}">',
        '<i class="fa fa-eye"></i>',
        '</a>',
        // '<a rel="tooltip" title="Editer commande" class="table-action edit-offre text-success" href="javascript:void(0)">',
        // '<i class="fa fa-pencil"></i>',
        // '</a>',
        // '<a rel="tooltip" title="Annuler commande" class="table-action remove-offre text-danger" href="javascript:void(0)">',
        // '<i class="fa fa-trash"></i>',
        // '</a>'
    ].join('');
}



