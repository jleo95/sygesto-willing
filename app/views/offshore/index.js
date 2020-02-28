$(document).ready(function (e) {
    $('#peremptionAddOffshore').pickadate({
        formatSubmit: 'yyyy-mm-dd'
    });

    $('#formAddOffshore').submit(function (e) {
        e.preventDefault();

        var formValide = true;

        if ($('#designAddOffshore').val() == '') {
            fieldForm($('#designAddOffshore'), 'Veillez saissir le nom du offshore', 1);
            formValide = false;
        }else {
            fieldForm($('#designAddOffshore'), '', 2);
        }

       

        // verification du prix unitaire de vente
        if ($('#prixUnitVenteAddOffshore').val() == '') {
            fieldForm($('#prixUnitVenteAddOffshore'), 'Veillez saissir le prix unitaire de vente', 1);
            formValide = false;
        }else {
            fieldForm($('#prixUnitVenteAddOffshore'), '', 2);
        }

        // verification du prix global de vente
        if ($('#prixGlobVenteAddOffshore').val() == '') {
            fieldForm($('#prixGlobVenteAddOffshore'), 'Veillez saissir le prix global de vente', 1);
            formValide = false;
        }else {
            fieldForm($('#prixGlobVenteAddOffshore'), '', 2);
        }

        // verication du champs date de peremption
        if ($('#peremptionAddOffshore').val() == '') {
            fieldForm($('#peremptionAddOffshore'), 'Veillez entrer la date de peremption', 1);
            formValide = false;
        }else {
            fieldForm($('#peremptionAddOffshore'), '', 2);
        }

        // verication du champs unite de mesure
        if ($('#uniteAddOffshore').val() == '') {
            fieldForm($('#uniteAddOffshore'), 'Veillez chosir une unité de mesure', 1);
            formValide = false;
        }else {
            fieldForm($('#uniteAddOffshore'), '', 2);
        }


        if ($('#familleAddOffshore').val() == '') {
            fieldForm($('#familleAddOffshore'), '', 2);
        }

        if ($('#seuilAddOffshore').val() != '') {
            fieldForm($('#seuilAddOffshore'), '', 2);
        }

        valueOffshore = false;

        if (formValide) {
            $.ajax({
                url: 'offshore/add',
                type: 'post',
                dataType: 'json',
                data: $(this).serialize(),
                success: function (data) {
                    console.log(data);
                    if (data.error == 0) {
                        // $(window).resize(function () {
                        //     $table.bootstrapTable('resetView');
                        // });
                        // $('#bodyTableOffshore').html(data.bodyTableOffshore);
                        $('#addProuit .modal-header h4').html('Félicitation');
                        $('#addProuit .modal-body').html('<p>Un nouveau offshore a été ajouté à ligne #1</p>');
                        $('#addProuit .modal-content').append(data.modalFooter);
                        $('#addProuit .modal-dialog').addClass('small-modal');
                    }else if (data.error == 1) {
                        $('#addProuit .errorAdd').html(data.mssge);
                    }else {
                        $('#addProuit .modal-header h4').html('Echec');
                        $('#addProuit .modal-body').html('<p>Erreur de serveur. Le offshore n\'a pas été ajouté. <br>Veillez réessayer plutard</p>');
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


    // edite offshore

});

var $table = $('#offshore-table'),
    $alertBtn = $('#alertBtn'),
    rowOffshore = null,
    valueOffshore = null,
    indexOffshore = null,
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
        showAllInfosOffshore (row.id);
    },
    'click .edit-offshore': function (e, value, row, index) {
        rowOffshore = row;
        valueOffshore = value;
        indexOffshore = index;
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
        laodForEditeOffshore(row.id);
    },
    'click .remove-offshore': function (e, value, row, index) {
        rowOffshore = row;
        valueOffshore = value;
        indexOffshore = index;
        actionEven = e;
        $.ajax({
            url: 'offshore/laodDataForDeleteOffshore',
            type: 'post',
            dataType: 'json',
            data: {idOffshore: row.id},
            success: function (data) {
                nameOffshore = data.nameOffshore;
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
        '<a rel="tooltip" title="Editer" class="table-action edit-offshore text-success" href="javascript:void(0)">',
        '<i class="fa fa-edit"></i>',
        '</a>'
    ].join('');
}

var nameOffshore = null;
function laodIdOffshore(idOffshore) {
    $('#hiddenIdOffshore').val(idOffshore);
    console.log(idOffshore);
}

function laodDataForDeleteOffshore(idOffshore) {
    console.log(rowOffshore);
    $('#hiddenIdOffshore').val(idOffshore);

    $.ajax({
        url: 'offshore/laodDataForDeleteOffshore',
        type: 'post',
        dataType: 'json',
        data: {idOffshore: idOffshore},
        success: function (data) {
            nameOffshore = data.nameOffshore;
            $('#deletProuit .modal-body').html(data.modalBody);
            $('#deletProuit .modal-content .modal-header h4').html('Suppression');
        },
        error: function (xhr) {
            alert('Erreur de chargement');
        }
    });
    console.log(idOffshore);
}

function laodForEditeOffshore(idOffshore) {
    $('#hiddenIdOffshore').val(idOffshore);

    $.ajax({
        url: 'offshore/laodForEditeOffshore',
        type: 'post',
        dataType: 'json',
        data: {idOffshore: idOffshore},
        success: function (data) {
            nameOffshore = data.nameOffshore;
            $('#editeOffshore .modal-body').html(data.modalBody);
            $('#editeOffshore .modal-content .modal-header h4').html('Editer un offshore');
            $('#editeOffshore').modal('show');
        },
        error: function (xhr) {
            alert('Erreur de chargement');
        }
    })
    console.log(idOffshore);
}

function deleteOffshore() {

    var idOffshore = rowOffshore.id,
        row = rowOffshore,
        value = valueOffshore,
        index = indexOffshore,
        e = actionEven;

    $table.bootstrapTable('remove', {
        field: 'id',
        values: [row.id]
    });

    $.ajax({
        url: 'offshore/delete',
        type: 'post',
        dataType: 'json',
        data: {idOffshore: idOffshore},
        success: function (data) {
            if (data.succes == 0) {
                console.log(data.tbodyOffshore);
                $table.bootstrapTable('load', data.tbodyOffshore);
                // $('#bodyTableOffshore').html(data.tbodyOffshore);
                $.gritter.add({
                    title: 'Suppression',
                    text: 'Le offshore ' + nameOffshore + ' a été supprimé',
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
            text: 'Erreur de serveur. Offshore non ajouté. <br> Veillez réessayer plutard',
            image: 'assets/img/un.png'
        });
    }else {
        $.gritter.add({
            title: 'Ajout',
            text: 'Vous avez ajouté un mouvel offshore',
            // image: 'assets/img/un.png'
            image: 'assets/img/confirm.png'
        });
    }

    $('#addProuit .modal-dialog').removeClass('small-modal');

}

function trieOffshore(idTrie) {
    $.ajax({
        url: 'offshore/trieOffshore',
        type: 'post',
        dataType: 'json',
        data: {idTrie: idTrie},
        success: function (data) {
            $table.bootstrapTable('removeAll');
            $table.bootstrapTable('append', data.bodyTableOffshore);
//            $('#bodyTableOffshore').html(data.bodyTableOffshore);
        }
    })
}

function showAllInfosOffshore(idOffshore) {
    $.ajax({
        url: 'offshort/showAllInfosOffshore',
        type: 'post',
        dataType: 'json',
        data: {idOffshore: idOffshore},
        success: function (data) {
            $('#showAllInfosOffshore .modal-body').html(data.infosOffshore);
            $('#showAllInfosOffshore .modal-header h4').html('Infos Offshore ' + data.header_stock);
            $('#showAllInfosOffshore').modal('show');
        },
        error: function (xhr) {
            alert('Erreur de chargement. Veillez réessqyer plutard');
        }
    })
}

function printOffshore(_idPrinter) {
    var frm = $("<form>", {
        action: "offshore/imprimer",
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
    $('#addProuit .modal-content .modal-header h4').html('Nouveau offshore');
    $('#addProuit .modal-body').load('offshore/loadAdd');
    $('#addProuit').modal('show');
}
