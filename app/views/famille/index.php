<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 31/10/2018
 * Time: 17:39
 */
?>

<div class="views-famille">
    <div class="container-table">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="fresh-table toolbar-color-azure">

                        <div class="toolbar">
                            <?php if (in_array('111', $_SESSION['stkdroits'])) : ?>
                            <button data-toggle="modal" href="#addFamille" onclick="loadModalAdd()" class="btn btn-primary">Nouvelle famille</button>
                            <?php else: ?>
                                <button data-toggle="modal" class="btn btn-primary disabled" disabled>Nouvelle unité</button>
                            <?php endif; ?>
                            <!--                <button id="alertBtn" class="btn btn-default">Nouveau famille</button>-->
                        </div>

                        <input type="hidden" name="hiddenIdFamille" id="hiddenIdFamille">
                        <table id="famille-table" class="table table-responsive table-bordered">
                            <thead>
                                <th data-field="id" style="width: 10%">id</th>
                                <th data-field="name" data-sortable="true" style="width: 90%">Designation</th>
                                <th data-field="actions" data-formatter="operateFormatter" data-events="operateEvents" style="width: 10%">Actions</th>
                            </thead>
                            <tbody id="bodyTableFamille">
                            <?php $i = 1; foreach ($familles as $famille) : ?>
                                <tr>
                                    <td><?php echo $famille->famid ; ?></td>
                                    <td><?php echo $famille->famlibelle ?></td>
                                    <td>
                                        <?php if (in_array('113', $_SESSION['stkdroits'])) : ?>
                                            <a data-toggle="modal" href="#editeFamille" onclick="laodForEditFamille(<?php echo $famille->famid; ?>); return false" title="editer"><i class="fa fa-edit" style="color: #05AE0E;"></i></a>
                                        <?php else: ?>
                                            <a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-edit"></i></a>
                                        <?php endif; ?>

                                        <?php if (in_array('112', $_SESSION['stkdroits'])) : ?>
                                            <a data-toggle="modal" href="#deletFamille" onclick="laodDataForDeleteFamille(<?php echo $famille->famid; ?>); return false;" title="supprimer"><i class="fa fa-remove" style="color: #FF3B30;"></i></a>
                                        <?php else: ?>
                                            <a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-remove"></i></a>
                                        <?php endif;?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>


        <!--        modal supprimer un famille-->
        <div class="modal" id="deletFamille">
            <div class="modal-dialog small-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Supprimer l'unité</h4>
                    </div>
                    <div class="modal-body">
                        delete
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deletFamille()">Supprimer</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </div>
        </div>

        <!--        modal editer un famille-->
        <div class="modal" id="editeFamille">
            <div class="modal-dialog small-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Editer une famille</h4>
                    </div>
                    <div class="modal-body">

                        <div class="container-form-EditFamille">
                            <form method="post" id="formEditFamille">
                                <div class="errorEdit"></div>
                                <input type="hidden" name="idFamilleEdit">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="designationEditFamille">Designation</label>
                                            <input type="text" name="designationEditFamille" id="designationEditFamille" class="form-control" placeholder="nom de l'unté" required>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="abvEditFamille">Abreviation</label>
                                            <input type="text" name="abvEditFamille" id="abvEditFamille" class="form-control" placeholder="l'abreviation de l'unté" required>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="clear">
                                    <div class="btn-group right">
                                        <button type="submit" class="btn btn-primary right" id="btnSubFormEdit">Modifier</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="addFamille">
            <div class="modal-dialog small-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Ajouter une nouvelle unité</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-form-EditFamille">
                            <form action="" method="post" id="formAddFamille">
                                add famille
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
                text: 'Un nouveau famille a été ajouté',
                image: 'assets/img/confirm.png'
            });
        </script>
        <?php
    }
    ?>
</div>
