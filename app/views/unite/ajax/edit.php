<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 31/10/2018
 * Time: 22:24
 */
?>

<div class="container-form-EditUnite">
    <form method="post" id="formEditUnite">
        <div class="errorEdit"></div>
        <input type="hidden" name="idUniteEdit" value="<?php echo $unite->uniid; ?>">
        <div class="row">
            <div class="col-md-7">
                <div class="form-group">
                    <label for="designationEditUnite">Designation</label>
                    <input type="text" name="designationEditUnite" id="designationEditUnite" value="<?php echo $unite->unilibelle; ?>" class="form-control" placeholder="nom de l'unté" required>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="abvEditUnite">Abreviation</label>
                    <input type="text" name="abvEditUnite" id="abvEditUnite" value="<?php echo $unite->uniabv; ?>" class="form-control" placeholder="l'abreviation de l'unté" required>
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
    $(document).ready(function (e) {

        // edition d'unite
        $('#formEditUnite').submit(function (e) {
            e.preventDefault();

            var valideFormEdit = true;
            console.log('soumit');

            if ($('#designationEditUnite').val() == '') {
                fieldForm($('#designationEditUnite'), 'Ce champs est obligatoire', 1);
                valideFormEdit = false;
            }else {
                fieldForm($('#designationEditUnite'), '', 0);
            }

            if ($('#abvEditUnite').val() == '') {
                fieldForm($('#abvEditUnite'), 'Ce champs est obligatoir', 1);
                valideFormEdit = false;
            }else {
                fieldForm($('#abvEditUnite'), '', 0);
            }

            if (valideFormEdit) {
                $.ajax({
                    url: 'unite/edit',
                    type: 'post',
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function (data) {

                        if (data.error == 0) {
                            $('#bodyTableUnite').html(data.tbodyTableUnite);
                            $('#editeUnite .modal-header h4').html('Félicitation');
                            $('#editeUnite .modal-content').append(data.modalFooter);
                            $('#editeUnite .modal-body').html('<p>L\'nité de mesure a été mis à jour</p>');
                        }else if (data.error == 1) {
                            $('#editeUnite .errorEdit').html(data.mssge);
                        }else {
                            $('#editeUnite .modal-header h4').html('Echec');
                            $('#editeUnite .modal-body').html('<p>Erreur de serveur. L\'unité n\'a pas été mis à jour. <br>Veillez réessayer plutard</p>');
                            $('#editeUnite .modal-content').append(data.modalFooter);
                        }
                    },
                    error: function (xhr) {
                        alert('Erreur de serveur veillez réessayer plutard');
                    }
                });
            }
            return false;
        });
    })
</script>