var $table = $('#user-table'),
    $alertBtn = $('#alertBtn'),
    nameUser = null,
    rowUser = null,
    valueUser = null,
    indexUser = null,
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
$().ready(function() {
    $('#connexion-table').bootstrapTable({
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
    'click .edit-user': function (e, value, row, index) {
        // laodForEditeProduit(row.id);
        $.ajax({
            url: 'user/loadForEditUser',
            type: 'post',
            dataType: 'html',
            data: {idUser: row.id},
            success: function (data) {
                $('#editeUser .modal-body').html(data);
                $('#editeUser').modal('show');
            },
            error: function (xhr) {
                alert('Erreur de chargement');
            }

        })
    },
    'click .remove-user': function (e, value, row, index) {
        rowUser = row;
        valueUser = value;
        indexUser = index;
        actionEven = e;

        $.ajax({
            url: 'user/laodDataForDeleteUser',
            type: 'post',
            dataType: 'json',
            data: {idUser: row.id},
            success: function (data) {
                nameUser = data.nameUser;
                $('#deletUser .modal-body').html(data.modalBody);
                $('#deletUser .modal-content .modal-header h4').html('Verouillez un utilisateur');
                $('#deletUser').modal('show');
            },
            error: function (xhr) {
                alert('Erreur de chargement');
            }
        });
    }

};

function operateFormatter(value, row, index) {
    return [
        '<a rel="tooltip" title="Editer" class="edit-user text-success" href="javascript: void(0)">',
        '<i class="fa fa-pencil"></i>',
        '</a>',
        '<a rel="tooltip" title="Verouiller" class="remove-user text-danger" data-toggle="modal" href="javascript: void(0)">',
        '<i class="fa fa-key"></i>',
        '</a>'
    ].join('');
}

function deleteUser() {

    var idUser = rowUser.id,
        row = rowUser,
        value = valueUser,
        index = indexUser,
        e = actionEven;
    // $table.bootstrapTable('remove', {
    //     field: 'id',
    //     values: [row.id]
    // });
    $.ajax({
        url: 'user/delete',
        type: 'post',
        dataType: 'json',
        data: {idUser: idUser},
        success: function (data) {
            if (data.succes == 0) {
                console.log(data.tbodyProduit);
                $table.bootstrapTable('removeAll');
                $table.bootstrapTable('append', data.tbodyUser);
                // $('#bodyTableProduit').html(data.tbodyProduit);
                $.gritter.add({
                    title: 'Verouillage',
                    text: 'L\'utilisateur ' + nameUser+ ' a été verouillé',
                    image: 'assets/img/confirm.png'
                });

              
            }
        },
        error: function (xhr) {
            alert('Erreur de chargement');
        }
    })
    console.log(idProduit);
}

function contentModalAdd() {
    $('.container-form-AddUser').load('user/loadForContent');
}