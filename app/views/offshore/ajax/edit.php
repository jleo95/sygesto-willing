<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 29/10/2018
 * Time: 20:57
 */
?>


<div class="errorEdit"></div>
<form action="" method="post" id="formEditProduit">
    <input type="hidden" name="idProduitEdit" value="<?php echo $produit->proid; ?>">
    <div class="form-group">
        <label for="designEditProduit">Designation</label>
        <input type="text" name="designEditProduit" id="designEditProduit" class="form-control"
               placeholder="nom du produit" value="<?php echo (isset($produit->prodesignation)) ? $produit->prodesignation : '';?>" required>
        <span class="help-block"></span>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="prixachatEditProduit">Prix d'achat</label>
                <input type="number" name="prixachatEditProduit" id="prixachatEditProduit" class="form-control"
                       placeholder="prix unitaire d'achat" value="<?php echo (isset($produit->proprixUnitAchat)) ? $produit->proprixUnitAchat : '';?>" required>
                <span class="help-block"></span>
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group">
                <label for="fournisseurEditProduit">Fournisseur</label>
                <select name="fournisseurEditProduit" id="fournisseurEditProduit" class="form-control">
                    <!--                                                <option value="">Fournisseurs</option>-->
                    <?php foreach ($fournisseurs as $f) : ?>
                        <option value="<?php echo $f->fouid; ?>"
                            <?php echo ($produit->profournisseur == $f->fouid) ? 'selected' : '';?> ><?php echo ucfirst($f->founom) . ' ' . ucfirst($f->fouprenom) ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="help-block"></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="prixUnitVenteEditProduit">Prix unitaire de vente</label>
                <input type="number" name="prixUnitVenteEditProduit" id="prixUnitVenteEditProduit" class="form-control"
                       placeholder="prix unitaire de vente" value="<?php echo (isset($produit->proprixUnitVente)) ? $produit->proprixUnitVente : '';?>" required>
                <span class="help-block"></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="prixGlobVenteEditProduit">Prix de vente global</label>
                <input type="number" name="prixGlobVenteEditProduit" id="prixGlobVenteEditProduit" class="form-control"
                       placeholder="prix de vente globale" value="<?php echo (isset($produit->proprixblogVente)) ? $produit->proprixblogVente : '';?>">
                <span class="help-block"></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="nbblogEditProduit">Nbre de produit par blog</label>
                <input type="text" name="nbblogEditProduit" id="nbblogEditProduit" class="form-control"
                       placeholder="nombre de produit par blog" value="<?php echo (isset($produit->pronbproduitBlog)) ? $produit->pronbproduitBlog : '';?>">
                <span class="help-block"></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10">
            <div class="form-group">
                <label for="peremptionEditProduit">Date de peremption</label>
                <input type="text" name="peremptionEditProduit" id="peremptionEditProduit" class="form-control"
                       placeholder="le date d'expiration d'un produit"
                       value="<?php echo (isset($produit->prodatePeremption)) ? $produit->prodatePeremption : '';?>" required>
                <span class="help-block"></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="familleEditProduit">Famille</label>
                <select name="familleEditProduit" id="familleEditProduit" class="form-control">
                    <!--                                                <option value="">Famille du produit</option>-->
                    <?php foreach ($familles as $famille) : ?>
                        <option value="<?php echo $famille->famid; ?>" <?php
                        echo ($produit->profamille == $famille->famid) ? 'selected' : ''
                        ?>><?php echo $famille->famlibelle; ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="help-block"></span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="uniteEditProduit">Unité de mesure</label>
                <select name="uniteEditProduit" id="uniteEditProduit" class="form-control" required>
                    <!--                                                <option value="">Unité de mesure</option>-->
                    <?php foreach ($unites as $unite) : ?>
                        <option value="<?php echo $unite->uniid; ?>"  <?php
                        echo ($produit->prounitemessure == $unite->uniid) ? 'selected' : ''
                        ?> ><?php echo $unite->unilibelle ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="help-block"></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="seuilEditProduit">Seuil d'alerte (stock)</label>
                <input type="text" name="seuilEditProduit" id="seuilEditProduit" class="form-control"
                       placeholder="par exemple 5" value="<?php echo (isset($produit->proseuilalert)) ? $produit->proseuilalert : '' ?>">
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


