<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 02/11/2018
 * Time: 02:28
 */
?>

<div class="views-client">
    <div class="container-table">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="fresh-table toolbar-color-azure">

                        <div class="toolbar">
                            <?php if (in_array('801', $_SESSION['stkdroits'])) : ?>
                                <button data-toggle="modal" href="#addClient" onclick="contentModalAdd()" class="btn btn-primary">Nouveau Client</button>
                            <?php else: ?>
                                <button disabled class="btn btn-primary disabled">Nouveau Client</button>
                            <?php endif; ?>
                            <!--                <button id="alertBtn" class="btn btn-default">Nouveau Client</button>-->
                        </div>

                        <input type="hidden" name="hiddenIdClient" id="hiddenIdClient">
                        <table id="client-table" class="table table-responsive table-bordered">
                            <thead>
                            <th data-field="n">#</th>
                            <th data-field="name">Nom et prénom</th>
                            <th data-field="contact">Contact</th>
                            <th data-field="city">Ville</th>
                            <th data-field="remise">Remise (%)</th>
                            <th data-field="actions">Actions</th>
                            </thead>
                            <tbody id="bodyTableClient">
                            <?php echo $tableClient; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>


        <!--        modal supprimer un Client-->
        <div class="modal" id="deletClient">
            <div class="modal-dialog small-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Supprimer un Client</h4>
                    </div>
                    <div class="modal-body">
                        delete
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deleteClient()">Supprimer</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </div>
        </div>

        <!--        modal editer un Client-->
        <div class="modal" id="editeClient">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Editer un Client</h4>
                    </div>
                    <div class="modal-body">
                        editer
                    </div>
                </div>
            </div>
        </div>

        <!--        modal add client-->
        <div class="modal" id="addClient">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Nouveau Client</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-form-AddClient">
                            <div class="errorAdd"></div>
                            <form action="" method="post" id="formAddClient">
                                <div class="form-group">
                                    <label for="nomAddClient">Nom</label>
                                    <input type="text" name="nomAddClient" id="nomAddClient" value="Du Pond" class="form-control" placeholder="nom du client" required>
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                    <label for="prenomAddClient">Prénom</label>
                                    <input type="text" name="prenomAddClient" id="prenomAddClient" value="Bois" class="form-control" placeholder="prénom du client">
                                    <span class="help-block"></span>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="telephone1AddClient">Téléphone</label>
                                            <input type="text" name="telephone1AddClient" id="telephone1AddClient" value="698846669" class="form-control" placeholder="numéro de téléphone" required>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="telephone2AddClient">Téléphone 2</label>
                                            <input type="text" name="telephone2AddClient" id="telephone2AddClient" class="form-control">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="villeAddClient">Ville</label>
                                            <input type="text" name="villeAddClient" id="villeAddClient" value="Yaoundé" class="form-control" placeholder="ville de residence" required>
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
                text: 'Un nouveau Client a été ajouté',
                image: 'assets/img/confirm.png'
            });
        </script>
        <?php
    }
    ?>
</div>
