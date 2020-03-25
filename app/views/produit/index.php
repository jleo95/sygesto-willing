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
                        <select name="triProduit" id="triProduit" class="form-control" onchange="trieProduit(this.value)">
                            <option value="">Trier par :</option>
                            <option value="1">Famille</option>
                            <option value="2">Unite mésure</option>
                            <option value="3">Tous</option>
                        </select>
                    </div>

                </div>
                <div class="col-md-3">
                    <div class="form-group">

                        <br>
                        <a href="produit/imprimer" class="btn btn-default text-primary" target="_blank" style="font-size: 22px;" title="imprimer liste des produits"><i class="fa fa-print"></i></a>
                    </div>

                </div>
                <div class="col-md-12">
                    <div class="fresh-table toolbar-color-azure">

                        <div class="toolbar">
                            <?php if (in_array('101', $_SESSION['stkdroits'])) : ?>
                                <button onclick="contentModalAdd()" class="btn btn-primary">Nouveau produit</button>
                            <?php else: ?>
                                <button disabled class="btn btn-primary disabled">Nouveau produit</button>
                            <?php endif; ?>
                            <!--                <button id="alertBtn" class="btn btn-default">Nouveau produit</button>-->
                        </div>

                        <input type="hidden" name="hiddenIdProduit" id="hiddenIdProduit">
                        <table id="produit-table" class="table table-responsive table-bordered">
                            <thead>
                            <th data-field="id" data-sortable="true">Ref.</th>
                            <!--                    <th data-field="id" data-sortable="false">ID</th>-->
                            <th data-field="name" data-sortable="false">Designation</th>

                            <th data-field="family" data-sortable="true">Famille</th>

                            <th data-field="unite" data-sortable="true">Unité M.</th>

                            <th data-field="actions" data-formatter="operateFormatter" data-events="operateEvents">Actions</th>
                            </thead>

                            <tbody id="bodyTableProduit">
                            <?php echo $tbodyProduit; ?>
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
                        <h4 class="modal-title">Infos produit</h4>
                    </div>
                    <div class="modal-body">
                        show infos produit
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
                        <h4 class="modal-title">Supprimer un produit</h4>
                    </div>
                    <div class="modal-body">
                        delete
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deleteProduit()">Supprimer</button>
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
                        <h4 class="modal-title">Editer un produit</h4>
                    </div>
                    <div class="modal-body">
                        editer
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="addProuit">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Nouveau produit</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-form-AddProduit">
                            <div class="errorAdd"></div>
                            <form action="" method="post" id="formAddProduit">
                                <div class="form-group">
                                    <label for="designAddProduit">Designation</label>
                                    <input type="text" name="designAddProduit" id="designAddProduit" class="form-control" placeholder="nom du produit" required>
                                    <span class="help-block"></span>
                                </div>

                                <div class="form-group">
                                    <label for="fournisseurAddProduit">Fournisseur</label>
                                    <select name="fournisseurAddProduit" id="fournisseurAddProduit" class="form-control">
                                        <option value="">Fournisseurs</option>
                                        <?php foreach ($fournisseurs as $f) : ?>
                                            <option value="<?php echo $f->fouid; ?>"><?php echo ucfirst($f->founom) . ' ' . ucfirst($f->fouprenom) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="help-block"></span>
                                </div>



                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nbblogAddProduit">Nbre de produit par blog</label>
                                            <input type="text" name="nbblogAddProduit" id="nbblogAddProduit" class="form-control" placeholder="nombre de produit par blog">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="peremptionAddProduit">Date de peremption</label>
                                            <input type="text" name="peremptionAddProduit" id="peremptionAddProduit" class="form-control" placeholder="le date d'expiration d'un produit" required>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="familleAddProduit">Famille</label>
                                            <select name="familleAddProduit" id="familleAddProduit" class="form-control">
                                                <option value="">Famille du produit</option>
                                                <?php foreach ($familles as $famille) : ?>
                                                    <option value="<?php echo $famille->famid; ?>"><?php echo $famille->famlibelle; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="uniteAddProduit">Unité de mesure</label>
                                            <select name="uniteAddProduit" id="uniteAddProduit" class="form-control" required>
                                                <option value="">Unité de mesure</option>
                                                <?php foreach ($unites as $unite) : ?>
                                                    <option value="<?php echo $unite->uniid; ?>"><?php echo $unite->unilibelle ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="seuilAddProduit">Seuil d'alerte (stock)</label>
                                            <input type="text" name="seuilAddProduit" id="seuilAddProduit" class="form-control" placeholder="par exemple 5">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
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
