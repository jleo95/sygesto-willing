<div class="views-commande">
    <div class="table-offre">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="fresh-table">
                        <input type="hidden" name="hiddenIdcommande" id="hiddenIdcommande">
                        <table id="selectproduit-table" class="table table-responsive table-bordered">
                            <thead>
                            <th data-field="commande">Commande</th>
                            <th data-field="line">#</th>
                            <th data-field="designation">Produit</th>
                            <th data-field="quantite">Quantite</th>
                            <th data-field="unite">Unite</th>
                            <th data-field="actions" data-formatter="operateFormatter" data-events="operateEvents"></th>
                            </thead>
                            <tbody id="tbodyProduitCommande">
                            <?php
                            $i = 1;
                            foreach ($produits as $k => $produit):
                                foreach ($produit as $p) {
                                    ?>
                                    <tr>
                                        <td><?php echo $k; ?></td>
                                        <td><?php echo $p->produit_id; ?></td>
                                        <td><?php echo $p->produit; ?></td>
                                        <td><?php echo $p->quantite; ?></td>
                                        <td><?php echo $p->unite; ?></td>
                                        <td></td>
                                    </tr>
                                    <?php
                                }
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

<div class="modal" id="addProduitToLivraison">
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
                <div class="error"></div>
                <div class="btn-div" style='text-align: center;'>
                    <input type='button' id='btnValidSelectionProduit' class='btn btn-default disabled' value='Ajouter' disabled>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    table{
        border-collapse: collapse;
        border: 1px solid #dddddd !important;
    }
    table tr{
        border: 1px solid #dddddd;
    }
</style>

<script !src="">
    var $table = $('#selectproduit-table'),
        $alertBtn = $('#alertBtn'),
        full_screen = false,
        idProduit, idOffre;

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

        $('#inputQuantite').change(function () {
            if ($(this).val() != '' && $(this).val() > 0) {
                $('#addProduitToLivraison .modal-body .btn-div').html("<input type='button' id='btnValidSelectionProduit' class='btn btn-success' value='Ajouter' onclick=\"addProduitToLivraison()\" >");
            }else{
                $('#addProduitToLivraison .modal-body .btn-div').html("<input type='button' id='btnValidSelectionProduit' class='btn btn-default disabled' value='Ajouter' disabled>");
            }
        });

        $('#btnValidSelectionProduit').click(function () {

        });

    });

    window.operateEvents = {
        'click .ajout': function (e, value, row, index) {
            idProduit = row.line;
            designation = row.designation;
            unite = row.unite;
            famille = row.famille;
            idOffre = row.commande;
            $('#addProduitToLivraison .modal-title').html(row.designation);
            $('#addProduitToLivraison .modal-title').html(row.designation);
            $('#inputQuantite').val('');
            $('#addProduitToLivraison .modal-body .btn-div').html("<input type='button' id='btnValidSelectionProduit' class='btn btn-default disabled' value='Ajouter' disabled>");
        }
    };
    function operateFormatter(value, row, index) {
        return [
            '<a rel="tooltip" data-toggle="modal" title="Voir plus" class="table-action ajout text-success" href="#addProduitToLivraison">',
            '<i class="fa fa-plus-circle"></i>',
            '</a>'
        ].join('');
    }



    function addProduitToLivraison() {
        console.log('clicker');
        $.ajax({
            method: 'post',
            url: 'livraison/addProduitToLivraison',
            dataType: 'json',
            data: {
                idProduit: idProduit,
                offre: idOffre,
                quantite: $('#inputQuantite').val()
            },
            success: function (donnee) {
                console.log(donnee);
                if (donnee.success == 1) {
                    console.log('resuite')
                    window.location.replace('http://sygesto/livraison/ajout');
                    // window.location
                }else {
                    $('#addProduitToLivraison .modal-body .error').append("<span class='text-danger'>La quantite passée (dans la commande) est insufisante.</span>");
                    // $.gritter.add({
                    //     title: 'Erreur',
                    //     text: 'La quantite passée est insufisante.',
                    //     image: 'assets/img/un.png'
                    // });
                    console.log('quantite livre supperieur a commandee')
                }
            },
            error: function (xhr) {
                alert("Une erreur s'est produite veillez reassayer plutard")
            }
        });
    }
</script>