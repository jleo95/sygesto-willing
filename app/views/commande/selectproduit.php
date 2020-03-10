<div class="views-commande">
    <div class="table-offre">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="fresh-table">
                        <input type="hidden" name="hiddenIdcommande" id="hiddenIdcommande">
                        <table id="select-produit-table" class="table table-responsive table-bordered">
                            <thead>
                                <th data-field="id">#</th>
                                <th data-field="designation">Designation</th>
                                <th data-field="famille">Famille</th>
                                <th data-field="unite">Unite</th>
                                <th data-field="actions" data-formatter="operateFormatter" data-events="operateEvents"></th>
                            </thead>
                            <tbody id="tbodyProduitCommande">
                            <?php
                            $i = 1;
                            foreach ($produits as $produit):
                                ?>
                                <tr>
                                    <td><?php echo $produit->proid; ?></td>
                                    <td><?php echo $produit->prodesignation; ?></td>
                                    <td><?php echo $produit->famille; ?></td>
                                    <td><?php echo $produit->unite; ?></td>
                                    <td></td>
                                </tr>
                            <?php
                            endforeach;
                            ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="addProduitCommande">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 class="modal-title">Produit</h4>
            </div>
            <div class="modal-body">
                <div>
                    <label>Quantité: </label>
                    <input type='text' name='quantite' id='inputQuantite' class='form-control' placeholder='quantié à commander'>
                </div><br>
                <div class="btn-div" style='text-align: center;'>
                    <input type='button' id='btnValidSelectionProduit' class='btn btn-default disabled' value='Ajouter' disabled>
                </div>
            </div>
        </div>
    </div>
</div>


<script !src="">
    var $table = $('#select-produit-table'), idProduit = 0, quantite = 0, unite = 0, famille = '', designation = '';

    $().ready(function() {

        $table.bootstrapTable({
            toolbar: ".toolbar",
            showRefresh: true,
            search: true,
            showColumns: true,
            pagination: true,
            striped: true,
            pageSize: 15,
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

        $('#inputQuantite').change(function () {
            if ($(this).val() != '' && $(this).val() > 0) {
                $('#addProduitCommande .modal-body .btn-div').html("<input type='button' id='btnValidSelectionProduit' class='btn btn-success' value='Ajouter' onclick=\"addProduitToCommande()\" >");
            }else{
                $('#addProduitCommande .modal-body .btn-div').html("<input type='button' id='btnValidSelectionProduit' class='btn btn-default disabled' value='Ajouter' disabled>");
            }
        });

        $('#btnValidSelectionProduit').click(function () {
            console.log('clicker');
            $.ajax({
                method: post,
                url: 'addProduitToCommande',
                data: {
                    idProduit: idProduit,
                    quantite: $('#inputQuantite').val()
                },
                success: function (e) {
                    console.log('resuite')
                },
                error: function (xhr) {
                    alert("Une erreur s'est produite veillez reassayer plutard")
                }
            })
        });

    });


    window.operateEvents = {
        'click .ajout': function (e, value, row, index) {
            idProduit = row.id;
            designation = row.designation;
            unite = row.unite;
            famille = row.famille;
            $('#addProduitCommande .modal-title').html(row.designation);
            $('#addProduitCommande .modal-title').html(row.designation);
            $('#inputQuantite').val('');
            $('#addProduitCommande .modal-body .btn-div').html("<input type='button' id='btnValidSelectionProduit' class='btn btn-default disabled' value='Ajouter' disabled>");
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
            '<a rel="tooltip" data-toggle="modal" href="#addProduitCommande" title="Ajouter" class="table-action ajout text-success" href="javascript:void(0)">',
            '<i class="fa fa-plus-circle"></i>',
            '</a>'
        ].join('');
    }

    function addProduitToCommande() {
        $.ajax({
            method: 'post',
            url: 'commande/addProduitToCommande',
            data: {
                type: 1,
                idProduit: idProduit,
                unite: unite,
                famille: famille,
                designation: designation,
                quantite: $('#inputQuantite').val()
            },
            success: function (data) {
                window.location.replace('http://sygesto/commande/ajout');
                // $('#tbodyProduitsCommande').append(data.html);
            },
            error: function (xhr) {
                alert("Une erreur s'est produite veillez reassayer plutard")
            }
        })
    }


</script>