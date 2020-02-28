<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 01/11/2018
 * Time: 14:28
 */
?>

<div class="container-form-AddProduit">
    <div class="errorAdd"></div>
    <form action="" method="post" id="formAddProduit">
        <div class="form-group">
            <label for="designAddProduit">Designation</label>
            <input type="text" name="designAddProduit" id="designAddProduit" class="form-control" placeholder="nom du produit" required>
            <span class="help-block"></span>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="prixachatAddProduit">Prix d'achat</label>
                    <input type="number" name="prixachatAddProduit" id="prixachatAddProduit" class="form-control" placeholder="prix unitaire d'achat" required>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    <label for="fournisseurAddProduit">Fournisseur</label>
                    <select name="fournisseurAddProduit" id="fournisseurAddProduit" class="form-control">
                        <option value="">Fournisseurs</option>
                        <?php foreach ($fournisseurs as $f) : ?>
                            <option value="<?php echo $f->fouid; ?>"><?php echo ucfirst($f->founom) . ' ' . ucfirst($f->fouprenom) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="help-block"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="prixUnitVenteAddProduit">Prix unitaire de vente</label>
                    <input type="number" name="prixUnitVenteAddProduit" id="prixUnitVenteAddProduit" class="form-control" placeholder="prix unitaire de vente" required>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="prixGlobVenteAddProduit">Prix de vente global</label>
                    <input type="number" name="prixGlobVenteAddProduit" id="prixGlobVenteAddProduit" class="form-control" placeholder="prix de vente globale">
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="nbblogAddProduit">Nbre de produit par blog</label>
                    <input type="text" name="nbblogAddProduit" id="nbblogAddProduit" class="form-control" placeholder="nombre de produit par blog">
                    <span class="help-block"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10">
                <div class="form-group">
                    <label for="peremptionAddProduit">Date de peremption</label>
                    <input type="text" name="peremptionAddProduit" id="peremptionAddProduit" class="form-control" placeholder="le date d'expiration d'un produit" required>
                    <span class="help-block"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="familleAddProduit">Famille</label>
                    <select name="familleAddProduit" id="familleAddProduit" class="form-control">
                        <option value="">Famille du produit</option>
                        <?php foreach ($familles as $famille) : ?>
                            <option value="<?php echo $famille->famid; ?>"><?php echo $famille->famlibelle; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="uniteAddProduit">Unité de mesure</label>
                    <select name="uniteAddProduit" id="uniteAddProduit" class="form-control" required>
                        <option value="">Unité de mesure</option>
                        <?php foreach ($unites as $unite) : ?>
                            <option value="<?php echo $unite->uniid; ?>"><?php echo $unite->unilibelle ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="help-block"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="seuilAddProduit">Seuil d'alerte (stock)</label>
                    <input type="text" name="seuilAddProduit" id="seuilAddProduit" class="form-control" placeholder="par exemple 5">
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

<script>
    $(document).ready(function () {
        $('#peremptionAddProduit').pickadate({
            formatSubmit: 'yyyy-mm-dd',
            format: 'yyyy-mm-dd',
        });

        $('#addProuit .modal-content').children('.modal-footer').remove();
        $('#formAddProduit').submit(function (e) {
            e.preventDefault();

            var formValide = true;

            if ($('#designAddProduit').val() == '') {
                fieldForm($('#designAddProduit'), 'Veillez saissir le nom du produit', 1);
                formValide = false;
            }else {
                fieldForm($('#designAddProduit'), '', 2);
            }

            /* verification du champs prix d'achat **/
            if ($('#prixachatAddProduit').val() == '') {
                fieldForm($('#prixachatAddProduit'), 'Veillez saissir le prix unitaire d\'achat', 1);
                formValide = false;
            }else {
                fieldForm($('#prixachatAddProduit'), '', 2);
            }

            // verification du prix unitaire de vente
            if ($('#prixUnitVenteAddProduit').val() == '') {
                fieldForm($('#prixUnitVenteAddProduit'), 'Veillez saissir le prix unitaire de vente', 1);
                formValide = false;
            }else {
                fieldForm($('#prixUnitVenteAddProduit'), '', 2);
            }

            // verification du prix global de vente
            if ($('#prixGlobVenteAddProduit').val() == '') {
                fieldForm($('#prixGlobVenteAddProduit'), 'Veillez saissir le prix global de vente', 1);
                formValide = false;
            }else {
                fieldForm($('#prixGlobVenteAddProduit'), '', 2);
            }

            // verication du champs date de peremption
            if ($('#peremptionAddProduit').val() == '') {
                fieldForm($('#peremptionAddProduit'), 'Veillez entrer la date de peremption', 1);
                formValide = false;
            }else {
                fieldForm($('#peremptionAddProduit'), '', 2);
            }

            // verication du champs unite de mesure
            if ($('#uniteAddProduit').val() == '') {
                fieldForm($('#uniteAddProduit'), 'Veillez chosir une unité de mesure', 1);
                formValide = false;
            }else {
                fieldForm($('#uniteAddProduit'), '', 2);
            }


            if ($('#familleAddProduit').val() == '') {
                fieldForm($('#familleAddProduit'), '', 2);
            }

            if ($('#seuilAddProduit').val() != '') {
                fieldForm($('#seuilAddProduit'), '', 2);
            }

            // formValide = false;
            // console.log($('#peremptionAddProduit').val());
            if (formValide) {
                $.ajax({
                    url: 'produit/add',
                    type: 'post',
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function (data) {
                        console.log(data);
                        if (data.error == 0) {
                            $('#bodyTableProduit').html(data.bodyTableProduit);

                            var produit = data.produit;
                            $table.bootstrapTable('prepend', produit);
                            console.log(data);

                            $('#addProuit .modal-header h4').html('Félicitation');
                            $('#addProuit .modal-body').html('<p>Votre produit a été enregistré</p>');
                            $('#addProuit .modal-content').append(data.modalFooter);
                            $('#addProuit .modal-dialog').addClass('small-modal');
                        }else if (data.error == 1) {
                            $('#addProuit .errorAdd').html(data.mssge);
                        }else {
                            $('#addProuit .modal-header h4').html('Echec');
                            $('#addProuit .modal-body').html('<p>Erreur de serveur. Le produit n\'a pas été ajouté. <br>Veillez réessayer plutard</p>');
                            $('#addProuit .modal-content').append(data.modalFooter);
                            $('#addProuit .modal-dialog').addClass('small-modal');
                        }

                    },
                    error: function (xhr, error) {
                        alert('Une erreur s\'est survnue lors du traitement');
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
