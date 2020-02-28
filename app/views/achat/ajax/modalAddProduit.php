<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 05/11/2018
 * Time: 11:50
 */
?>

<div class="addProduitOffre">
    <div class="errorAddProduit"></div>
    <input type="hidden" name="hiddenIdProduitCommande" id="hiddenIdProduitCommande"><br>
    <div><span class="label">Produit: </span><?php echo ucfirst($produit->prodesignation); ?></div><br>
    <div><span class="label">Famille: </span><?php echo $famille; ?></div><br>
    <div><span class="label">Quantité: </span> <input type="number" value="0" name="qtiteProduitOffre" id="qtiteProduitOffre"></div>
    <div class="clear">
        <div class="btn-group-sm right">
            <input type="button" data-dismiss="modal" value="Ajouter" class="btn btn-primary disabled" id="btnAddProduitOffre" disabled onclick="addProduitInCommand(<?php echo $produit->proid ?>)">
        </div>
    </div>

</div>

<script>
    $(document).ready(function () {
        $('#qtiteProduitOffre').blur(function () {
            if ($(this).val() > 0) {
                $('.errorAddProduit').html('');
                $('#btnAddProduitOffre').removeAttr('disabled');
                $('#btnAddProduitOffre').removeClass('disabled');
            }else {
                $('.errorAddProduit').html('<div class="alert alert-danger alert-dismissable">' +
                    '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                    '<stron>Attention !</stron> Veillez entrer une quantité valide' +
                    '</div>'
                );
                $('#btnAddProduitOffre').addClass('disabled');
                $('#btnAddProduitOffre').attr('disabled', '');
            }
        });

        $('#qtiteProduitOffre').change(function () {
            if ($(this).val() > 0) {
                $('.errorAddProduit').html('');
                $('#btnAddProduitOffre').removeAttr('disabled');
                $('#btnAddProduitOffre').removeClass('disabled');
            }else {
                $('.errorAddProduit').html('<div class="alert alert-danger alert-dismissable">' +
                    '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                    '<stron>Attention !</stron> Veillez entrer une quantité valide' +
                    '</div>'
                );
                $('#btnAddProduitOffre').addClass('disabled');
                $('#btnAddProduitOffre').attr('disabled', '');
            }
        });
        $('#qtiteProduitOffre').keyup(function () {
            if ($(this).val() > 0) {
                $('.errorAddProduit').html('');
                $('#btnAddProduitOffre').removeAttr('disabled');
                $('#btnAddProduitOffre').removeClass('disabled');
            }else {
                $('.errorAddProduit').html('<div class="alert alert-danger alert-dismissable">' +
                    '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                    '<stron>Attention !</stron> Veillez entrer une quantité valide' +
                    '</div>'
                );
                $('#btnAddProduitOffre').addClass('disabled');
                $('#btnAddProduitOffre').attr('disabled', '');
            }
        });
    })
</script>
