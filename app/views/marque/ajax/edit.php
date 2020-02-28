<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 01/11/2018
 * Time: 16:54
 */
?>
<div class="container-form-EditMarque">
    <form action="" method="post" id="formEditMarque">
        <div class="errorEdit"></div>

        <input type="hidden" name="idMarqueEdit" id="idMarqueEdit" value="<?php $marque->marid?>">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="designationEditMarque">Designation</label>
                    <input type="text" name="designationEditMarque" id="designationEditMarque" value="<?php echo $marque->marlibelle; ?>" class="form-control" placeholder="nom de l'unté">
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="descriptionEditMarque">Description</label>
                    <textarea name="descriptionEditMarque" id="descriptionEditMarque" cols="30" rows="10" placeholder="une description" class="form-control" style="height: 90px"><?php echo $marque->mardescription; ?></textarea>
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
        $('#formEditMarque').submit(function (e) {
            e.preventDefault();

            var valideFormEdit = true;
            console.log('soumit');

            $('#editeMarque .modal-content').children('.modal-footer').remove();

            if ($('#designationEditMarque').val() == '') {
                fieldForm($('#designationEditMarque'), 'Ce champs est obligatoire', 1);
                valideFormEdit = false;
            }else {
                fieldForm($('#designationEditMarque'), '', 0);
            }

            if ($('#descriptionEditMarque').val() == '') {
                fieldForm($('#descriptionEditMarque'), 'Ce champs est vide', 1);
            }else {
                fieldForm($('#descriptionEditMarque'), '', 0);
            }

            if (valideFormEdit) {
                $.ajax({
                    url: 'marque/edit',
                    type: 'post',
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function (data) {

                        console.log(data);
                        if (data.error == 0) {
                            $('#bodyTableMarque').html(data.tbodyTableMarque);
                            $('#editeMarque .modal-header h4').html('Félicitation');
                            $('#editeMarque .modal-content').append(data.modalFooter);
                            $('#editeMarque .modal-body').html('<p>La marque a été mis à jour</p>');
                        }else if (data.error == 1) {
                            $('#editeMarque .errorEdit').html(data.mssge);
                        }else {
                            $('#editeMarque .modal-header h4').html('Echec');
                            $('#editeMarque .modal-body').html('<p>Erreur de serveur. La marque n\'a pas été mis à jour. <br>Veillez réessayer plutard</p>');
                            $('#editeMarque .modal-content').append(data.modalFooter);
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
