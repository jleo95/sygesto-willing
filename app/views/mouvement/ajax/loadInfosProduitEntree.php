<?php
/**
 * Created by PhpStorm.
 * User: LEOBA / Willing
 * Date: 10/11/2018
 * Time: 12:01
 */
?>
    <script>

    </script>
    <div class="error-produit">
        <div class="alert alert-block alert-danger" style="display:none">
            <strong>Erreur !</strong>
            <p></p>
        </div>
    </div>
    <div>
        <a data-toggle="modal" href="javascript: loadProduitForEntree2()" style="font-size: 20px;"><i class="fa fa-arrow-circle-left"></i> retour</a>
    </div>
    <div class="produit">
        <div class="line">
            <input type="hidden" name="hiddenIdOffre" id="hiddenIdOffre" value="<?php echo $commande->offid ?>">
            <span class="label">Ref.: </span><span class="value"><?php echo $commande->offid; ?></span>
        </div>
        <div class="line">
            <span class="label">Ref.: </span><span class="value"><?php echo $commande->date; ?></span>
        </div>
        <div class="line">
            <input type="hidden" name="hiddenIdOffshore" id="hiddenIdOffshore" value="<?php echo $commande->offshore; ?>">
            <span class="label">Ref.: </span><span class="value"><?php echo $commande->offshore; ?></span>
        </div>
        <div class="line">
            <span class="label">Description: </span><span class="value"><?php echo ucfirst($commande->description); ?></span>
        </div>
        <div class="line">
            <span class="label">Fournisseur: </span><span class="value"><?php echo ucfirst($commande->fournisseur); ?></span>
        </div>
        <div class="line">
            <span class="label">Date chargement: </span><span class="value"><input type="text" style="width: 280px" class="form-control" placeholder="date du chargement" id="dateMouvement">
        </div>
        <div>
            <div class="commande-content">

                <div class="container-table scrollbar-deep-purple" style="height: 450px; overflow-y: auto">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="fresh-table">

                                    <table id="commande-details-table" class="table table-responsive table-bordered">
                                        <thead>
                                        <th data-field="id">Ref.</th>

                                        <th data-field="name" data-sortable="true">Designation</th>

                                        <th data-field="quantite-cmd" data-sortable="true">Qté cmdée</th>

                                        <th data-field="quantite-charge" data-sortable="true">Qté livrée</th>

                                        <th data-field="actions" data-formatter="operateFormatter" data-events="operateEvents">Actions</th>
                                        </thead>

                                        <tbody id="bodyTableProduit">
                                        <?php foreach ($commande_details as $cmd) : ?>
                                            <tr>
                                                <td><?php echo $cmd->produit ?></td>
                                                <td><?php echo $cmd->prodesignation ?></td>
                                                <td>
                                                    <?php echo $cmd->quantite . ' ' . $cmd->unite ?>
                                                </td>
                                                <td>
                                                    <?php //echo $cmd->quantite ?>
                                                    <input type="number" name="newQuantite" class="newQuantite form-control" value="<?php echo $cmd->quantite ?>"> <span><?php echo $cmd->unite ?></span>
                                                    <input type="hidden" name="hiddenIdProduitSetQuantite" class="hiddenIdProduitSetQuantite" value="<?php echo $cmd->produit ?>">
                                                </td>
                                                <td><a href="javascript: removeProduitFromCommande(<?php echo $commande->offid ?>)" class="text-danger"><i class="fa fa-remove"></i></a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <br>
        <div class="line clear">
            <div class="right">
                <button type="button" data-dismiss="modal" id="btnValideLivraison" class="btn btn-sm btn-default">Valider</button>
            </div>
        </div>
    </div>

<script>


    var setQuantity = [], index = 0;

    var $table = $('#commande-details-table'),
        full_screen = false;
    $().ready(function(){
        $table.bootstrapTable({
            pagination: true,
            striped: true,
            sortable: true,
            pageSize: 8,
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


        $(".newQuantite").change(function (e) {
            console.log(e)
            // setQuantity[index]['id'] = $(this).parent().children('input.hiddenIdProduitSetQuantite').val();
            // setQuantity[index]['quantite'] = $(this).val();
            // setQuantity[index]['idOffre'] = $('#hiddenIdOffre').val();

            setQuantity.push({
                produit: $(this).parent().children('input.hiddenIdProduitSetQuantite').val(),
                quantite: $(this).val(),
                offre: $('#hiddenIdOffre').val(),
                offshore: $('#hiddenIdOffshore').val()
            });
            // index ++;
            console.log("set ", setQuantity)
        });

        $('#btnValideLivraison').click(function (e) {
            if ($('#dateMouvement').val() != '') {
                $.ajax({
                    url: 'mouvement/addLivraisonOnView',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'idOffre': $('#hiddenIdOffre').val(),
                        'idOffshore': $('#hiddenIdOffshore').val(),
                        'dateMvt': $('#dateMouvement').val(),
                        'setData': setQuantity
                    },
                    success: function (data) {
                        $('#bodyTableProduitEntree').append(data.html);
                        $.gritter.add({
                            title: 'Ajout d\'une nouvelle commande',
                            text: 'Nouvelle commande ajouter en queux de la liste livraison',
                            image: 'assets/img/confirm.png'
                        });

                        $('form div.text-center').html('<button type="submit" name="btnProcessEnd" class="btn btn-success" id="btnProcessEntree">Terminer <i class="fa fa-arrow-right"></i></button>');
                    },
                    error: function (xhr) {
                        alert('Erreur de chargement. Veillez réessayer plutard');
                    }
                })
            } else {
                alert("Veillez choisir la date du chargement");
                $('#dateMouvement').addClass('danger')
                return false;
            }


            // return false;
        });

        $('#dateMouvement').pickadate({
            format: 'yyyy-mm-dd'
        });

    });

    window.operateEvents = {
        'click .remove-commande': function (e, value, row, index) {
            rowCommande = row;
            idCommande = row.id;

            if (confirm("Voulez-vous vraiment retirer cet article ?")) {
                $table.bootstrapTable('remove', {
                    field: 'id',
                    values: [row.id]
                });
                $.ajax({
                    url: 'mouvement/removeProduitFromCommande',
                    type: 'post',
                    dataType: 'json',
                    data: {idCommande: row.id},
                    success: function (data) {
                        console.log('done')
                    },
                    error: function (xhr) {
                        alert('Erreur de chargement');
                    }
                });
            }

        }
    };
    function operateFormatter(value, row, index) {
        return [
            '<a rel="tooltip" title="Remove" class="table-action remove-commande text-danger" href="javascript:void(0)">',
            '<i class="fa fa-remove"></i>',
            '</a>'
        ].join('');
    }
</script>

<style>
    input.danger{
        border-color: #cc2127;
    }
</style>