<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 02/11/2018
 * Time: 00:58
 */
?>
<div class="container-form-EditFournisseur">
    <div class="errorEdit"></div>
    <form action="" method="post" id="formEditFournisseur">

        <input type="hidden" name="hiddenIdFournisseur" value="<?php echo $fournisseur->fouid; ?>">
        <div class="form-group">
            <label for="nomEditFournisseur">Nom</label>
            <input type="text" name="nomEditFournisseur" id="nomEditFournisseur" value="<?php
            echo ucfirst($fournisseur->founom);
            ?>" class="form-control" placeholder="nom du fournisseur" required>
            <span class="help-block"></span>
        </div>
        <div class="form-group">
            <label for="prenomEditFournisseur">Prénom</label>
            <input type="text" name="prenomEditFournisseur" id="prenomEditFournisseur" value="<?php
            echo ucfirst($fournisseur->fouprenom);
            ?>" class="form-control" placeholder="prénom du fournisseur" required>
            <span class="help-block"></span>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="telephone1EditFournisseur">Téléphone</label>
                    <input type="text" name="telephone1EditFournisseur" id="telephone1EditFournisseur" value="<?php
                    echo $fournisseur->foutelephone;
                    ?>" class="form-control" placeholder="numéro de téléphone" required>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="telephone2EditFournisseur">Téléphone 2</label>
                    <input type="text" name="telephone2EditFournisseur" id="telephone2EditFournisseur" value="<?php
                        echo $fournisseur->fouportable;
                    ?>" class="form-control">
                    <span class="help-block"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10">
                <div class="form-group">
                    <label for="villeEditFournisseur">Ville</label>
                    <input type="text" name="villeEditFournisseur" id="villeEditFournisseur" value="<?php
                    echo $fournisseur->fouville;
                    ?>" class="form-control" placeholder="ville de residence" required>
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
    var nameFournisseurEdit = '';
    $(document).ready(function (e) {

        $('#editeFournisseur .modal-content').children('.modal-footer').remove();
        $('#editeFournisseur .modal-dialog').removeClass('small-modal');
        $('#editeFournisseur .modal-header h4').html('Editer un Fournisseur');

        $('#formEditFournisseur').submit(function (e) {
            e.preventDefault();


            var valideFormEdit = true;

            if ($('#nomEditFournisseur').val() == '') {
                fieldForm($('#nomEditFournisseur'), 'Ce champs est obligatoire', 1);
                valideFormEdit = false;
            }else {
                fieldForm($('#nomEditFournisseur'), '', 0);
            }

            if ($('#prenomEditFournisseur').val() == '') {
                fieldForm($('#prenomEditFournisseur'), 'Ce champs est vide', 1);
            }else {
                fieldForm($('#prenomEditFournisseur'), '', 0);
            }

            if ($('#nomEditFournisseur').val() == '') {
                fieldForm($('#nomEditFournisseur'), 'Ce champs est obligatoire', 1);
                valideFormEdit = false;
            }else {
                fieldForm($('#nomEditFournisseur'), '', 0);
            }

            if ($('#telephone1EditFournisseur').val() == '') {
                fieldForm($('#telephone1EditFournisseur'), 'Ce champs est obligatoire', 1);
                valideFormEdit = false;
            }else {
                fieldForm($('#telephone1EditFournisseur'), '', 0);
            }

            if ($('#telephone2EditFournisseur').val() == '') {
                fieldForm($('#telephone2EditFournisseur'), 'Ce champs est vide', 1);
            }else {
                fieldForm($('#telephone2EditFournisseur'), '', 0);
            }


            if ($('#villeEditFournisseur').val() == '') {
                fieldForm($('#villeEditFournisseur'), 'Ce champs est obligatoire', 1);
                valideFormEdit = false;
            }else {
                fieldForm($('#villeEditFournisseur'), '', 0);
            }


            if (valideFormEdit) {
                $.ajax({
                    url: 'fournisseur/edit',
                    type: 'post',
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function (data) {
                        nameFournisseurEdit = data.nomFournisseur;
                        if (data.error == 0) {
                            $('#bodyTableFournisseur').html(data.tbodyTableFournisseur);
                            $('#editeFournisseur .modal-header h4').html('Félicitation');
                            $('#editeFournisseur .modal-body').html('<p>Fournisseur edtier</p>');
                            $('#editeFournisseur .modal-content').append(data.modalFooter);
                            $('#editeFournisseur .modal-dialog').addClass('small-modal');
                        }else if (data.error == 1){
                            $('#editeFournisseur .modal-body .errorEdit').html(data.mssge);
                        }else {
                            $('#editeFournisseur .modal-header h4').html('Echec');
                            $('#editeFournisseur .modal-body').html('<p>Fournisseur non mis à jour</p>');
                            $('#editeFournisseur .modal-content').append(data.modalFooter);
                            $('#editeFournisseur .modal-dialog').addClass('small-modal');
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
                text: 'Erreur de serveur. Le fournisseur "' + nameFournisseurEdit + '" n\a pas été mis à jour. <br> Veillez réessayer plutard',
                image: 'assets/img/un.png'
            });
        }else {
            $.gritter.add({
                title: 'Edition',
                text: 'Les information du fournisseur "' + nameFournisseurEdit + '" ont été mis à jour',
                // image: 'assets/img/un.png'
                image: 'assets/img/confirm.png'
            });
        }

        $('#addProuit .modal-dialog').removeClass('small-modal');

    }
    
    
</script>