var $tableEntree = $('#boutiqueEntrees-table'),
    $tableSorite = $('#boutiqueSorties-table'),
    full_screen = false;

$().ready(function(){
    $tableEntree.bootstrapTable({
        toolbar: ".toolbarEntree",

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
        toolbar: ".toolbarSortie",
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

function loadProduitForEntree() {
    $('#loadProduitForEntree .modal-header h4').text("Produit disponible");
    $('#loadProduitForEntree .modal-dialog').addClass("modal-lg");
    $('#loadProduitForEntree .modal-dialog').removeClass("modal-sm");
    $.ajax({
        url: 'mouvement/loadProduitForEntree',
        type: 'post',
        dataType: 'json',
        data: {},
        success: function (data) {
            $('#loadProduitForEntree .modal-body').html(data.bodyModal);
            $('#loadProduitForEntree .modal-header h4').text("Produit disponible");
        },
        error: function (xhr) {
            alert('Erreur de chargement. Veilez réessayer plutard');
        }
    })
}

function loadProduitForEntree2() {
    $('#loadProduitForEntree .modal-header h4').text("Produit disponible");
    $('#loadProduitForEntree .modal-dialog').addClass("modal-lg");
    $('#loadProduitForEntree .modal-dialog').removeClass("modal-sm");
    $.ajax({
        url: 'mouvement/loadProduitForEntree',
        type: 'post',
        dataType: 'json',
        data: {},
        success: function (data) {
            $('#loadProduitForEntree .modal-body').html(data.bodyModal);
            $('#loadProduitForEntree .modal-header h4').text("Produit disponible");
        },
        error: function (xhr) {
            alert('Erreur de chargement. Veilez réessayer plutard');
        }
    })
}

function loadInfosProduitEntree(idOffre) {
    $.ajax({
        url: 'mouvement/loadInfosProduitEntree',
        type: 'post',
        dataType: 'json',
        data: {idOffre: idOffre},
        success: function (data) {
            console.log(data);
            $('#loadProduitForEntree .modal-body').html(data.bodyModal);
            $('#loadProduitForEntree .modal-header h4').text("Commande");
            // $('#loadProduitForEntree .modal-dialog').removeClass("modal-lg");
            // $('#loadProduitForEntree .modal-dialog').addClass("modal-sm");
        }
    })
}

function removeProduitFromCommande(idOffre) {
    $.ajax
}

function addInEntree(idProduit) {
    $.ajax({
        url: 'mouvement/addInEntree',
        type: 'post',
        dataType: 'json',
        data: {
            idProduit: idProduit,
            quantite: $('#quantiteEntree').val(),
            line: $('#bodyTableProduitEntree').children('tr').length
        },
        success: function (data) {
            $('#bodyTableProduitEntree').append(data.bodyTableProduitEntree);
            if ($('#bodyTableProduitEntree').children('tr').length == 1) {
                $('#btnProcessEntree').removeAttr('disabled');
                $('#btnProcessEntree').removeClass('disabled');
                $('#btnProcessEntree').removeClass('btn-default');
                $('#btnProcessEntree').addClass('btn-success');
            }
        },
        error: function (xhr) {
            alert('Erreur de chargement. Veilez réessayer plutard');
        }
    })
}

function removeProduitForEntree(idProduit, line) {
    $.ajax({
        url: 'mouvement/removeProduitForEntree',
        type: 'post',
        dataType: 'json',
        data: {
            idProduit: idProduit,
            line: line
        },
        success: function (data) {
            $('#bodyTableProduitEntree').children('tr.' + line).remove();

            if ($('#bodyTableProduitEntree').children('tr').length == 0) {
                $('#btnProcessEntree').attr('disabled');
                $('#btnProcessEntree').addClass('disabled');
                $('#btnProcessEntree').addClass('btn-default');
                $('#btnProcessEntree').removeClass('btn-success');
            }else {
                $('#btnProcessEntree').removeAttr('disabled');
                $('#btnProcessEntree').removeClass('disabled');
                $('#btnProcessEntree').removeClass('btn-default');
                $('#btnProcessEntree').addClass('btn-success');
            }
        }
    })
}

function trieEntree1(idTrie) {
    if (idTrie != '') {
         $.ajax({
            url: 'mouvement/trieEntree1',
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
        })
    }
}

function trieOther(idTrie) {
    var valueTrie = null;
    
    if (idTrie == 1) {
        valueTrie = $('#produitsListe').val();
    }else {
        valueTrie = $('#familleListe').val();
    }
    
//    console.log(valueTrie);
//    console.log($('#hiddenIdTri').val());
    
    if (valueTrie == '') {
        $('.modal-error-entree').fadeIn();
    }else {
        
        if ($('#hiddenIdTri').val() == 1) {
            trieEntreeOther2(idTrie, valueTrie);
            $('#triEntreeOtherProduit').modal('hide');
        } else {
            
        }
        
    }
}
function trieEntreeOther(idTrie) {
//    console.log(idTrie);
    $('#hiddenIdTri').val(1);
    if (idTrie == 1) {
//        console.log('produit');
        $('#triEntreeOtherProduit').modal('show');
    }else {
//        console.log('famille');
        $('#triEntreeOtherFamille').modal('show');
    }
//    trieEntreeOther2(idTrie, 2);
}

function trieEntreeOther2(idTrie, secondeId) {
    $.ajax({
        url: 'mouvement/trieEntreeOther',
        type: 'post',
        dataType: 'json',
        data: {
            idTrie: idTrie,
            secondeId: secondeId,
        },
        success: function (data) {
            $tableEntree.bootstrapTable('removeAll');
            $tableEntree.bootstrapTable('append', data.tbodyTableEntree);
//            $('#boutiqueEntrees-table tbody').html(data.tbodyTableEntree);
        },
        error: function (xhr) {
            alert('Erreur de chargement. Veillez réessayer plutard');
        }
    })
}

function triByProduit(idProduit, idTrie) {
    $.ajax({
        url: 'mouvement/triByProduit',
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

function trieSortie1(idTrie) {
    if (idTrie != '') {
         $.ajax({
            url: 'mouvement/trieSortie1',
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

function printProduit(_idPrinter) {
    var frm = $("<form>", {
        action: "mouvement/imprimer",
        method: "post",
        target: '_blank'
    }).append($("<input>", {
        name: "idPrinter",
        type: "hidden",
        value: _idPrinter
    })).appendTo("body");

    frm.submit();
}