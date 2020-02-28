<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 02/11/2018
 * Time: 00:58
 */
?>
<div class="container-form-EditEmploye">
    <div class="errorEdit"></div>
    <form action="" method="post" id="formEditEmploye">
        <input type="hidden" name="hiddenIdEmployeEdit" id="hiddenIdEmployeEdit" value="<?php echo $employe->empid?>">
        <div class="form-group">
            <label for="nomEditEmploye">Nom du client</label>
            <input type="text" name="nomEditEmploye" id="nomEditEmploye" value="<?php
                echo ucfirst($employe->empnom);
            ?>" class="form-control" placeholder="nom du employe" required>
            <span class="help-block"></span>
        </div>
        <div class="form-group">
            <label for="prenomEditEmploye">Prénom</label>
            <input type="text" name="prenomEditEmploye" id="prenomEditEmploye" value="<?php
                echo ucfirst($employe->empprenom);
            ?>" class="form-control" placeholder="prénom du employe">
            <span class="help-block"></span>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="telephone1EditEmploye">Téléphone</label>
                    <input type="text" name="telephone1EditEmploye" id="telephone1EditEmploye" value="<?php
                        echo $employe->emptelephone;
                    ?>" class="form-control" placeholder="numéro de téléphone" required>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="telephone2EditEmploye">Téléphone 2</label>
                    <input type="text" name="telephone2EditEmploye" id="telephone2EditEmploye" value="<?php
                        echo $employe->empportable;
                    ?>" class="form-control">
                    <span class="help-block"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="residenceEditEmploye">Résidence</label>
                    <input type="text" name="residenceEditEmploye" id="residenceEditEmploye" value="<?php
                        echo ucfirst($employe->empresidence);
                    ?>" class="form-control" placeholder="ville de residence" required>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Sexe: </label>&nbsp;&nbsp;&nbsp;
                    <div style="padding-left: 32px;">
                        <label for="hommeEditEmploye" style="cursor: pointer;"><input type="radio" name="sexeEditEmploye" id="hommeEditEmploye" <?php
                            if (intval($employe->empsexe) == 1) {
                                echo 'checked';
                            }
                            ?> value="1" required> <i class="fa fa-male" style="font-size: 22px"></i></label>&nbsp;&nbsp;

                        <label for="femmeEditEmploye" style="cursor: pointer;"><input type="radio" name="sexeEditEmploye" id="femmeEditEmploye" <?php
                                if (intval($employe->empsexe) == 2) {
                                    echo 'checked';
                                }
                            ?> value="2" required> <i class="fa fa-female" style="font-size: 22px"></i></label>
                    </div>
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

<script>
    var nameEmployeEdit = '';
    $(document).ready(function (e) {

        $('#editeEmploye .modal-content').children('.modal-footer').remove();
        $('#editeEmploye .modal-dialog').removeClass('small-modal');
        $('#editeEmploye .modal-header h4').html('Editer un employé');

        $('#formEditEmploye').submit(function (e) {
            e.preventDefault();


            var valideFormEdit = true;

            if ($('#nomEditEmploye').val() == '') {
                fieldForm($('#nomEditEmploye'), 'Ce champs est obligatoire', 1);
                valideFormEdit = false;
            }else {
                fieldForm($('#nomEditEmploye'), '', 0);
            }

            if ($('#prenomEditEmploye').val() == '') {
                fieldForm($('#prenomEditEmploye'), 'Ce champs est vide', 1);
            }else {
                fieldForm($('#prenomEditEmploye'), '', 0);
            }

            if ($('#nomEditEmploye').val() == '') {
                fieldForm($('#nomEditEmploye'), 'Ce champs est obligatoire', 1);
                valideFormEdit = false;
            }else {
                fieldForm($('#nomEditEmploye'), '', 0);
            }

            if ($('#telephone1EditEmploye').val() == '') {
                fieldForm($('#telephone1EditEmploye'), 'Ce champs est obligatoire', 1);
                valideFormEdit = false;
            }else {
                fieldForm($('#telephone1EditEmploye'), '', 0);
            }

            if ($('#telephone2EditEmploye').val() == '') {
                fieldForm($('#telephone2EditEmploye'), 'Ce champs est vide', 1);
            }else {
                fieldForm($('#telephone2EditEmploye'), '', 0);
            }


            if ($('#residenceEditEmploye').val() == '') {
                fieldForm($('#residenceEditEmploye'), 'Ce champs est obligatoire', 1);
                valideFormEdit = false;
            }else {
                fieldForm($('#residenceEditEmploye'), '', 0);
            }


            if (valideFormEdit) {
                $.ajax({
                    url: 'employe/edit',
                    type: 'post',
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function (data) {
                        nameEmployeEdit = data.nomEmploye;
                        if (data.error == 0) {
                            $('#bodyTableEmploye').html(data.tbodyTableEmploye);
                            $('#editeEmploye .modal-header h4').html('Félicitation');
                            $('#editeEmploye .modal-body').html('<p>Employe edtier</p>');
                            $('#editeEmploye .modal-content').append(data.modalFooter);
                            $('#editeEmploye .modal-dialog').addClass('small-modal');
                        }else if (data.error == 1){
                            $('#editeEmploye .modal-body .errorEdit').html(data.mssge);
                        }else {
                            $('#editeEmploye .modal-header h4').html('Echec');
                            $('#editeEmploye .modal-body').html('<p>Employe non mis à jour</p>');
                            $('#editeEmploye .modal-content').append(data.modalFooter);
                            $('#editeEmploye .modal-dialog').addClass('small-modal');
                        }
                    },
                    error: function (data) {
                        alert('Erreur de chargement reessayer plutard');
                    }
                })
            }
        })
    });

    function confirmEdit (status) {
        console.log(status);
        if (status == 1) {
            $.gritter.add({
                title: 'Echec de mis à jour',
                text: 'Erreur de serveur. L\'employé "' + nameEmployeEdit + '" n\a pas été mis à jour. <br> Veillez réessayer plutard',
                image: 'assets/img/un.png'
            });
        }else {
            $.gritter.add({
                title: 'Edition',
                text: 'Les information de l\'employé "' + nameEmployeEdit + '" ont été mis à jour',
                // image: 'assets/img/un.png'
                image: 'assets/img/confirm.png'
            });
        }

        $('#addProuit .modal-dialog').removeClass('small-modal');

    }
    
    
</script>