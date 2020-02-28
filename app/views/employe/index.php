<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 02/11/2018
 * Time: 13:25
 */
?>

<div class="views-employe">
    <div class="container-table">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="fresh-table toolbar-color-azure">

                        <div class="toolbar">
                            <?php if (in_array('801', $_SESSION['stkdroits'])) : ?>
                                <button data-toggle="modal" href="#addEmploye" onclick="contentModalAdd()" class="btn btn-primary">Nouveau Employe</button>
                            <?php else: ?>
                                <button disabled class="btn btn-primary disabled">Nouveau Employe</button>
                            <?php endif; ?>
                            <!--                <button id="alertBtn" class="btn btn-default">Nouveau Employe</button>-->
                        </div>

                        <input type="hidden" name="hiddenIdEmploye" id="hiddenIdEmploye">
                        <table id="employe-table" class="table table-responsive table-bordered">
                            <thead>
                                <th data-field="n">#</th>
                                <th data-field="name">Nom et prénom</th>
                                <th data-field="contact">Contact</th>
                                <th data-field="sexe">Sexe</th>
                                <th data-field="residence">Residence</th>
                                <th data-field="actions">Actions</th>
                            </thead>
                            <tbody id="bodyTableEmploye">
                            <?php $i = 1; foreach ($employes as $employe) : ?>
                                <tr>
                                    <td><?php echo $i ++ ; ?></td>
                                    <td><?php echo ucfirst($employe->empnom) . ' ' . ucfirst($employe->empprenom); ?></td>
                                    <td><?php
                                        echo $employe->emptelephone ;
                                        if ($employe->empportable != '') {
                                            echo ' / ' . $employe->empportable;
                                        }
                                        ?></td>
                                    <td><?php echo (intval($employe->empsexe) == 1) ? 'Homme' : 'Femme' ?></td>
                                    <td><?php echo ucfirst($employe->empresidence) ?></td>
                                    <td>
                                        <?php if (in_array('803', $_SESSION['stkdroits'])) : ?>
                                            <a data-toggle="modal" href="#editeEmploye" onclick="laodForEditEmploye(<?php echo $employe->empid; ?>); return false" title="editer"><i class="fa fa-edit" style="color: #05AE0E;"></i></a>
                                        <?php else: ?>
                                            <a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-edit"></i></a>
                                        <?php endif; ?>

                                        <?php if (in_array('804', $_SESSION['stkdroits'])) : ?>
                                            <a data-toggle="modal" href="#deletEmploye" onclick="laodDataForDeleteEmploye(<?php echo $employe->empid; ?>); return false;" title="supprimer"><i class="fa fa-remove" style="color: #FF3B30;"></i></a>
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


        <!--        modal supprimer un Employe-->
        <div class="modal" id="deletEmploye">
            <div class="modal-dialog small-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Supprimer un Employe</h4>
                    </div>
                    <div class="modal-body">
                        delete
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deleteEmploye()">Supprimer</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </div>
        </div>

        <!--        modal editer un Employe-->
        <div class="modal" id="editeEmploye">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Editer un Employe</h4>
                    </div>
                    <div class="modal-body">
                        editer
                    </div>
                </div>
            </div>
        </div>

        <!--        modal add employe-->
        <div class="modal" id="addEmploye">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Nouveau Employe</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-form-AddEmploye">
                            <div class="errorAdd"></div>
                            <form action="" method="post" id="formAddEmploye">
                                <div class="form-group">
                                    <label for="nomAddEmploye">Nom du client</label>
                                    <input type="text" name="nomAddEmploye" id="nomAddEmploye" value="Du Pond" class="form-control" placeholder="nom du employe" required>
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                    <label for="prenomAddEmploye">Prénom</label>
                                    <input type="text" name="prenomAddEmploye" id="prenomAddEmploye" value="Bois" class="form-control" placeholder="prénom du employe">
                                    <span class="help-block"></span>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="telephone1AddEmploye">Téléphone</label>
                                            <input type="text" name="telephone1AddEmploye" id="telephone1AddEmploye" value="698846669" class="form-control" placeholder="numéro de téléphone" required>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="telephone2AddEmploye">Téléphone 2</label>
                                            <input type="text" name="telephone2AddEmploye" id="telephone2AddEmploye" class="form-control">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="villeAddEmploye">Résidence</label>
                                            <input type="text" name="villeAddEmploye" id="villeAddEmploye" value="Yaoundé" class="form-control" placeholder="ville de residence" required>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Sexe: </label>&nbsp;&nbsp;&nbsp;
                                            <div style="padding-left: 32px;">
                                                <label for="hommeAddEmploye" style="cursor: pointer;"><input type="radio" name="sexeAddEmploye" id="hommeAddEmploye" value="1" required> <i class="fa fa-male" style="font-size: 22px"></i></label>&nbsp;&nbsp;

                                                <label for="femmeAddEmploye" style="cursor: pointer;"><input type="radio" name="sexeAddEmploye" id="femmeAddEmploye" value="0" required> <i class="fa fa-female" style="font-size: 22px"></i></label>
                                            </div>
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
                text: 'Un nouveau Employe a été ajouté',
                image: 'assets/img/confirm.png'
            });
        </script>
        <?php
    }
    ?>
</div>
