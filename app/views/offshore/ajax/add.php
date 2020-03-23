<?php
/**
 * Created by PhpStorm.
 * User: Willing
 * Date: 19/03/2020
 * Time: 14:28
 */
?>

<div class="container-form-AddOffshore">
    <div class="errorAdd"></div>
    <form action="" method="post" id="formAddOffshore">
        <div class="form-group">
            <label for="descriptionAddOffshore">Description</label>
            <input type="text" name="descriptionAddOffshore" id="descriptionAddOffshore" class="form-control" placeholder="Description du offshore" required>
            <span class="help-block"></span>
        </div>
   
        <div class="form-group">
            <label for="responsableAddOffshore">Responsable</label>
            <select name="responsableAddOffshore" id="responsableAddOffshore" class="form-control">
                <option value="" >Choisir un Responsable</option>
                <?php foreach ($employes as $employe) {
            ?>
                
                 <option value="<?php echo $employe->empid; ?>"><?php echo ucfirst($employe->empnom) . ' ' . ucfirst($employe->empprenom) ?></option>
                <?php
                }
                ?>
            </select>
            <span class="help-block"></span>
        </div>

       
        <div class="form-group">

            <div class="col-md-6">
                <label for="dateDebutAddOffshore">Date de debut</label>
                <input type="text" name="dateDebutAddOffshore" id="dateDebutAddOffshore" class="form-control" placeholder="Date de début offshore" required>
                <span class="help-block"></span>
            </div>
        
            <div class="col-md-6"> 
                <label for="dateFinAddOffshore">Date de fin</label>
                <input type="text" name="dateFinAddOffshore" id="dateFinAddOffshore" class="form-control" placeholder="Date de fin offshore" required>
                <span class="help-block"></span>
            </div>

        </div>
            
    

        
           
        <div class="form-group">
            <label for="clientAddOffshore">Client</label>
            <select name="clientAddOffshore" id="clientAddOffshore" class="form-control">
            <?php foreach ($clients as $client) {
            ?>
                <option value="" >Choisir un client</option>

                 <option value="<?php echo $client->cliid; ?>"><?php echo ucfirst($client->clinom) . ' ' . ucfirst($client->cliprenom) ?></option>
                <?php
                }
                ?>
            </select>
            <span class="help-block"></span>
        </div>
        

        <div class="row">
            
        </div>

        <div class="clear">
            <div class="btn-group right">
                <button type="submit" class="btn btn-primary right" id="btnSubFormAdd">Ajouter</button>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {

        // from add (view/offshore/ajax/add.php
        $('#dateFinAddOffshore').pickadate({
            formatSubmit: 'yyyy-mm-dd',
            format: 'yyyy-mm-dd'
        });
        // from add (view/offshore/ajax/add.php
        $('#dateDebutAddOffshore').pickadate({
            formatSubmit: 'yyyy-mm-dd',
            format: 'yyyy-mm-dd'
        });

        $('#addOffshore .modal-content').children('.modal-footer').remove();
        $('#formAddOffshore').submit(function (e) {
            e.preventDefault();

            var formValide = true;

            if (formValide) {
                $.ajax({
                    url: 'offshore/add',
                    type: 'post',
                    dataType: 'json',
                    data: $(this).serialize(),

                    success: function (data) {
                        console.log(data);
                        if (data.error == 0) {
                            window.location.replace('http://sygesto/offshore');
                        }else if (data.error == 1) {
                            $('#addOffshore .errorAdd').html(data.mssge);
                        }else {
                            $('#addOffshore .modal-header h4').html('Echec');
                            $('#addOffshore .modal-body').html('<p>Erreur de serveur. L\'offshore n\'a pas été ajouté. <br>Veillez réessayer plutard</p>');
                            $('#addOffshore .modal-content').append(data.modalFooter);
                            $('#addOffshore .modal-dialog').addClass('small-modal');
                            window.location.replace('http://sygesto/offshore');
                        }

                    },
                    error: function (xhr, error) {
                        alert('Une erreur est survenue lors du traitement');
                    }
                });
                $(window).resize(function () {
                    $table.bootstrapTable('resetView');
                });
            }
            return formValide;
        });
    })
</script>
