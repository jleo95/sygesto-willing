var $table = $('#boutiqueStock-table'),
    full_screen = false;

$().ready(function(){
    $table.bootstrapTable({
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
});


function printStock(_idPrinter) {
    var frm = $("<form>", {
        action: "mouvement/imprimerStock",
        method: "post",
        target: '_blank'
    }).append($("<input>", {
        name: "idPrinter",
        type: "hidden",
        value: _idPrinter
    })).appendTo("body");

    frm.submit();
}