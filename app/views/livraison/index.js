var $table = $('#chargement-table'),
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


    $('#trierLivraison').change(function (e) {
        var val = $(this).val();
        if (val != 0 && val < 4) {
            $.ajax({
                method: 'post',
                url: 'livraison/trier',
                dataType: 'json',
                data: {idTrie: val},
                success: function (data) {
                    $('#bodyTableListLivraisons').html(data.data);
                    console.log(data)
                },
                error: function (xhr) {
                    alert("Une erreure s'est produite. Veillez ressayer plutard");
                }
            })
        }else {
           // $('#choisirPeriode').modal('show');
           $('#choisirPeriode').addClass('in');
           $('#choisirPeriode').attr('aria-hidden', 'false');
           $('#choisirPeriode').css('display', 'block');
        }
    });

    $('#dateDebut').pickadate({
        format: 'yyyy-mm-dd',
        selectYears: 4,
        max: true
    });

    $('#dateLivraison').pickadate({
        format: 'yyyy-mm-dd',
        selectYears: 4,
        max: true
    });

    $('#dateDebut').change(function (e) {
        var dateSplite  = $(this).val().split('-'),
            date = new Date();
        if ($('#dateDebut').val() != '') {
            $('#dateFin').removeAttr('disabled');
            $('#dateFin').pickadate({
                format: 'yyyy-mm-dd',
                min: [parseInt(dateSplite[0]), parseInt(dateSplite[1]), parseInt(dateSplite[2])],
                max: [date.getFullYear(), date.getMonth() + 1, date.getDate()],
                selectYears: 4
            });
        }
    });

    //quand on click sur button trier 
    $('#btnTrieLivraison').click(function (e) {
        var btnRadio = $('input[name=dateTrie]'), valRadio;
        if (btnRadio.is(':checked') == false){
            $('#choisirPeriode .error').html('<span class="text-danger">Veillez chosir un type de trie</span>');
        }else {
            $('#choisirPeriode .error').html('');
            valRadio = $('input[name=dateTrie]:checked').val();
            if (valRadio == 1) {
                if ($('#dateLivraison').val() != '') {
                    dateLivraison = $('#dateLivraison').val();
                    $.ajax({
                        method: 'post',
                        url: 'livraison/trier',
                        dataType: 'json',
                        data: {
                            idTrie: 4,
                            dateLivraison: dateLivraison
                        },
                        success: function (data) {
                            $('#choisirPeriode').modal('toggle');
                            // $('#choisirPeriode').removeClass('in');
                            // $('#choisirPeriode').removeAttr('aria-hidden', 'false');
                            // $('#choisirPeriode').css('display', 'none');
                            $('#bodyTableListLivraisons').html(data.data);
                            console.log(data)
                        },
                        error: function (xhr) {
                            alert("Une erreure s'est produite. Veillez ressayer plutard");
                        }
                    });
                }else {
                    $('#choisirPeriode .error-date').html('<span class="text-danger">Veillez chosir une date</span>');
                    $('#choisirPeriode #dateLivraison').css('border-color', 'red');
                }

            }else {
                if ($('#dateDebut').val() != '' && $('#dateFin').val() != '') {
                    if ($('#dateFin').val() != '') {
                        $.ajax({
                            method: 'post',
                            url: 'livraison/trier',
                            dataType: 'json',
                            data: {
                                idTrie: 4,
                                dateDebut: $('#dateDebut').val(),
                                dateFin: $('#dateFin').val()
                            },
                            success: function (data) {
                                $('#choisirPeriode').modal('toggle');
                                // $('#choisirPeriode').removeClass('in');
                                // $('#choisirPeriode').removeAttr('aria-hidden', 'false');
                                // $('#choisirPeriode').css('display', 'none');
                                $('#bodyTableListLivraisons').html(data.data);
                                console.log(data)
                            },
                            error: function (xhr) {
                                alert("Une erreure s'est produite. Veillez ressayer plutard");
                            }
                        });
                    }
                }else {
                    if ($('#dateDebut').val() == '' && $('#dateFin').val() == '') {
                        $('#choisirPeriode .error-periode').html('<span class="text-danger">Veillez chosir une date de debut et de fin</span>');
                        $('#choisirPeriode #dateFin').css('border-color', 'red');
                        $('#choisirPeriode #dateDebut').css('border-color', 'red');
                    }else if ($('#dateFin').val() == '') {
                        $('#choisirPeriode .error-periode').html('<span class="text-danger">Veillez chosir une date de fin</span>');
                        $('#choisirPeriode #dateFin').css('border-color', 'red');
                        $('#choisirPeriode #dateDebut').css('border-color', '#AAAAAA');
                    }else {
                        $('#choisirPeriode .error-periode').html('<span class="text-danger">Veillez chosir une date de debut et de fin</span>');
                        $('#choisirPeriode #dateDebut').css('border-color', 'red');
                        $('#choisirPeriode #dateFin').css('border-color', '#AAAAAA');
                    }
                }
            }
        }
    });



    $('#dateImpressionLivraison').pickadate({
        format: 'yyyy-mm-dd',
        max: true
    });

    $('#dateDebutImpression').pickadate({
        format: 'yyyy-mm-dd',
        max: true
    });
    $('#dateDebutImpression').change(function (e) {
        var dateSplite  = $(this).val().split('-'),
            date = new Date();
        if ($(this).val() != '') {
            $('#dateFinImpression').removeAttr('disabled');
            $('#dateFinImpression').pickadate({
                format: 'yyyy-mm-dd',
                min: [parseInt(dateSplite[0]), parseInt(dateSplite[1]), parseInt(dateSplite[2])],
                max: [date.getFullYear(), date.getMonth() + 1, date.getDate()],
                selectYears: 4
            });
        }
    });



    $('#choisirPeriodemprimerLivraison .close').click(function () {
        $('#choisirPeriodemprimerLivraison').modal('toggle');
    });
    $('#choisirPeriode .close').click(function () {
        $('#choisirPeriode').modal('toggle');
    });
    //quand on click sur imprimer
    $('#btnImpressionLivraison').click(function (e) {
        var btnRadio = $('input[name=dateImpression]'), valRadio, _idPrinter = 5;

        // on test si une methode est chosie ou pas
        if (btnRadio.is(':checked') == false){
            $('#choisirPeriodemprimerLivraison .error').html('<span class="text-danger">Veillez chosir un type d\'impression</span>');
        }else {
            if ($('input[name=dateImpression]:checked').val() == 1) {
                $('#choisirPeriodemprimerLivraison .error').html('');
                $('#choisirPeriodemprimerLivraison .error-date').html('');
                $('#choisirPeriodemprimerLivraison .error-periode').html('');
                var frm = $("<form>", {
                    action: "livraison/imprimer",
                    method: "post",
                    target: '_blank'
                }).append($("<input>", {
                    name: "idPrinter",
                    type: "hidden",
                    value: _idPrinter
                })).append($("<input>", {
                    name: "dateImpression",
                    type: "hidden",
                    value: $('#dateImpressionLivraison').val()
                })).appendTo("body");

                frm.submit();
            }else {
                $('#choisirPeriodemprimerLivraison .error').html('');
                $('#choisirPeriodemprimerLivraison .error-date').html('');
                $('#choisirPeriodemprimerLivraison .error-periode').html('');

                if ($('#dateDebutImpression').val() != '' && $('#dateFinImpression').val() != '') {
                    $('#choisirPeriodemprimerLivraison .error').html('');
                    $('#choisirPeriodemprimerLivraison .error-date').html('');
                    $('#choisirPeriodemprimerLivraison .error-periode').html('');
                    var frm = $("<form>", {
                        action: "livraison/imprimer",
                        method: "post",
                        target: '_blank'
                    }).append($("<input>", {
                        name: "idPrinter",
                        type: "hidden",
                        value: _idPrinter
                    })).append($("<input>", {
                        name: "dateDebutImpression",
                        type: "hidden",
                        value: $('#dateFinImpression').val()
                    })).append($("<input>", {
                        name: "dateFinImpression",
                        type: "hidden",
                        value: $('#dateFinImpression').val()
                    })).appendTo("body");

                    frm.submit();
                }else {
                    $('#choisirPeriodemprimerLivraison .error').html('');
                    $('#choisirPeriodemprimerLivraison .error-date').html('');
                    $('#choisirPeriodemprimerLivraison .error-periode').html('');
                    if ($('#dateDebutImpression').val() == '' && $('#dateFinImpression').val() == '') {
                        $('#choisirPeriodemprimerLivraison .error-periode').html('<span class="text-danger">Veillez chosir une date de debut et fin</span>');
                        console.log('periode')
                    }else if ($('#dateFinImpression') == '') {
                        $('#choisirPeriodemprimerLivraison .error-periode').html('<span class="text-danger">Veillez chosir une date de debut</span>');
                    } else {
                        $('#choisirPeriodemprimerLivraison .error-periode').html('<span class="text-danger">Veillez chosir une date de fin</span>');
                    }

                }
            }
            $('#choisirPeriodemprimerLivraison .error').html('');
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

//impression des livraison
function printLivraison(_idPrinter) {
    if (_idPrinter < 5) {
        var frm = $("<form>", {
            action: "livraison/imprimer",
            method: "post",
            target: '_blank'
        }).append($("<input>", {
            name: "idPrinter",
            type: "hidden",
            value: _idPrinter
        })).appendTo("body");

        frm.submit();
    }else {
        $('#choisirPeriodemprimerLivraison').addClass('in');
        $('#choisirPeriodemprimerLivraison').attr('aria-hidden', 'false');
        $('#choisirPeriodemprimerLivraison').css('display', 'block');
    }
}



