<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 08/11/2018
 * Time: 15:41
 */
?>

<div class="error-stock">
    <div class="alert alert-danger">
        <p><strong>Attention !</strong> Cette quantité n'existe pas en stock</p>
    </div>
</div>
<div class="produit">
    <div class="line"><span class="label">Ref. : </span><?php echo $produit->proid ?></div>
    <div class="line"><span class="label">Designation : </span><?php echo  ucfirst($produit->prodesignation) ?></div>
    <div class="line"><span class="label">Prix unitaire : </span><?php echo ucfirst($produit->proprixUnitVente) ?></div>
    <div class="line"><span class="label">Unité de mésure: </span><?php echo ucfirst($produit->unite) ?></div>
    <div class="line"><label for="quantiteProduitVente" class="label">Quantite : </label>
        '<input type="number" class="form-control input-sm" name="quantiteProduitVente" id="quantiteProduitVente">
    </div>
    <div class="line clear"><div class="right"><button data-dismiss="modal" class="btn btn-sm btn-default disabled" id="btnProcessAddVenteValidate" disabled type="button" onclick="addProduitInVente(<?php echo $produit->proid; ?>)">Valider</button></div></div>
</div>

<script>
    $(document).ready(function () {
        var qte = <?php echo $stock ; ?>;
        
        $('.error-stock').hide();
        $('#quantiteProduitVente').blur(function () {
            if ($(this).val() != 0 && $(this).val() > qte){
                $('.error-stock').fadeIn();
                $('.error-stock div p').html('<strong>Attention !</strong> Cette quantité n\'existe pas en stock');
                $('#btnProcessAddVenteValidate').attr('disabled', '');
                $('#btnProcessAddVenteValidate').addClass('disabled');
                $('#btnProcessAddVenteValidate').addClass('btn-default');
                $('#btnProcessAddVenteValidate').removeClass('myBtn-primary');
            }else
            if ($(this).val() > 0 && $(this).val() <= qte) {
                $('.error-stock').fadeOut();
                $('#btnProcessAddVenteValidate').removeAttr('disabled');
                $('#btnProcessAddVenteValidate').removeClass('disabled');
                $('#btnProcessAddVenteValidate').removeClass('btn-default');
                $('#btnProcessAddVenteValidate').addClass('myBtn-primary');
            }else {
                $('.error-stock').fadeIn();
                $('.error-stock div p').html('<strong>Attention !</strong> Veillez mettre une quantité valide');
                $('#btnProcessAddVenteValidate').attr('disabled', '');
                $('#btnProcessAddVenteValidate').addClass('disabled');
                $('#btnProcessAddVenteValidate').addClass('btn-default');
                $('#btnProcessAddVenteValidate').removeClass('myBtn-primary');
            }
        });
        $('#quantiteProduitVen1te').change(function () {
            if ($(this).val() != 0 && $(this).val() > qte){
                $('.error-stock').fadeIn();
                $('.error-stock div p').html('<strong>Attention !</strong> Cette quantité n\'existe pas en stock');
                $('#btnProcessAddVenteValidate').attr('disabled', '');
                $('#btnProcessAddVenteValidate').addClass('disabled');
                $('#btnProcessAddVenteValidate').addClass('btn-default');
                $('#btnProcessAddVenteValidate').removeClass('myBtn-primary');
            }else
            if ($(this).val() > 0 && $(this).val() <= qte) {
                $('.error-stock').fadeOut();
                $('#btnProcessAddVenteValidate').removeAttr('disabled');
                $('#btnProcessAddVenteValidate').removeClass('disabled');
                $('#btnProcessAddVenteValidate').removeClass('btn-default');
                $('#btnProcessAddVenteValidate').addClass('myBtn-primary');
            }else {
                $('.error-stock').fadeIn();
                $('.error-stock div p').html('<strong>Attention !</strong> Veillez mettre une quantité valide');
                $('#btnProcessAddVenteValidate').attr('disabled', '');
                $('#btnProcessAddVenteValidate').addClass('disabled');
                $('#btnProcessAddVenteValidate').addClass('btn-default');
                $('#btnProcessAddVenteValidate').removeClass('myBtn-primary');
            }
        });
    })
</script>