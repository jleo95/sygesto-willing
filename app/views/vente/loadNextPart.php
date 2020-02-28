<?php
/**
 * Created by PhpStorm.
 * User: LEOBA / Willing
 * Date: 08/11/2018
 * Time: 22:51
 */
?>

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <form action="" method="post" id="formEndProcessVente">
            <div class="form-group">
                <label for="modePaiement">Mode de paiement: </label>
                <select name="modePaiment" id="modePaiement" class="form-control">
                    <option value="">Paiement</option>
                    <?php foreach ($paiements as $paiement) {
                        ?>
                        <option value="<?php echo $paiement->paiid; ?>"><?php echo $paiement->pailibelle; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="clear text-center">
                <?php if (isset($_SESSION['ventes_print'])) { ?>
                    <a href="vente/endProcess" class="btn btn-success">Terminer</a>
                    <button type="button" class="btn btn-primary" onclick="imprimerRecu()">Imprimer reçu <i class="fa fa-print"></i></button>
                <?php } else { ?>
                    <button type="submit" class="btn btn-success" name="btnEnProcessVenteSubmit">Enregistrer</button>
                    <button type="button" class="btn btn-default disabled" disabled>Imprimer reçu <i class="fa fa-print"></i></button>
                <?php  } ?>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#formEndProcessVente').submit(function () {
            var formValide = true;

            if ($('#modePaiement').val() == '') {
                fieldForm($('#modePaiement'), 'Veillez choisir le mode de paiement svp.', 1);
                formValide = false;
            }else {
                fieldForm($('#modePaiement'), '', 0);
            }

            return formValide;
        })
    })
</script>
