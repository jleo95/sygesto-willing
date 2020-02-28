<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 02/11/2018
 * Time: 00:58
 */
?>
<div class="container-form-EditClient">
    <div class="errorEdit"></div>
    <form action="" method="post" id="formEditClient">

        <input type="hidden" name="hiddenIdClientEdit" value="<?php echo $client->cliid; ?>">
        <div class="form-group">
            <label for="nomEditClient">Nom</label>
            <input type="text" name="nomEditClient" id="nomEditClient" value="<?php
            echo ucfirst($client->clinom);
            ?>" class="form-control" placeholder="nom du client" required>
            <span class="help-block"></span>
        </div>
        <div class="form-group">
            <label for="prenomEditClient">Prénom</label>
            <input type="text" name="prenomEditClient" id="prenomEditClient" value="<?php
            echo ucfirst($client->cliprenom);
            ?>" class="form-control" placeholder="prénom du client" required>
            <span class="help-block"></span>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="telephone1EditClient">Téléphone</label>
                    <input type="text" name="telephone1EditClient" id="telephone1EditClient" value="<?php
                    echo $client->clitelephone;
                    ?>" class="form-control" placeholder="numéro de téléphone" required>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="telephone2EditClient">Téléphone 2</label>
                    <input type="text" name="telephone2EditClient" id="telephone2EditClient" value="<?php
                        echo $client->cliportable;
                    ?>" class="form-control">
                    <span class="help-block"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="villeEditClient">Ville</label>
                    <input type="text" name="villeEditClient" id="villeEditClient" value="<?php
                    echo $client->cliville;
                    ?>" class="form-control" placeholder="ville de residence" required>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="categoryEditClient">Categorie</label>
                    <input type="text" name="categoryEditClient" id="categoryEditClient" value="<?php
                    echo $client->clicategory;
                    ?>" class="form-control" placeholder="catégorie de client" required>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="checkRemise"> <input type="checkbox" name="checkRemise" id="checkRemise" value="1" /> Remise</label>
                    <input type="number" name="checkRemiseEditClient" id="checkRemiseEditClient" value="<?php
                    echo $client->cliremise;
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
    var nameClientEdit = '';
    $(document).ready(function (e) {

        $('#editeClient .modal-content').children('.modal-footer').remove();
        $('#editeClient .modal-dialog').removeClass('small-modal');
        $('#editeClient .modal-header h4').html('Editer un Client');
        
        $('#checkRemise').change(function() {
            console.log($(this));
            
            if($(this).checked == true) {
                console.log('cocher');
            }else {
                console.log('pas cocher');
            }
        });

        $('#formEditClient').submit(function (e) {
            e.preventDefault();


            var valideFormEdit = true;

            if ($('#nomEditClient').val() == '') {
                fieldForm($('#nomEditClient'), 'Ce champs est obligatoire', 1);
                valideFormEdit = false;
            }else {
                fieldForm($('#nomEditClient'), '', 0);
            }

            if ($('#prenomEditClient').val() == '') {
                fieldForm($('#prenomEditClient'), 'Ce champs est vide', 1);
            }else {
                fieldForm($('#prenomEditClient'), '', 0);
            }

            if ($('#nomEditClient').val() == '') {
                fieldForm($('#nomEditClient'), 'Ce champs est obligatoire', 1);
                valideFormEdit = false;
            }else {
                fieldForm($('#nomEditClient'), '', 0);
            }

            if ($('#telephone1EditClient').val() == '') {
                fieldForm($('#telephone1EditClient'), 'Ce champs est obligatoire', 1);
                valideFormEdit = false;
            }else {
                fieldForm($('#telephone1EditClient'), '', 0);
            }

            if ($('#telephone2EditClient').val() == '') {
                fieldForm($('#telephone2EditClient'), 'Ce champs est vide', 1);
            }else {
                fieldForm($('#telephone2EditClient'), '', 0);
            }


            if ($('#villeEditClient').val() == '') {
                fieldForm($('#villeEditClient'), 'Ce champs est obligatoire', 1);
                valideFormEdit = false;
            }else {
                fieldForm($('#villeEditClient'), '', 0);
            }


            if (valideFormEdit) {
                $.ajax({
                    url: 'client/edit',
                    type: 'post',
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function (data) {
                        nameClientEdit = data.nomClient;
                        if (data.error == 0) {
                            $('#bodyTableClient').html(data.tbodyTableClient);
                            $('#editeClient .modal-header h4').html('Félicitation');
                            $('#editeClient .modal-body').html('<p>Client edtier</p>');
                            $('#editeClient .modal-content').append(data.modalFooter);
                            $('#editeClient .modal-dialog').addClass('small-modal');
                        }else if (data.error == 1){
                            $('#editeClient .modal-body .errorEdit').html(data.mssge);
                        }else {
                            $('#editeClient .modal-header h4').html('Echec');
                            $('#editeClient .modal-body').html('<p>Client non mis à jour</p>');
                            $('#editeClient .modal-content').append(data.modalFooter);
                            $('#editeClient .modal-dialog').addClass('small-modal');
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
                text: 'Erreur de serveur. Le client "' + nameClientEdit + '" n\a pas été mis à jour. <br> Veillez réessayer plutard',
                image: 'assets/img/un.png'
            });
        }else {
            $.gritter.add({
                title: 'Edition',
                text: 'Les information du client "' + nameClientEdit + '" ont été mis à jour',
                // image: 'assets/img/un.png'
                image: 'assets/img/confirm.png'
            });
        }

        $('#addProuit .modal-dialog').removeClass('small-modal');

    }
    
    
</script>