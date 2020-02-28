<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 08/11/2018
 * Time: 01:50
 */
?>

<div class="venteAdd-view">
    <div class="title-vente"><span class="title">Numéro de vente: </span> <span class="value"><?php echo $idVente ?></span></div>
    <div class="container-fluid">
        <div class="row">
            <?php if (isset($_SESSION['vente']) AND !empty($_SESSION['vente'])) : ?>
            <div class="col-md-10 col-md-offset-1">
<!--                --><?php //var_dump($_SESSION['vente_detail']) ?>
                <table class="tableVenteProduit table" id="tableVenteProduit">
                    <thead>
                        <th>Ref</th>
                        <th>Designation</th>
                        <th>Famille</th>
                        <th>Prix unitaire</th>
                        <th>Unité de mésure</th>
                        <th>Quantité</th>
                        <th>Prix total</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <?php
                        $i          = 1;
                        $prixtotaux = 0;
                        if (isset($_SESSION['vente_detail']) AND !empty($_SESSION['vente_detail'])) :
                            ?>
                        <?php foreach ($_SESSION['vente_detail'] as $detail) {
                            $prixtotaux += $detail['prixtotal'];
                                ?>
                                <tr class="line<?php echo $i?>">
                                    <td><?php echo $detail['idProduit']; ?></td>
                                    <td><?php echo $detail['produit']; ?></td>
                                    <td><?php echo $detail['famille']; ?></td>
                                    <td><?php echo number_format($detail['prix'], 2, ',', ' '); ?></td>
                                    <td><?php echo $detail['unite']; ?></td>
                                    <td><?php echo $detail['quantite']; ?></td>
                                    <td><span style="font-style: italic;"><?php echo number_format($detail['prixtotal'], 2, ',', ' '); ?></span></td>
                                    <td><a href="javascript: removeProduitInVente(<?php echo $detail['idProduit'] ?>, <?php echo $i ?>)" class="btn abtn abtn-danger"><i class="fa fa-trash"></i></a></td>
                                </tr>
                        <?php
                            $i ++;
                        }
                        endif;
                        ?>
                    </tbody>
                </table>
                <div class="clear">
                    <div class="right"><a data-toggle="modal" href="#loadProduitAddVente" onclick="loadProduitForTableAddVente()" class="text-success" style="font-size: 22px"><i class="fa fa-plus-circle"></i></a></div>
                </div>
                <div class="text-right prixTatal" style="margin-top: 20px;">
                    <p><span class="label">Prix totaux: </span> <span class="value"><?php echo number_format($prixtotaux, 2, ',', ' ')?> fcfa</span></p>
                </div>
                <div class="center-block text-center" id="formEndProcess">
                    <?php if (isset($_SESSION['endProcessVente']) AND $_SESSION['endProcessVente']) {
                        ?>

                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <form action="" method="post" id="formEndProcessVente">
                                    <?php if (!isset($_SESSION['ventes_print'])) : ?>
                                    <div class="form-group">
                                        <label for="modePaiement">Mode de paiement: </label>
                                        <select name="modePaiment" id="modePaiement" class="form-control">
                                            <option value="">Paiement</option>
                                            <?php foreach ($paiements as $paiement) {
                                                ?>
                                            <option value="<?php echo $paiement->paiid; ?>"><?php echo $paiement->pailibelle; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <?php endif; ?>
                                    <div class="clear text-center">
                                        <?php if (isset($_SESSION['ventes_print'])) { ?>
                                            <a href="vente/endProcess" class="btn btn-success">Terminer</a>
                                            <button type="button" class="btn btn-primary" onclick="imprimerRecu()">Imprimer reçu <i class="fa fa-print"></i></button>
                                        <?php } else { ?>
                                            <button type="submit" class="btn btn-success" name="btnEnProcessVenteSubmit">Enregistrer</button>
                                            <button type="button" class="btn btn-default disabled" disabled>Imprimer reçu <i class="fa fa-print"></i></button>
                                        <?php  } ?>
                                    </div>
                                </form>
                            </div>
                            <script>
                                $(document).ready(function () {
                                    $('#formEndProcessVente').submit(function () {
                                        var formValide = true;

                                        if ($('#modePaiement').val() == '') {
                                            fieldForm($('#modePaiement'), 'Veillez choisir le mode de paiement svp.', 1);
                                            formValide = false;
                                        }else {
                                            fieldForm($('#modePaiement'), '', 0);
                                        }

                                        return formValide;
                                    })
                                })
                            </script>
                        </div>
                    <?php
                    }else {
                        ?>
                    <?php if (isset($_SESSION['vente_detail']) AND !empty($_SESSION['vente_detail'])) : ?>
                    <button type="button" class="btn btn-success" name="btnProcessVente2" id="btnProcessVente2" onclick="loadNextPart()">Suivant <i class="fa fa-arrow-right"></i></button>
                    <?php else : ?>
                    <button type="button" class="btn btn-default disabled" disabled name="btnProcessVente2" id="btnProcessVente2" onclick="loadNextPart()">Suivant <i class="fa fa-arrow-right"></i></button>
                    <?php endif;?>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <?php else: ?>
                <div class="col-md-6 col-md-offset-3">
                    <form id="formProcessVente1" action="" method="post">
                        <div class="form-group">
                            <label for="clientVente">Client: </label>
                            <select name="clientVente" id="clientVente" class="form-control">
                                <?php foreach ($clients as $client) {
                                    ?>
                                    <option value="<?php echo $client->cliid; ?>"><?php echo ucfirst($client->clinom) . ' ' . ucfirst($client->cliprenom) ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="dateVente">Date : </label>
                            <input type="date" name="dateVente" id="dateVente" class="form-control" placeholder="date de vente">
                        </div>
                        <div class="center-block text-center">
                            <button type="submit" class="btn btn-success" name="btnProcessVente1">Suivant <i class="fa fa-arrow-right"></i></button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <div class="modal" id="loadProduitAddVente">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Liste des produits</h4>
                    </div>
                    <div class="modal-body">
                        editer
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
