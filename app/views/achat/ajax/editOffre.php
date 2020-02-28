<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <?php if (isset($error) AND !$error) : ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <stron>Attention !</stron> Veillez entrer une quantité valide
                    </div>
                <?php endif; ?>
                <form action="" id="formOffreEdit" name="formOffreEdit" method="post">
                    <input type='hidden' id='hiddenIdOffreForEdit' name='hiddenIdOffreForEdit' value="<?php echo $offre['id']; ?>">
                    <div class="form-group">
                        <label for="fournisseurOffre">Fournisseur: </label>
                        <select name="fournisseurOffre" id="fournisseurOffre" class="form-control" required>
                            <option value="">Liste des fournisseurs</option>
                            <?php foreach ($fournisseurs as $fournisseur) {
                                ?>
                            <option value="<?php echo $fournisseur->fouid?>" <?php 
                                        if ($fournisseur->fouid == $offre['fournisseur']) {
                                            echo 'selected';
                                        }
                            ?>><?php echo ucfirst($fournisseur->founom) . ' ' . ucfirst($fournisseur->fouprenom)?></option>

                            <?php
                            }
                            ?>
                        </select>
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <label for="">Etat de la commande: </label>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="statusNotValideOffre">Non valide: </label>
                                <input type="radio" name="statusOffre" id="statusNotValideOffre" class="form-control" <?php 
                                echo ($offre['validite'] == 0) ? 'checked="true"' : '';
                                ?> placeholder="date d'achat" value="1" required style="display: inline-block">
                            </div>
                            <div class="col-md-6">
                                <label for="statusValideOffre">Valide: </label>
                                <input type="radio" name="statusOffre" id="statusValideOffre" class="form-control" <?php 
                                echo ($offre['validite'] == 1) ? 'checked="true"' : '';
                                ?> placeholder="date d'achat" value="2" required style="display: inline-block">
                            </div>
                        </div>
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <label for="dateOffre">Date d'achat: </label>
                        <input type="text" name="dateOffre" id="dateOffre" class="form-control" placeholder="date d'achat" value="<?php 
                        echo $offre['date'];
                        ?>" required>
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <label for="dateLivraisonOffre">Date livraison: </label>
                        <input type="text" name="dateLivraisonOffre" id="dateLivraisonOffre" class="form-control" value="<?php
                        echo $offre['dateLivraison'];
                        ?>" placeholder="date de livrason de la marchandise" required >
                        <span class="help-block"></span>
                    </div>
                    <div style="text-align: center; padding: 10px;">
                        <button type="submit" class="btn btn-success" name="btnBeginProcessOffre" id="btnBeginProcessOffre">Suivant <i class="fa fa-arrow-right"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <script>
           $('#dateLivraisonOffre').pickadate({
                format: 'yyyy-mm-dd'
            });
            $('#dateOffre').pickadate({
                format: 'yyyy-mm-dd'
            });
            
            $('#formOffreEdit').submit(function (e) {
//                     e.preventDefault();

                    var valideForm = true;

                    if ($('#fournisseurOffre').val() == '' ) {
                        fieldForm($('#fournisseurOffre'), 'Veillez chosir un fournisseur', 1);
                        valideForm = false;
                    }else {
                        fieldForm($('#fournisseurOffre'), '', 0);
                    }
                    if ($('#dateOffre').val() == '' ) {
                        fieldForm($('#dateOffre'), 'Veillez chosir une date', 1);
                        valideForm = false;
                    }else {
                        fieldForm($('#dateOffre'), '', 0);
                    }
                    if ($('#dateLivraisonOffre').val() == '' ) {
                        fieldForm($('#dateLivraisonOffre'), 'Veillez chosir une date pour la livraison de la marchandise', 1);
                        valideForm = false;
                    }else {
                        fieldForm($('#dateLivraisonOffre'), '', 0);
                    }
                    
                    if (valideForm) {
                        $.ajax({
                            url: 'achat/edit',
                            type: 'post',
                            dataType: 'json',
                            data: $(this).serialize(),
                            success: function(data) {
                                $('#editOffre').modal('hide');
                            },
                            error: function (xhr) {
                                alert('Une erreur s\'est produite. Veillez ressayer plutard');
                            }
                        })
                    }

                    return valideForm;
                })
        </script>
    </div>

