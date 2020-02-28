var $tableEntree = $('#statVentes-table'),
    full_screen = false;

$().ready(function(){
    $tableEntree.bootstrapTable({
        toolbar: ".toolbar",

        search: true,
        pagination: true,
        striped: true,
        sortable: true,
        pageSize: 8,
        pageList: [8,10,25,50,100],

        formatShowingRows: function(pageFrom, pageTo, totalRows){
            //do nothing here, we don't want to show the text "showing x of y from..."
        },
        formatRecordsPerPage: function(pageNumber){
            return pageNumber + " rows visible";
        },
        icons: {
            refresh: 'fa fa-refresh',
            toggle: 'fa fa-th-list',
            columns: 'fa fa-columns',
            detailOpen: 'fa fa-plus-circle',
            detailClose: 'fa fa-minus-circle'
        }
    });

    $tableSorite.bootstrapTable({
        search: true,
        pagination: true,
        striped: true,
        sortable: true,
        pageSize: 8,
        pageList: [8,10,25,50,100],

        formatShowingRows: function(pageFrom, pageTo, totalRows){
            //do nothing here, we don't want to show the text "showing x of y from..."
        },
        formatRecordsPerPage: function(pageNumber){
            return pageNumber + " rows visible";
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
    }

};
function operateFormatter(value, row, index) {
    return [
        '<a rel="tooltip" title="Voir plus" class="table-action more-infos text-primary" href="javascript:void(0)">',
        '<i class="fa fa-eye"></i>',
        '</a>'
    ].join('');
}