<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 28/10/2018
 * Time: 17:51
 */
?>


<div class="views-produit">
    <div class="container-table">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="triProduit">Trier par: </label>
                        <select name="triProduit" id="triProduit" class="form-control" onchange="trieOffshore(this.value)">
                            <option value="">Trier</option>
                            <option value="3">Déjà terminé</option>
                            <option value="1">En cours</option>
                            <option value="2">A venir</option>
                            <option value="4">Tous</option>
                        </select>
                    </div>

                </div>
                <div class="col-md-3">
                    <div class="form-group">

                        <br>
                        <a href="offshore/imprimer" class="btn btn-default text-primary" target="_blank" style="font-size: 22px;" title="imprimer liste des produits"><i class="fa fa-print"></i></a>
                    </div>

                </div>
                <div class="col-md-12">
                    <div class="fresh-table toolbar-color-azure">

                        <div class="toolbar">
                            <?php if (in_array('101', $_SESSION['stkdroits'])) : ?>
                                <button onclick="contentModalAdd()" class="btn btn-primary">Nouvel offshore</button>
                            <?php else: ?>
                                <button disabled class="btn btn-primary disabled">Nouvel offshore</button>
                            <?php endif; ?>
                            <!--                <button id="alertBtn" class="btn btn-default">Nouveau produit</button>-->
                        </div>

                        <input type="hidden" name="hiddenIdProduit" id="hiddenIdProduit">
                        <table id="offshore-table" class="table table-responsive table-bordered">
                            <thead>
                            <th data-field="id" data-sortable="true">Ref.</th>
                            <!--                    <th data-field="id" data-sortable="false">ID</th>-->
                            <th data-field="description" data-sortable="false">Designation</th>

                            <th data-field="responsable" data-sortable="true">Responsable</th>

                            <th data-field="Client" data-sortable="true">Client</th>

                            <th data-field="datedebut" data-sortable="true">Date debut</th>

                            <th data-field="jourrestant" data-sortable="true">Jours restants</th>

                            <th data-field="pourcentage" data-sortable="true">% chargement</th>

                            <th data-field="actions" data-formatter="operateFormatter" data-events="operateEvents">Actions</th>
                            </thead>

                            <tbody id="bodyTableProduit">
                            <?php echo $tbodyOffshore; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>


        <!--        modal infos produit-->
        <div class="modal" id="showAllInfosProduit">
            <div class="modal-dialog moyen-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Infos offshore</h4>
                    </div>
                    <div class="modal-body">
                        show infos offshore
                    </div>
                </div>
            </div>
        </div>

        <!--        modal supprimer un produit-->
        <div class="modal" id="deletProuit">
            <div class="modal-dialog small-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Suspendre un offshore</h4>
                    </div>
                    <div class="modal-body">
                        Suspendre
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deleteProduit()">Suspendre</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </div>
        </div>

        <!--        modal editer un produit-->
        <div class="modal" id="editeProduit">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Editer un offshore</h4>
                    </div>
                    <div class="modal-body">
                        Editer
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="addOffshore">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Nouvel offshore</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-form-AddOffshore">
                            <div class="errorAdd"></div>
                            <form action="" method="post" id="formAddOffshore">
                                <div class="form-group">
                                    <label for="descriptionAddOffshore">Description</label>
                                    <input type="text" name="descriptionAddOffshore" id="descriptionAddOffshore" class="form-control" placeholder="Description du offshore" required>
                                    <span class="help-block"></span>
                                </div>

                                <div class="form-group">
                                    <label for="responsableAddOffshore">Responsable</label>
                                    <select name="responsableAddOffshore" id="responsableAddOffshore" class="form-control">
                                        <option value="" >Choisir un Responsable</option>
                                        <?php foreach ($employes as $employe) {
                                            ?>

                                            <option value="<?php echo $employe->empid; ?>"><?php echo ucfirst($employe->empnom) . ' ' . ucfirst($employe->empprenom) ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <span class="help-block"></span>
                                </div>


                                <div class="form-group">

                                    <div class="col-md-6">
                                        <label for="dateDebutAddOffshore">Date de debut</label>
                                        <input type="text" name="dateDebutAddOffshore" id="dateDebutAddOffshore" class="form-control" placeholder="Date de début offshore" required>
                                        <span class="help-block"></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="dateFinAddOffshore">Date de fin</label>
                                        <input type="text" name="dateFinAddOffshore" id="dateFinAddOffshore" class="form-control" placeholder="Date de fin offshore" disabled required>
                                        <span class="help-block"></span>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label for="clientAddOffshore">Client</label>
                                    <select name="clientAddOffshore" id="clientAddOffshore" class="form-control">
                                        <?php foreach ($clients as $client) {
                                            ?>
                                            <option value="" >Choisir un client</option>

                                            <option value="<?php echo $client->cliid; ?>"><?php echo ucfirst($client->clinom) . ' ' . ucfirst($client->cliprenom) ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <span class="help-block"></span>
                                </div>


                                <div class="row">

                                </div>

                                <div class="clear">
                                    <div class="btn-group right">
                                        <button type="submit" class="btn btn-primary right" id="btnSubFormAdd">Ajouter</button>
                                    </div>
                                </div>
                            </form>
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
                text: 'Un nouveau produit a été ajouté',
                image: 'assets/img/confirm.png'
            });
        </script>
        <?php
    }
    ?>
    <?php
    if (isset($_SESSION['offshoreAddError']) AND $_SESSION['offshoreAddError'] === 0) {
        ?>
        <script>
            $.gritter.add({
                title: 'Félicitation',
                text: 'Vous avez ajouté un nouveau offshore',
                image: 'assets/img/confirm.png'
            });
        </script>
        <?php
        unset($_SESSION['offshoreAddError']);
    }
    ?>
    <?php
//    var_dump($_SESSION['error']);
    if (isset($_SESSION['offshoreAddError']) AND $_SESSION['offshoreAddError'] === 1) {
        ?>
        <script>
            $.gritter.add({
                title: 'Echec',
                text: 'Offshore non ajouté. Veillez ressayer plutard',
                image: 'assets/img/un.png'
            });
        </script>
        <?php
        unset($_SESSION['offshoreAddError']);
    }
    ?>
</div>
