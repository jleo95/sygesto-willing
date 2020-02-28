<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 31/10/2018
 * Time: 17:39
 */
?>

<div class="views-Marque">
    <div class="container-table">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="fresh-table toolbar-color-azure">

                        <div class="toolbar">
                            <?php if (in_array('111', $_SESSION['stkdroits'])) : ?>
                                <button data-toggle="modal" href="#addMarque" onclick="loadModalAdd()" class="btn btn-primary">Nouveau Marque</button>
                            <?php else: ?>
                                <button data-toggle="modal" class="btn btn-primary disabled" disabled>Nouveau Marque</button>
                            <?php endif; ?>
                            <!--                <button id="alertBtn" class="btn btn-default">Nouveau Marque</button>-->
                        </div>

                        <input type="hidden" name="hiddenIdMarque" id="hiddenIdMarque">
                        <table id="marque-table" class="table table-responsive table-bordered">
                            <thead>
                                <th data-field="n">#</th>
                                <th data-field="name" data-sortable="true">Designation</th>
                                <th data-field="desc" data-sortable="true">Description</th>
                                <th data-field="actions">Actions</th>
                            </thead>
                            <tbody id="bodyTableMarque">
                            <?php $i = 1; foreach ($marques as $marque) : ?>
                                <tr>
                                    <td><?php echo $i ++ ; ?></td>
                                    <td><?php echo $marque->marlibelle ?></td>
                                    <td><?php echo $marque->mardescription ?></td>
                                    <td>
                                        <?php if (in_array('113', $_SESSION['stkdroits'])) : ?>
                                            <a data-toggle="modal" href="#editeMarque" onclick="laodForEditeMarque(<?php echo $marque->marid; ?>); return false;" title="editer"><i class="fa fa-edit" style="color: #05AE0E;"></i></a>
                                        <?php else: ?>
                                            <a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-edit"></i></a>
                                        <?php endif; ?>

                                        <?php if (in_array('112', $_SESSION['stkdroits'])) : ?>
                                            <a data-toggle="modal" href="#deletMarque" onclick="laodDataForDeleteMarque(<?php echo $marque->marid; ?>); return false;" title="supprimer"><i class="fa fa-remove" style="color: #FF3B30;"></i></a>
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


        <!--        modal supprimer un Marque-->
        <div class="modal" id="deletMarque">
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deletMarque()">Supprimer</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </div>
        </div>

        <!--        modal editer un Marque-->
        <div class="modal" id="editeMarque">
            <div class="modal-dialog small-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Editer une marque</h4>
                    </div>
                    <div class="modal-body">
                        editer
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="addMarque">
            <div class="modal-dialog small-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Ajouter une marque</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-form-EditMarque">
                            <form action="" method="post" id="formAddMarque">
                                <div class="errorAdd"></div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="designationAddMarque">Designation</label>
                                            <input type="text" name="designationAddMarque" id="designationAddMarque" value="Gramme" class="form-control" placeholder="nom de l'unté">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="descriptionAddMarque">Description</label>
                                            <textarea name="descriptionAddMarque" id="descriptionAddMarque" cols="30" rows="10" placeholder="une description" class="form-control" style="height: 90px"></textarea>
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
                text: 'Un nouveau Marque a été ajouté',
                image: 'assets/img/confirm.png'
            });
        </script>
        <?php
    }
    ?>
</div>
