<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 31/10/2018
 * Time: 22:24
 */
?>

<div class="container-form-EditFamille">
    <form method="post" id="formEditFamille">
        <div class="errorEdit"></div>
        <input type="hidden" name="idFamilleEdit" value="<?php echo $famille->famid; ?>">
        <div class="row">
            <div class="col-md-7">
                <div class="form-group">
                    <label for="designationEditFamille">Designation</label>
                    <input type="text" name="designationEditFamille" id="designationEditFamille" value="<?php echo $famille->famlibelle; ?>" class="form-control" placeholder="nom de l'unté" required>
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

        // edition d'famille
        $('#formEditFamille').submit(function (e) {
            e.preventDefault();

            var valideFormEdit = true;
            console.log('soumit');

            if ($('#designationEditFamille').val() == '') {
                fieldForm($('#designationEditFamille'), 'Ce champs est obligatoire', 1);
                valideFormEdit = false;
            }else {
                fieldForm($('#designationEditFamille'), '', 0);
            }

            if ($('#abvEditFamille').val() == '') {
                fieldForm($('#abvEditFamille'), 'Ce champs est obligatoir', 1);
                valideFormEdit = false;
            }else {
                fieldForm($('#abvEditFamille'), '', 0);
            }

            if (valideFormEdit) {
                $.ajax({
                    url: 'famille/edit',
                    type: 'post',
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function (data) {

                        if (data.error == 0) {
                            $('#bodyTableFamille').html(data.tbodyTableFamille);
                            $('#editeFamille .modal-header h4').html('Félicitation');
                            $('#editeFamille .modal-content').append(data.modalFooter);
                            $('#editeFamille .modal-body').html('<p>La famille a été mis à jour</p>');
                        }else if (data.error == 1) {
                            $('#editeFamille .errorEdit').html(data.mssge);
                        }else {
                            $('#editeFamille .modal-header h4').html('Echec');
                            $('#editeFamille .modal-body').html('<p>Erreur de serveur. La famille n\'a pas été mis à jour. <br>Veillez réessayer plutard</p>');
                            $('#editeFamille .modal-content').append(data.modalFooter);
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