<script type="text/javascript">

    var nameProduit = '';
    $(document).ready(function (e) {
        $('#peremptionEditProduit').pickadate({
            formatSubmit: 'yyyy-mm-dd',
            format: 'yyyy-mm-dd',
        });

        $('#editeProduit .modal-content').children('.modal-footer').remove();

        $('#formEditProduit').submit(function (e) {
            e.preventDefault();
            var formValide = true;

            if ($('#designEditProduit').val() == '') {
                fieldForm($('#designEditProduit'), 'Veillez saissir le nom du produit', 1);
                formValide = false;
            }else {
                fieldForm($('#designEditProduit'), '', 2);
            }

            /* verification du champs prix d'achat **/
            if ($('#prixachatEditProduit').val() == '') {
                fieldForm($('#prixachatEditProduit'), 'Veillez saissir le prix unitaire d\'achat', 1);
                formValide = false;
            }else {
                fieldForm($('#prixachatEditProduit'), '', 2);
            }

            // verification du prix unitaire de vente
            if ($('#prixUnitVenteEditProduit').val() == '') {
                fieldForm($('#prixUnitVenteEditProduit'), 'Veillez saissir le prix unitaire de vente', 1);
                formValide = false;
            }else {
                fieldForm($('#prixUnitVenteEditProduit'), '', 2);
            }

            // verification du prix global de vente
            if ($('#prixGlobVenteEditProduit').val() == '') {
                fieldForm($('#prixGlobVenteEditProduit'), 'Veillez saissir le prix global de vente', 1);
                formValide = false;
            }else {
                fieldForm($('#prixGlobVenteEditProduit'), '', 2);
            }

            // verication du champs date de peremption
            if ($('#peremptionEditProduit').val() == '') {
                fieldForm($('#peremptionEditProduit'), 'Veillez entrer la date de peremption', 1);
                formValide = false;
            }else {
                fieldForm($('#peremptionEditProduit'), '', 2);
            }

            // verication du champs unite de mesure
            if ($('#uniteEditProduit').val() == '') {
                fieldForm($('#uniteEditProduit'), 'Veillez chosir une unité de mesure', 1);
                formValide = false;
            }else {
                fieldForm($('#uniteEditProduit'), '', 2);
            }


            if ($('#familleEditProduit').val() == '') {
                fieldForm($('#familleEditProduit'), '', 2);
            }

            if ($('#seuilEditProduit').val() != '') {
                fieldForm($('#seuilEditProduit'), '', 2);
            }

            // si le formulaire est valide on envoi les donnee par ajax pour le traitement
            if (formValide) {
                $.ajax({
                    url: 'produit/edit',
                    type: 'post',
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function (data) {
                        console.log(data);
                        nameProduit = data.nameProduit;

                        if (data.error == 0) {
                            // $('#bodyTableProduit').html(data.tbodyTableProduit);
                            $('#editeProduit .modal-header h4').html('Félicitation');
                            $('#editeProduit .modal-body').html('<p>Produit edtier</p>');
                            $('#editeProduit .modal-content').append(data.modalFooter);
                            $('#editeProduit .modal-dialog').addClass('small-modal');
                        }else if (data.error == 1){
                            $('#editeProduit .modal-body .errorEdit').html(data.mssge);
                        }else {
                            $('#editeProduit .modal-header h4').html('Echec');
                            $('#editeProduit .modal-body').html('<p>Produit non mis à edtier</p>');
                            $('#editeProduit .modal-content').append(data.modalFooter);
                            $('#editeProduit .modal-dialog').addClass('small-modal');
                        }

                    },
                    error: function (xhr, error) {
                        alert('Une erreur s\'est survnue lors du traitement');
                    }
                });
            }
        });
    });

    function confirmEdit(status) {
        console.log(status);
        if (status == 1) {
            $.gritter.add({
                title: 'Eche mis à jour',
                text: 'Erreur de serveur. Prouit non mis à jour. <br> Veillez réessayer plutard',
                image: 'assets/img/un.png'
            });
        }else {
            $.gritter.add({
                title: 'Edition',
                text: 'Produit "' + nameProduit + '" est mis à jour',
                // image: 'assets/img/un.png'
                image: 'assets/img/confirm.png'
            });
        }

        $('#editeProduit .modal-dialog').removeClass('small-modal');

    }
</script>


<?php


