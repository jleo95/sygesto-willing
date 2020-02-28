<?php
/**
 * Created by PhpStorm.
 * User: LEOBA / Willing
 * Date: 10/11/2018
 * Time: 12:01
 */
?>
<script>

</script>
<div class="error-produit">
    <div class="alert alert-block alert-danger" style="display:none">
        <strong>Erreur !</strong>
        <p></p>
    </div>
</div>
<div class="produit">
    <div class="line">
        <span class="label">Designation: </span><span class="value"><?php echo ucfirst($produit->prodesignation); ?></span>
    </div>
    <div class="line">
        <span class="label">Famille: </span><span class="value"><?php echo ucfirst($produit->famille); ?></span>
    </div>
    <div class="line">
        <span class="label">Stock magasin: </span><span class="value"><?php echo $stock_function->stock_magasin_by_produit($produit->proid); ?></span>
    </div>
    <div class="line">
        <span class="label">Quantité: </span>
        <input type="number" name="quantiteEntree" id="quantiteEntree" class="form-control">
    </div>
    <div class="line clear">
        <div class="right">
            <button type="button" data-dismiss="modal" id="btnProcessEntree1" class="btn btn-sm btn-default disabled" disabled onclick="addInEntree(<?php echo $produit->proid ?>)">Valider</button>
        </div>
    </div>
</div>

<?php
$trouver = false;
$quantite = 0;

if (isset($_SESSION['entrees']) AND !empty($_SESSION['entrees'])) {
    foreach ($_SESSION['entrees'] as $entree) {
        if ($entree['idProduit'] == $produit->proid) {
            $trouver = true;
            $quantite += $entree['quantite'];
        }
    }
}

if ($trouver) {
    ?>
    <script>
        var quantiteAll = <?php echo $quantite ?>,
            quantite = <?php echo $stock_function->stock_magasin_by_produit($produit->proid) ?>,
            maxStock = quantite - quantiteAll,
            stock = 0;
        $('#quantiteEntree').blur(function () {
            stock = $(this).val() * 1 + quantiteAll;
            if (stock> quantite) {
                $('.error-produit div.alert p').text("Ce produit existe deja dans votre demande. En plus de la nouvelle quantité le stock est en manque. (Quantité max " + maxStock + ")");
                $('.error-produit div.alert').show("slow").delay(7000).hide("slow");
            }
            // console.log(stock);
        });

        $('#quantiteEntree').change(function () {
            if ($(this).val() * 1 + quantiteAll <= quantite && $(this).val() > 0) {
                $('#btnProcessEntree1').removeAttr('disabled');
                $('#btnProcessEntree1').removeClass('disabled');
                $('#btnProcessEntree1').removeClass('btn-default');
                $('#btnProcessEntree1').addClass('myBtn-primary');
            }else {
                $('#btnProcessEntree1').attr('disabled', '');
                $('#btnProcessEntree1').addClass('disabled');
                $('#btnProcessEntree1').addClass('disabled');
                $('#btnProcessEntree1').removeClass('myBtn-primary');
                $('#btnProcessEntree1').addClass('btn-default');
            }
        });
    </script>
    <?php
}else {
    ?>

    <script>
        var quantite = 0,
            idProduit = null;
        tmpIdProduit = <?php echo $produit->proid ?>;

        if (tmpIdProduit != idProduit) {
            quantite = <?php echo $stock_function->stock_magasin_by_produit($produit->proid) ?>;
            idProduit = tmpIdProduit;
        }

        $('#quantiteEntree').blur(function () {
            if ($(this).val() > quantite) {
                $('.error-produit div.alert p').text("Cette quantité n'est pas en stock. (Quantité max " + quantite + ")");
                $('.error-produit div.alert').show("slow").delay(7000).hide("slow");
            }
        });
        $('#quantiteEntree').change(function () {
            if ($(this).val() <= quantite && $(this).val() > 0) {
                $('#btnProcessEntree1').removeAttr('disabled');
                $('#btnProcessEntree1').removeClass('disabled');
                $('#btnProcessEntree1').removeClass('btn-default');
                $('#btnProcessEntree1').addClass('myBtn-primary');
            }else {
                $('#btnProcessEntree1').attr('disabled', '');
                $('#btnProcessEntree1').addClass('disabled');
                $('#btnProcessEntree1').addClass('disabled');
                $('#btnProcessEntree1').removeClass('myBtn-primary');
                $('#btnProcessEntree1').addClass('btn-default');
            }
        });
        console.log('je nexiste pas encore');
        console.log(quantite);
    </script>

<?php
}
?>