<?php
/**
 * Created by PhpStorm.
 * User: LEOBA / Willing
 * Date: 10/11/2018
 * Time: 11:02
 */
?>

<div class="container-table scrollbar-deep-purple" style="height: 540px; overflow-y: auto">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="fresh-table toolbar-color-azure">

                    <table id="produits-table" class="table table-responsive table-bordered">
                        <thead>
                            <th data-field="id">Ref.</th>

                            <th data-field="name" data-sortable="true">Date</th>

                            <th data-field="quantite" data-sortable="true">Offshore</th>

                            <th data-field="mouvement" data-sortable="true">Fournisseur</th>

                            <th data-field="action" data-sortable="true"></th>
                        </thead>

                        <tbody id="bodyTableProduit">
                        <?php foreach ($commandes as $commande) {
                                ?>
                            <tr>
                                <td><?php echo $commande->offid; ?></td>

                                <td><?php echo ucfirst($commande->date) ?></td>

                                <td><?php echo ucfirst($commande->offshore);?></td>

                                <td><?php echo ucfirst($commande->fournisseur) ?></td>

                                <td><a href="javascript: loadInfosProduitEntree(<?php echo $commande->offid ?>)" class="text-success"><i class="fa fa-plus-circle"></i></a></td>
                            </tr>
                                <?php
                        } ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<script>


    var $table = $('#produits-table'),
        full_screen = false;
    $().ready(function(){
        $('#loadProduitForEntree .modal-header h4').text("Commandes disponibles");
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


</script>
