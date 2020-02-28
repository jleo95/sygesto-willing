$(document).ready(function () {
    $('#dateVente').pickadate({
        format: 'dd-mm-yyyy',
        formatSubmit: 'yyyy-mm-dd'
    });

    $('#formProcessVente1').submit(function () {
        var formValide = true;

        if ($('#clientVente').val() == '') {
            fieldForm($('#clientVente'), 'Veillez choisir un client', 1);
            formValide = false;
        }else {
            fieldForm($('#clientVente'), '', 0);
        }

        if ($('#dateVente').val() == '') {
            fieldForm($('#dateVente'), 'Veillez entrer une date pour la vente', 1);
            formValide = false;
        }else {
            fieldForm($('#dateVente'), '', 0);
        }

        return formValide;
    });
    
    /* chosir une date pour les trie des ventes */
    $('#validateCalendar').click(function () {
        if ($('#startDateTrieVente').val() != '') {
            $('.errorCalendarDate').html('');

            console.log($('#startDateTrieVente').val());

            $.ajax({
                url: 'vente/trieVente',
                type: 'post',
                dataType: 'json',
                data: {
                    idTrie: 'o',
                    startDate: $('#startDateTrieVente').val(),
                    endDate: $('#endDateTrieVente').val(),
                },
                success: function (data) {
                    console.log(data);
                    $table.bootstrapTable('removeAll');
                    $table.bootstrapTable('append', data.ventes);
                    $('#triCalendar').modal('hide');
                },
                error: function (xhr) {
                    alert('Erreur de chargement veillez réessayer plutard');
                }
            })

        }else {
            $('.errorCalendarDate').html('<div class="alert alert-danger"><strong>Attention !</strong> Veillez choisir un date</div>');
        }
    });
});

var $table = $('#mesventes-table'),
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
        showAllInfosMesventes (row.id);
    },

};

function operateFormatter(value, row, index) {
    return [
        '<a rel="tooltip" title="Voir plus" class="table-action more-infos text-primary" href="javascript:void(0)">',
        '<i class="fa fa-eye"></i>',
        '</a>'
    ].join('');
}

function loadProduitForTableAddVente() {
    $('#loadProduitAddVente .modal-dialog').removeClass('small-modal');
    $('#loadProduitAddVente .modal-header h4').text('Liste des produits');
    $.ajax({
        url: 'vente/loadProduitForTableAddVente',
        type: 'post',
        dataType: 'json',
        data: {},
        success: function (data) {
            $('#loadProduitAddVente .modal-body').html(data.modalBody);
        },
        error: function (xhr) {
            alert('Erreur de chargement. Veillez réessayer plutard');
        }
    })
}

function loadInfosProduitVente(idProduit) {
    $.ajax({
        url: 'vente/loadInfosProduitVente ',
        type: 'post',
        dataType: 'json',
        data: {idProduit: idProduit},
        success: function (data) {
            $('#loadProduitAddVente .modal-body').html(data.modalBody);
            $('#loadProduitAddVente .modal-dialog').addClass('small-modal');
            $('#loadProduitAddVente .modal-header h4').html('Produit');
        },
        error: function (xhr) {
            alert('Erreur de chargement. Veillez réessayer plutard');
        }
    })
}

function addProduitInVente(idProduit) {
    $.ajax({
        url: 'vente/addProduitInVente',
        type: 'post',
        dataType: 'json',
        data: {
            idProduit: idProduit,
            quantite: $('#quantiteProduitVente').val(),
            line: $('#tableVenteProduit tbody').children('tr').length
        },
        success: function (data) {
            $('.prixTatal span.value').text(data.prixTotaux);
            $('#tableVenteProduit tbody').append(data.lineTable);

            if ($('#tableVenteProduit tbody').children('tr').length > 0) {
                $('#btnProcessVente2').removeAttr('disabled');
                $('#btnProcessVente2').removeClass('disabled');
                $('#btnProcessVente2').removeClass('btn-default');
                $('#btnProcessVente2').addClass('btn-success');
            }
        },
        error: function (xhr) {
            alert('Erreur de chargement. Veillez réessayer plutard');
        }
    })
}

function removeProduitInVente(idProduit, line) {
    console.log(line);
    $.ajax({
        url: 'vente/removeProduitInVente',
        type: 'post',
        dataType: 'json',
        data: {
            idProduit: idProduit,
            line: line
        },
        success: function (data) {
            $('#tableVenteProduit tbody tr.line' + line).remove();
            $('.prixTatal span.value').text(data.prixtotaux);
            if ($('#tableVenteProduit tbody').children('tr').length == 0) {
                if ($('#formEndProcess').children('#btnProcessVente2').length > 0) {
                    $('#btnProcessVente2').attr('disabled', '');
                    $('#btnProcessVente2').addClass('disabled');
                    $('#btnProcessVente2').addClass('btn-default');
                    $('#btnProcessVente2').removeClass('btn-success');
                }else {
                    $('#formEndProcess').html('<button type="button" class="btn btn-default disabled" disabled name="btnProcessVente2" id="btnProcessVente2" onclick="loadNextPart()">Suivant <i class="fa fa-arrow-right"></i></button>');
                }

            }
        },
        error: function (xhr) {
            alert('Erreur de chargement. Veilez réessayer plutard');
        }
    })

}

function loadNextPart() {
    $('#formEndProcess').load('vente/loadNextPart');
}

function triVente(idTrie) {
    if (idTrie != 'o') {
        $.ajax({
            url: 'vente/trieVente',
            type: 'post',
            dataType: 'json',
            data: {idTrie: idTrie},
            success: function (data) {
                console.log(data);
                $table.bootstrapTable('removeAll');
                $table.bootstrapTable('append', data.ventes);
                pp = data.valuesprix;
                $('.values-benefice').text(data.benefices);
                $('.values-depense').text(data.depenses);
                $('.values-totaux').text(data.totaux_ventes);
                console.log(data.benefices);
            },
            error: function (xhr) {
                alert('Erreur de chargement veillez réessayer plutard');
            }
        })
    }else {
        $('#triCalendar').modal('show');
    }
}

function printVente(_idPrint) {
    if (_idPrint != 'o') {
        var frm = $("<form>", {
            action: "vente/impression_vente",
            target: "_blank",
            method: "post"
        }).append($("<input>", {
            name: "idPrint",
            type: "hidden",
            value: _idPrint
        })).appendTo("body");
        frm.submit();
    }else {
        $('#triCalendar').modal('show');
    }
}

function imprimerRecu(_idcaisse){

    _idcaisse = 5

    var frm = $("<form>", {
        action: "vente/imprimer",
        target: "_blank",
        method: "post"
    }).append($("<input>", {
        name: "code",
        type: "hidden",
        value: "0002"
    })).append($("<input>", {
        name: "idcaisse",
        type: "hidden",
        value: _idcaisse
    })).appendTo("body");
    frm.submit();
}

function showAllInfosMesventes(idCommande) {
    console.log(idCommande);
    $.ajax({
        url: 'vente/showAllInfosMesventes',
        type: 'post',
        dataType: 'json',
        data: {idCommande: idCommande},
        success: function (data) {
            $('#showAllInfosMesventes .modal-body').html(data.bodyModal);
            $('#showAllInfosMesventes .modal-header h4').text(data.headerModal);
            $('#showAllInfosMesventes').modal('show');
        },
        error: function (xhr) {
            alert('Erreur de chargement. Veillez réessayer plutard');
        }
    })
}