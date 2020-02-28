<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 31/10/2018
 * Time: 17:39
 */
?>

<div class="views-unite">
    <div class="container-table">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="fresh-table toolbar-color-azure">

                        <div class="toolbar">
                            <?php if (in_array('111', $_SESSION['stkdroits'])) : ?>
                            <button data-toggle="modal" href="#addUnite" onclick="loadModalAdd()" class="btn btn-primary">Nouvelle unité de mesure</button>
                            <?php else: ?>
                                <button data-toggle="modal" class="btn btn-primary disabled" disabled>Nouvelle unité</button>
                            <?php endif; ?>
                            <!--                <button id="alertBtn" class="btn btn-default">Nouveau unite</button>-->
                        </div>

                        <input type="hidden" name="hiddenIdUnite" id="hiddenIdUnite">
                        <table id="unite-table" class="table table-responsive table-bordered">
                            <thead>
                                <th data-field="id">#</th>
                                <th data-field="name" data-sortable="true">Designation</th>
                                <th data-field="abv" data-sortable="true">Abreviation</th>
                                <th data-field="actions" data-formatter="operateFormatter" data-events="operateEvents">Actions</th>
                            </thead>
                            <tbody id="bodyTableUnite">
                            <?php $i = 1; foreach ($unites as $unite) : ?>
                                <tr>
                                    <td><?php echo $unite->uniid; ?></td>
                                    <td><?php echo $unite->unilibelle ?></td>
                                    <td><?php echo $unite->uniabv ?></td>
                                    <td>
                                        <?php if (in_array('113', $_SESSION['stkdroits'])) : ?>
                                            <a data-toggle="modal" href="#editeUnite" onclick="laodForEditUnite(<?php echo $unite->uniid; ?>); return false" title="editer"><i class="fa fa-edit" style="color: #05AE0E;"></i></a>
                                        <?php else: ?>
                                            <a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-edit"></i></a>
                                        <?php endif; ?>

                                        <?php if (in_array('112', $_SESSION['stkdroits'])) : ?>
                                            <a data-toggle="modal" href="#deletUnite" onclick="laodDataForDeleteUnite(<?php echo $unite->uniid; ?>); return false;" title="supprimer"><i class="fa fa-remove" style="color: #FF3B30;"></i></a>
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


        <!--        modal supprimer un unite-->
        <div class="modal" id="deletUnite">
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deletUnite()">Supprimer</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </div>
        </div>

        <!--        modal editer un unite-->
        <div class="modal" id="editeUnite">
            <div class="modal-dialog small-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Editer une unité</h4>
                    </div>
                    <div class="modal-body">

                        <div class="container-form-EditUnite">
                            <form method="post" id="formEditUnite">
                                <div class="errorEdit"></div>
                                <input type="hidden" name="idUniteEdit">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="designationEditUnite">Designation</label>
                                            <input type="text" name="designationEditUnite" id="designationEditUnite" class="form-control" placeholder="nom de l'unté" required>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="abvEditUnite">Abreviation</label>
                                            <input type="text" name="abvEditUnite" id="abvEditUnite" class="form-control" placeholder="l'abreviation de l'unté" required>
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

        <div class="modal" id="addUnite">
            <div class="modal-dialog small-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Ajouter une novelle unité</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-form-EditUnite">
                            <form action="" method="post" id="formAddUnite">
                                <div class="errorAdd"></div>
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="designationAddUnite">Designation</label>
                                            <input type="text" name="designationAddUnite" id="designationAddUnite" value="Gramme" class="form-control" placeholder="nom de l'unté">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="abvAddUnite">Abreviation</label>
                                            <input type="text" name="abvAddUnite" id="abvAddUnite" value="Gramme" class="form-control" placeholder="l'abreviation de l'unté" >
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
                text: 'Un nouveau unite a été ajouté',
                image: 'assets/img/confirm.png'
            });
        </script>
        <?php
    }
    ?>
</div>
