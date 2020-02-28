<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 05/11/2018
 * Time: 03:36
 */
$controller = new \App\Core\Controller();
?>

<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="fresh-table">
                    <table class="table" id="tableProduitOffre">

                        <thead>
                            <th data-field="name">Designation</th>
                            <th data-field="family">Famille</th>
                            <th data-field="prix">Prix unitaire</th>
                            <th data-field="stock">Stock</th>
                            <th data-field="actions"></th>
                        </thead>
                        <tbody>
                        <?php foreach ($produits as $produit) {
                            ?>

                            <tr>
                                <td><?php echo ucfirst($produit->prodesignation) ?></td>
                                <td><?php echo $produit->famille; ?></td>
                                <td><span style="font-style: italic; font-size: 14px;"><?php echo $produit->proprixUnitAchat; ?></span></td>
                                <td>
                                    <?php if ($controller->stock_magasin_by_produit($produit->proid) == 0) : ?>
                                        <span style="font-style: italic; font-size: 12px;"><?php echo 'rupture' ?></span>
                                    <?php else: ?>
                                        <?php echo $controller->stock_magasin_by_produit($produit->proid) ?>
                                    <?php endif; ?>
                                </td>
                                <td><a href="javascript: loadProduitForAddOffre(<?php echo $produit->proid ?>)"><i class="fa fa-plus" style="color: #008000;"></i></a></td>
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

    var $table = $('#tableProduitOffre'),
        $alertBtn = $('#alertBtn'),
        full_screen = false,
        nameClient = '';

    $().ready(function() {

        $table.bootstrapTable({
            toolbar: ".toolbar",
            search: true,
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
