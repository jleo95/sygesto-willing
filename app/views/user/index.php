<?php
/**
 * Created by PhpStorm.
 * User: LEOBA / Willing
 * Date: 14/11/2018
 * Time: 14:39
 */
?>

<div class="views-user">
    <div class="container-table">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="fresh-table toolbar-color-azure">

                        <div class="toolbar">
                            <?php if (in_array('801', $_SESSION['stkdroits'])) : ?>
                                <button data-toggle="modal" href="#addUser" onclick="contentModalAdd()" class="btn btn-primary">Nouvelle utilisateur</button>
                            <?php else: ?>
                                <button disabled class="btn btn-primary disabled">Nouveau User</button>
                            <?php endif; ?>
                            <!--                <button id="alertBtn" class="btn btn-default">Nouveau User</button>-->
                        </div>

                        <input type="hidden" name="hiddenIdUser" id="hiddenIdUser">
                        <table id="user-table" class="table table-responsive table-bordered">
                            <thead>
                                <th data-field="id">id</th>
                                <th data-field="name">Nom d'utilisateur</th>
                                <th data-field="profile">Profile</th>
                                <th data-field="state">Status</th>
                                <th data-field="actions" data-formatter="operateFormatter" data-events="operateEvents">Actions</th>
                            </thead>
                            <tbody id="bodyTableUser">
                            <?php $i = 1; foreach ($users as $user) : ?>
                                <tr>
                                    <td><?php echo $user->useid ; ?></td>
                                    <td><?php echo $user->uselogin; ?></td>
                                    <td><?php echo $user->prflibelle ?></td>
                                    <td><span style="font-size: 17px"><?php echo ($user->useverouiller == 1) ? '<i class="fa fa-lock text-danger"></i>' : '<i class="fa fa-key text-success"></i>' ?></span></td>
                                    <td>
                                        <?php if (in_array('611', $_SESSION['stkdroits'])) : ?>
                                            <a data-toggle="modal" href="#editeUser" onclick="laodForEditUser(<?php echo $user->useid; ?>); return false" title="editer"><i class="fa fa-edit" style="color: #05AE0E;"></i></a>
                                        <?php else: ?>
                                            <a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-edit"></i></a>
                                        <?php endif; ?>

                                        <?php if (in_array('610', $_SESSION['stkdroits'])) : ?>
                                            <a data-toggle="modal" href="#deletUser" onclick="laodDataForDeleteUser(<?php echo $user->useid; ?>); return false;" title="supprimer"><i class="fa fa-remove" style="color: #FF3B30;"></i></a>
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


        <!--        modal supprimer un User-->
        <div class="modal" id="deletUser">
            <div class="modal-dialog small-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Supprimer un User</h4>
                    </div>
                    <div class="modal-body">
                        delete
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deleteUser()">Verouiller</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </div>
        </div>

        <!--        modal editer un User-->
        <div class="modal" id="editeUser">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Editer un User</h4>
                    </div>
                    <div class="modal-body">
                        editer
                    </div>
                </div>
            </div>
        </div>

        <!--        modal add user-->
        <div class="modal" id="addUser">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Nouvelle utilisateur</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-form-AddUser">
                            <div class="errorAdd"></div>
                            <form action="" method="post" id="formAddUser">
                                <div class="form-group">
                                    <label for="nomAddUser">Nom d'utilisateur</label>
                                    <input type="text" name="nomAddUser" id="nomAddUser" value="Du Pond" class="form-control" placeholder="nom du user" required>
                                    <span class="help-block"></span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mdpAddUser">Mot de passe</label>
                                            <input type="text" name="mdpAddUser" id="mdpAddUser" class="form-control" placeholder="mot de passe">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mdp2AddUser">Confirmation du mot de passe</label>
                                            <input type="text" name="mdp2AddUser" id="mdp2AddUser" class="form-control" placeholder="confirmer le mot de passe">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="profileAddUser">Profile</label>
                                            <select name="profileAddUser" id="profileAddUser" class="form-control">
                                                <option value="">Profile</option>
                                                <?php foreach ($profiles as $profile) {
                                                    ?>
                                                <option value="<?php echo $profile->prfid ?>"><?php echo ucfirst($profile->prflibelle); ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="employerAddUser">Employer</label>
                                            <select name="employerAddUser" id="employerAddUser" class="form-control">
                                                <option value="">Liste des emplyer</option>
                                                <?php foreach ($employes as $employe) {
                                                    ?>
                                                    <option value="<?php echo $employe->empid; ?>"><?php echo ucfirst($employe->empnom) . ' ' . ucfirst($employe->empprenom) ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
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
    if (isset($_SESSION['stkedit'] ) AND $_SESSION['stkedit']  == true) {
        ?>
        <script>
            $.gritter.add({
                title: 'Mis à jour',
                text: 'Utilisateur editer',
                image: 'assets/img/confirm.png'
            });
        </script>
        <?php
        unset($_SESSION['stkedit']);
    }
    ?>
</div>
