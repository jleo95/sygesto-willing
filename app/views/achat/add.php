<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 05/11/2018
 * Time: 02:34
 */
?>

<input type="hidden" name="hiddenIdOffre" value="<?php echo $idOffre ?>">
<div class="addcommand-view">
    <?php if (isset($_SESSION['offre']) AND !empty($_SESSION['offre'])) : ?>
    <div class="table-offre">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="scrollbar-deep-purple" style="overflow-y: auto; max-height: 380px; border: 1px solid #ddd;">
                        <input type="hidden" name="hiddenIdOffre" id="hiddenIdOffre" value="<?php echo $idOffre; ?>">
<!--                                            --><?php //$_SESSION['offre_detail'] = [] ;?>
                        <table class="table">
                            <thead>
                            <th>Designation</th>
                            <th>Famille</th>
                            <th>Prix unitaire</th>
                            <th>Quantité</th>
                            <th></th>
                            </thead>
                            <tbody id="tbodyProduitOffre">
                            <?php if (isset($_SESSION['offre_detail']) AND !empty($_SESSION['offre_detail'])) : ?>
                                <?php foreach ($_SESSION['offre_detail'] as $detail) {
                                    ?>
                                    <tr id="<?php echo 'line' . $detail['idProduit'] ?>">
                                        <td><?php echo $detail['produit'] ?></td>
                                        <td><?php echo $detail['famille'] ?></td>
                                        <td><?php echo $detail['prix'] ?></td>
                                        <td><?php echo $detail['quantite'] ?></td>
                                        <td><a href="javascript: removeProduitOffre(<?php echo $detail['idProduit'] ?>)"><i class="fa fa-remove" style="color: #EE0000;"></i></a></td>
                                    </tr>
                                    <?php
                                }
                            endif;
                            ?>
                            </tbody>
                        </table>
                        <div class="clear">
                            <div class="right" style="padding-right: 28px; padding-bottom: 10px;"><a data-toggle="modal" href="#addProduitOffre" onclick="loadProduitOffre()"><i class="fa fa-plus" style="color: #008000;"></i></a></div>
                        </div>
                    </div>
                    <div style="text-align: center; padding: 10px;">
                        <?php if (isset($_SESSION['offre_detail']) AND count($_SESSION['offre_detail']) > 0) : ?>
                        <button type="button" href="#confirmAddOffre" data-toggle="modal" class="btn btn-success" id="btnNextProcessAddOffre">Suivant <i class="fa fa-arrow-right"></i></button>
                        <?php else : ?>
                        <button type="button" disabled href="#confirmAddOffre" data-toggle="modal" class="btn btn-default disabled" id="btnNextProcessAddOffre">Suivant <i class="fa fa-arrow-right"></i></button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="addProduitOffre">
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

        <div class="modal" id="confirmAddOffre">
            <div class="modal-dialog small-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Information complementaire</h4>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" id="formEndProcessOffre">
                            <div class="form-group">
                                <label for="etat">Etat d'envoi: </label>
                                <select name="etat" id="etat" class="form-control">
                                    <option value="0">En cours</option>
                                    <option value="1">Validé</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label for="modePayement">Mode de payement: </label>
                                <select name="modePayement" id="modePayement" class="form-control">
                                    <option value="">Mode paiement</option>
                                    <?php foreach ($paiements as $paiement) {
                                        ?>
                                    <option value="<?php echo $paiement->paiid; ?>"><?php echo $paiement->pailibelle; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="clear">
                                <div class="right">
                                    <button type="submit" class="btn btn-primary" name="btnEndProcessOffre" id="btnEndProcessOffre">Terminer</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function () {
                    $('#formEndProcessOffre').submit(function (e) {

                        var valideFormEndProcess = true;

                        if ($('#etat').val() == '') {
                            fieldForm($('#etat'), 'Veillez choisir un etat pour l\'achat', 1);
                            valideFormEndProcess = false;
                        }else {
                            fieldForm($('#etat'), '', 0);
                        }

                        if ($('#modePayement').val() == '') {
                            fieldForm($('#modePayement'), 'Veillez choisir un mode de paiement pour l\'achat', 1);
                            valideFormEndProcess = false;
                        }else {
                            fieldForm($('#modePayement'), '', 0);
                        }

                        return valideFormEndProcess;
                    })
                })
            </script>
        </div>
    </div>
    <?php else: ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <?php if (isset($error) AND !$error) : ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <stron>Attention !</stron> Veillez entrer une quantité valide
                    </div>
                <?php endif; ?>
                <form action="" id="formOffre" method="post">
                    <div class="form-group">
                        <label for="fournisseurOffre">Fournisseur: </label>
                        <select name="fournisseurOffre" id="fournisseurOffre" class="form-control" required>
                            <option value="">Liste des fournisseurs</option>
                            <?php foreach ($fournisseurs as $fournisseur) {
                                ?>
                            <option value="<?php echo $fournisseur->fouid?>"><?php echo ucfirst($fournisseur->founom) . ' ' . ucfirst($fournisseur->fouprenom)?></option>

                            <?php
                            }
                            ?>
                        </select>
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <label for="dateOffre">Date d'achat: </label>
                        <input type="text" name="dateOffre" id="dateOffre" class="form-control" placeholder="date d'achat" required>
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <label for="dateLivraisonOffre">Date livraison: </label>
                        <input type="text" name="dateLivraisonOffre" id="dateLivraisonOffre" class="form-control" placeholder="date de livrason de la marchandise" required >
                        <span class="help-block"></span>
                    </div>
                    <div style="text-align: center; padding: 10px;">
                        <button type="submit" class="btn btn-success" name="btnBeginProcessOffre" id="btnBeginProcessOffre">Suivant <i class="fa fa-arrow-right"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                $('#dateLivraisonOffre').pickadate({
                    format: 'yyyy-mm-dd'
                });
                $('#dateOffre').pickadate({
                    format: 'yyyy-mm-dd'
                });

                $('#formOffre').submit(function (e) {
                    // e.preventDefault();

                    var valideForm = true;

                    if ($('#fournisseurOffre').val() == '' ) {
                        fieldForm($('#fournisseurOffre'), 'Veillez chosir un fournisseur', 1);
                        valideForm = false;
                    }else {
                        fieldForm($('#fournisseurOffre'), '', 0);
                    }
                    if ($('#dateOffre').val() == '' ) {
                        fieldForm($('#dateOffre'), 'Veillez chosir une date', 1);
                        valideForm = false;
                    }else {
                        fieldForm($('#dateOffre'), '', 0);
                    }
                    if ($('#dateLivraisonOffre').val() == '' ) {
                        fieldForm($('#dateLivraisonOffre'), 'Veillez chosir une date pour la livraison de la marchandise', 1);
                        valideForm = false;
                    }else {
                        fieldForm($('#dateLivraisonOffre'), '', 0);
                    }

                    return valideForm;
                })
            })
        </script>
    </div>
    <?php endif; ?>
</div>

