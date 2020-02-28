<?php
/**
 * Created by PhpStorm.
 * User: LEOBA / Willing
 * Date: 08/11/2018
 * Time: 13:23
 */
?>

<div class="container-table">
    <div class="container-fluid scrollbar-deep-purple" style="height: 490px; overflow-y: auto">
        <div class="row">
            <div class="col-md-12">
                <div class="fresh-table">
                    <table class="table table-responsive" id="listProduitVente">
                        <thead>
                        <th>Ref.</th>
                        <th>Designation</th>
                        <th>Famille</th>
                        <th>Prix unitaire</th>
                        <th>Prix du blog</th>
                        <th>Nbre. p. par blog</th>
                        <th>Stock</th>
                        <th></th>
                        </thead>
                        <tbody>
                        <?php foreach ($produits as $produit) {
                            ?>
                            <tr>
                                <td><?php echo $produit['idProduit'] ?></td>
                                <td><?php echo $produit['produit'] ?></td>
                                <td><?php echo $produit['famille'] ?></td>
                                <td><?php echo $produit['proprixUnitVente'] ?></td>
                                <td><?php echo $produit['proprixblogVente']; ?>/td>
                                <td><?php echo $produit['pronbproduitBlog']; ?></td>
                                <td><?php echo $produit['quantite'] ?></td>
                                <?php if (!$produit['quantiteBool']) : ?>
                                    <td><a class="disabled" disabled><i class="fa fa-plus-circle" style="color: #AAAAAA;"></i></a></td>
                                <?php else: ?>
                                    <td><a href="javascript: loadInfosProduitVente(<?php echo $produit['idProduit'] ?>)" class="text-success"><i class="fa fa-plus-circle"></i></a></td>
                                <?php endif; ?>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>


<script>
    var $table = $('#listProduitVente'),
        $alertBtn = $('#alertBtn'),
        full_screen = false;

    $().ready(function() {
        $table.bootstrapTable({
            toolbar: ".toolbar",
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
</script>
