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
                        <table id="produit-table" class="table table-responsive table-bordered">
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
                        <div class="container-form-AddProduit">
                            <div class="errorAdd"></div>
                            <form action="" method="post" id="formAddProduit">
                               
                               

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
</div>
