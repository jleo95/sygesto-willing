<?php
/**
 * Created by PhpStorm.
 * User: LEOBA / Willing
 * Date: 10/11/2018
 * Time: 10:44
 */
?>

<div class="views-entree">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
<!--                --><?php //$_SESSION['entrees'] = []; ?>
                <table class="table table-responsive">
                    <thead>
                        <th></th>
                        <th>Offshore</th>
                        <th>Commande</th>
                        <th>Produit</th>
                        <th>Date cmde</th>
                        <th>Date Livraison</th>
                        <th></th>
                    </thead>
                    <tbody id="bodyTableProduitEntree">
                    <?php if (isset($_SESSION['newMouvements']) AND !empty($_SESSION['newMouvements'])) : ?>
                    <?php
                    $i    = 1;
                    $line = '';
                    foreach ($_SESSION['newMouvements'] as $commde) {
                        $line = $i;
                        ?>
                        <tr class="<?php echo $line; ?>">
                            <td><?php echo $line; ?></td>
                            <td><?php echo ($commde['offshore'] != null) ? $commde['offshore'] : '//' ?></td>
                            <td><?php echo $commde['offre'] ?></td>
                            <td><?php echo $commde['produit']; ?></td>
                            <td><?php echo formatDate($commde['dateCmd'], 'd/m/Y') ?></td>
                            <td><?php echo formatDate($commde['dateMvt'], 'd/m/yy') ?></td>
                            <td><a href="javascript: removeProduitForEntree(<?php //echo $commde->offid . ', ' . $i ?>)" class="btn abtn abtn-danger"><i class="fa fa-trash-o"></i></a></td>
                        </tr>
                        <?php
                        $i ++;
                    }
                     endif; ?>
                    </tbody>

                </table>

                <div class="clear">
                    <div class="right"><a data-toggle="modal" href="#loadProduitForEntree" onclick="loadProduitForEntree()" class="text-success" style="font-size: 25px;"><i class="fa fa-plus-circle"></i></a></div>
                </div>

                <form method="post">
                    <div class="text-center">
                        <?php if (isset($_SESSION['newMouvements']) AND !empty($_SESSION['newMouvements'])) : ?>
                            <button type="submit" name="btnProcessEnd" class="btn btn-success" id="btnProcessEntree">Terminer <i class="fa fa-arrow-right"></i></button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-default disabled" disabled id="btnProcessEntree">Terminer <i class="fa fa-arrow-right"></i></button>
                        <?php endif; ?>
                    </div>
                </form>


                <div class="modal" id="loadProduitForEntree">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">x</button>
                                <h4 class="modal-title">Liste des produits</h4>
                            </div>
                            <div class="modal-body">
                                delete
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


