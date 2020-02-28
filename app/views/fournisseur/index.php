<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 01/11/2018
 * Time: 19:22
 */
?>

<div class="views-fournisseur">
    <div class="container-table">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="fresh-table toolbar-color-azure">

                        <div class="toolbar">
                            <?php if (in_array('701', $_SESSION['stkdroits'])) : ?>
                                <button data-toggle="modal" href="#addFournisseur" onclick="contentModalAdd()" class="btn btn-primary">Nouveau Fournisseur</button>
                            <?php else: ?>
                                <button disabled class="btn btn-primary disabled">Nouveau Fournisseur</button>
                            <?php endif; ?>
                            <!--                <button id="alertBtn" class="btn btn-default">Nouveau Fournisseur</button>-->
                        </div>

                        <input type="hidden" name="hiddenIdFournisseur" id="hiddenIdFournisseur">
                        <table id="fournisseur-table" class="table table-responsive table-bordered">
                            <thead>
                                <th data-field="n">#</th>
                                <th data-field="name">Nom et prénom</th>
                                <th data-field="contact">Contact</th>
                                <th data-field="city">Ville</th>
                                <th data-field="actions">Actions</th>
                            </thead>
                            <tbody id="bodyTableFournisseur">
                            <?php $i = 1; foreach ($fournisseurs as $fournisseur) : ?>
                                <tr>
                                    <td><?php echo $i ++ ; ?></td>
                                    <td><?php echo ucfirst($fournisseur->founom) . ' ' . ucfirst($fournisseur->fouprenom); ?></td>
                                    <td><?php echo $fournisseur->foutelephone . ' / ' . $fournisseur->fouportable ?></td>
                                    <td><?php echo $fournisseur->fouville ?></td>
                                    <td>
                                        <?php if (in_array('703', $_SESSION['stkdroits'])) : ?>
                                            <a data-toggle="modal" href="#editeFournisseur" onclick="laodForEditFournisseur(<?php echo $fournisseur->fouid; ?>); return false" title="editer"><i class="fa fa-edit" style="color: #05AE0E;"></i></a>
                                        <?php else: ?>
                                            <a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-edit"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>


        <!--        modal supprimer un Fournisseur-->
        <div class="modal" id="deletFournisseur">
            <div class="modal-dialog small-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Supprimer un Fournisseur</h4>
                    </div>
                    <div class="modal-body">
                        delete
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deleteFournisseur()">Supprimer</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </div>
        </div>

        <!--        modal editer un Fournisseur-->
        <div class="modal" id="editeFournisseur">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Editer un Fournisseur</h4>
                    </div>
                    <div class="modal-body">
                        editer
                    </div>
                </div>
            </div>
        </div>

        <!--        modal add fournisseur-->
        <div class="modal" id="addFournisseur">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Nouveau Fournisseur</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-form-AddFournisseur">
                            <div class="errorAdd"></div>
                            <form action="" method="post" id="formAddFournisseur">
                                <div class="form-group">
                                    <label for="nomAddFournisseur">Nom</label>
                                    <input type="text" name="nomAddFournisseur" id="nomAddFournisseur" class="form-control" placeholder="nom du fournisseur" required>
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                    <label for="prenomAddFournisseur">Prénom</label>
                                    <input type="text" name="prenomAddFournisseur" id="prenomAddFournisseur" class="form-control" placeholder="prénom du fournisseur" required>
                                    <span class="help-block"></span>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="telephone1AddFournisseur">Téléphone</label>
                                            <input type="text" name="telephone1AddFournisseur" id="telephone1AddFournisseur" value="698846669" class="form-control" placeholder="numéro de téléphone" required>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="telephone2AddFournisseur">Téléphone 2</label>
                                            <input type="text" name="telephone2AddFournisseur" id="telephone2AddFournisseur" class="form-control" required>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                                <label for="villeAddFournisseur">Ville</label>
                                            <input type="text" name="villeAddFournisseur" id="villeAddFournisseur" value="Yaoundé" class="form-control" placeholder="ville de residence" required>
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
                text: 'Un nouveau Fournisseur a été ajouté',
                image: 'assets/img/confirm.png'
            });
        </script>
        <?php
    }
    ?>
</div>