<?php
/**
 * Created by PhpStorm.
 * User: LEOBA / Willing
 * Date: 14/11/2018
 * Time: 16:32
 */
?>

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
                <input type="password" name="mdpAddUser" id="mdpAddUser" value="1234" class="form-control" placeholder="mot de passe">
                <span class="help-block"></span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="mdp2AddUser">Confirmation du mot de passe</label>
                <input type="password" name="mdp2AddUser" id="mdp2AddUser" class="form-control" value="1234" placeholder="confirmer le mot de passe">
                <span class="help-block"></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label for="profileAddUser">Profile</label>
                <select name="profileAddUser" id="profileAddUser" class="form-control">
<!--                    <option value="">Profile</option>-->
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
<!--                    <option value="">Liste des emplyer</option>-->
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

<script type="text/javascript">
    $(document).ready(function () {
        $('#formAddUser').submit(function () {
            var formValide = true;

            if ($('#nomAddUser').val() == '') {
                fieldForm($('#nomAddUser'), 'Veillez entrer votre nom d\'utilisateur', 1);
                formValide = false;
            }else {
                fieldForm($('#nomAddUser'), '', 0);
            }

            if ($('#profileAddUser').val() == '') {
                fieldForm($('#profileAddUser'), 'Veillez choisir votre profile', 1);
                formValide = false;
            }else {
                fieldForm($('#profileAddUser'), '', 0);
            }

            if ($('#employerAddUser').val() == '') {
                fieldForm($('#employerAddUser'), 'Veillez chosir un employe', 1);
                formValide = false;
            }else {
                fieldForm($('#employerAddUser'), '', 0);
            }

            if ($('#mdp2AddUser') != '' && $('#mdpAddUser') != '' && ($('#mdp2AddUser').val() != $('#mdpAddUser').val())) {
                fieldForm($('#mdp2AddUser'), 'Vos deux mot de passes ne sont pas identiques', 2);
                fieldForm($('#mdpAddUser'), '', 2);
                formValide = false;
            }else {
                if ($('#mdpAddUser').val() == '') {
                    fieldForm($('#mdpAddUser'), 'Veillez entrer votre mot de passe', 1);
                    formValide = false;
                }else {
                    fieldForm($('#mdpAddUser'), '', 0);
                }

                if ($('#mdp2AddUser').val() == '') {
                    fieldForm($('#mdp2AddUser'), 'Veillez confirmer votre mot de passe', 1);
                    formValide = false;
                }else {
                    fieldForm($('#mdp2AddUser'), '', 0);
                }
            }

            if (formValide) {
                $.ajax({
                    url: 'user/add',
                    type: 'post',
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function (data) {
                        if (data.error == 0) {
                            $('#addUser .modal-body .container-form-AddUser').html('<p>Un nouvelle utilisateur ajouté</p>');
                            $('#addUser .modal-dialog').append(data.modalFooter);
                            $('#addUser .modal-dialog').addClass('small-modal');
                            // $('#addUser')
                        }else if (data.error == 1) {
                            $('#addUser .errorAdd').html(data.mssge);
                        }else {
                            $('#addUser .modal-header h4').html('Echec');
                            $('#addUser .modal-body').html('<p>Erreur de serveur. L\'utilisateur n\'a pas été ajouté. <br>Veillez réessayer plutard</p>');
                            $('#addUser .modal-content').append(data.modalFooter);
                            $('#addUser .modal-dialog').addClass('small-modal');
                        }
                    },
                    error: function (xhr) {
                        alert('Une erreur s\'est survnue lors du traitement');
                    }
                })
            }


            return formValide;
        })
    });
</script>
