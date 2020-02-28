<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 04/11/2018
 * Time: 12:23
 */
?>
<div class="views-commande">
    <div class="container-table">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="trierpar">Trier par :</label>
                        <select name="trierpar" id="trierpar" class="form-control" onchange="trieAchat(this.value)">
                            <option value="">Trier</option>
                            <option value="1">Livrées</option>
                            <option value="2">En cours</option>
                            <option value="3">Annulée</option>
                            <option value="4">Suspendu</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="fresh-table toolbar-color-azure">

                        <div class="toolbar">
                            <?php if (in_array('701', $_SESSION['stkdroits'])) : ?>
                                <a href="achat/add" title="passer une commande" class="btn btn-round">Nouvelle commande</a>
                            <?php else: ?>
                                <button disabled class="btn btn-primary disabled">Nouvelle commande</button>
                            <?php endif; ?>
                            <!--                <button id="alertBtn" class="btn btn-default">Nouveau commande</button>-->
                        </div>

                        <input type="hidden" name="hiddenIdcommande" id="hiddenIdcommande">
                        <table id="commande-table" class="table table-responsive table-bordered">
                            <thead>
                                <th data-field="num">Num commande</th>
                                <th data-field="contact">Client</th>
                                <th data-field="date">Date</th>
                                <th data-field="state">Etat</th>
                                <th data-field="payement">Payement</th>
                                <th data-field="actions" data-formatter="operateFormatter" data-events="operateEvents">Actions</th>
                            </thead>
                            <tbody id="bodyTableOffre">
                                <?php echo $tbodyTable; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>


        <!--        modal supprimer un commande-->
        <div class="modal" id="deletcommande">
            <div class="modal-dialog small-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Supprimer un commande</h4>
                    </div>
                    <div class="modal-body">
                        delete
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deletecommande()">Supprimer</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </div>
        </div>

        <!--        modal editer un commande-->
        <div class="modal" id="editOffre">
            <div class="modal-dialog small-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Editer une commande</h4>
                    </div>
                    <div class="modal-body">
                        editer
                    </div>
                </div>
            </div>
        </div>

        <!--        modal add commande-->
        <div class="modal" id="showCommande">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Nouvelle commande</h4>
                    </div>
                    <div class="modal-body">
                        <div class="detail">
                            <div>
                                <span class="label">Fournisseur: </span> -
                            </div>
                            <div>
                                <span class="label">Date: </span>-
                            </div>
                        </div>
                        <div class="produits">
                            <div class="produit">
                                <div class="name">
                                    <span class="label">Produit: </span>------
                                </div>
                                <br>
                                <div class="prix">
                                    <span class="label">Prix unitaire: </span>---
                                </div>
                                <br>
                                <div class="quantite">
                                    <span class="label">Quantité: </span>--
                                </div>
                                <br>
                                <div class="quantite">
                                    <span class="label">Prix total: </span>
                                    <span style="font-style: italic;">15000</span>
                                </div>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php
    if (isset($success) AND $success == true) {
        ?>
        <script>
            $.gritter.add({
                title: 'Félicitation',
                text: 'Un nouveau commande a été ajouté',
                image: 'assets/img/confirm.png'
            });
        </script>
        <?php
    }
    ?>

    <?php
    if (isset($_SESSION['offre']['edit']) AND $_SESSION['offre']['edit'] == true) {
        ?>
        <script>
            $.gritter.add({
                title: 'Mis à jour',
                text: 'Commande d\'achat mis à jour',
                image: 'assets/img/confirm.png'
            });
        </script>
        <?php
        $_SESSION['offre']['edit'] = FALSE;
        unset($_SESSION['offre']['edit']);
    }
    ?>
</div>