var $tableEntree = $('#boutiqueEntrees-table'),
    $tableSorite = $('#boutiqueSorties-table'),
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


function trieEntree1(idTrie) {
    if (idTrie != '' || idTrie != null) {
        $.ajax({
            url: 'magasin/trieEntree1',
            type: 'post',
            dataType: 'json',
            data: {
                idTrie: idTrie
            },
            success: function (data) {
                $tableEntree.bootstrapTable('removeAll');
                $tableEntree.bootstrapTable('append', data.tbodyTableEntree);
    //            $('#boutiqueEntrees-table tbody').html(data.tbodyTableEntree);
            },
            error: function (xhr) {
                alert('Erreur de chargement. Veillez réessayer plutard');
            }
        });
    }
}

function trieSortie1(idTrie) {
    if (idTrie != '') {
        $.ajax({
            url: 'magasin/trieSortie1',
            type: 'post',
            dataType: 'json',
            data: {
                idTrie: idTrie
            },
            success: function (data) {
                $tableSorite.bootstrapTable('removeAll');
                $tableSorite.bootstrapTable('append', data.tbodyTableSortie);
    //            $('#boutiqueSorties-table tbody').html(data.tbodyTableSortie);
            },
            error: function (xhr) {
                alert('Erreur de chargement. Veillez réessayer plutard');
            }
        })
    }
}



function triByProduit(idProduit, idTrie) {
    $.ajax({
        url: 'magasin/triByProduit',
        type: 'post',
        dataType: 'json',
        data: {
            idTri: idTrie,
            idProduit: idProduit
        },
        success: function (data) {
            if (data.output == 1) {
                $tableEntree.bootstrapTable('removeAll');
                $tableEntree.bootstrapTable('append', data.tbodyTableEntree);
            }else {
                $tableSorite.bootstrapTable('removeAll');
                $tableSorite.bootstrapTable('append', data.tbodyTableSortie);
            }
            
//            $('#boutiqueEntrees-table tbody').html(data.tbodyTableEntree);
        },
        error: function (xhr) {
            alert('Erreur de chargement. Veillez réessayer plutard');
        }
    })
}

function printProduit(_idPrinter) {
    var frm = $("<form>", {
        action: "magasin/imprimer",
        method: "post",
        target: '_blank'
    }).append($("<input>", {
        name: "idPrinter",
        type: "hidden",
        value: _idPrinter
    })).appendTo("body");

    frm.submit();
}