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
        //code
    }
};
function operateFormatter(value, row, index) {
    uri = row.id;
    id = uri.split('#');
    return [
        '<a rel="tooltip" title="Voir plus" class="table-action more-infos text-primary" href="commande/voir/' + id[1] + '">',
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



