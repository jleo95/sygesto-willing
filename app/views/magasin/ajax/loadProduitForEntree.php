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

                            <th data-field="name" data-sortable="true">Designation</th>

                            <th data-field="mouvement" data-sortable="true">Famille</th>

                            <th data-field="quantite" data-sortable="true">Stock Magasin</th>

                            <th data-field="action" data-sortable="true"></th>
                        </thead>

                        <tbody id="bodyTableProduit">
                        <?php foreach ($produits as $produit) {
                            if ($stock_function->stock_magasin_by_produit($produit->proid) > 0) {
                                ?>
                                <tr>
                                    <td><?php echo $produit->proid; ?></td>
                                    <td><?php echo ucfirst($produit->prodesignation) ?></td>
                                    <td><?php echo ucfirst($produit->famille) ?></td>
                                    <td><?php echo $stock_function->stock_magasin_by_produit($produit->proid); ?></td>
                                    <td><a href="javascript: loadInfosProduitEntree(<?php echo $produit->proid ?>)" class="text-success"><i class="fa fa-plus-circle"></i></a></td>
                                </tr>
                                <?php
                            }
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
        $('#loadProduitForEntree .modal-header h4').text("Produit disponible");
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
