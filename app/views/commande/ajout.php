<div class="views-commande">
    <div class="table-offre">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <?php if (!isset($_SESSION['process']) || !$_SESSION['process']): ?>
                        <div class="scrollbar-deep-purple" style="overflow-y: auto; max-height: 380px; border: 1px solid #ddd;">
                            <table class="table">
                                <thead>
                                <th>#</th>
                                <th>Designation</th>
                                <th>Famille</th>
                                <th>Unite</th>
                                <th>Quantité</th>
                                <th></th>
                                </thead>
                                <tbody id="tbodyProduitsCommande">
                                <?php if (isset($_SESSION['listProduitInCommnade']) AND !empty($_SESSION['listProduitInCommnade'])) :
                                    $i = 1;
                                    foreach ($_SESSION['listProduitInCommnade'] as $produit):
                                        ?>
                                        <tr>
                                            <td><?php echo $i;?></td>
                                            <td><?php echo $produit['produit'];?></td>
                                            <td><?php echo $produit['famille'];?></td>
                                            <td><?php echo $produit['unite'];?></td>
                                            <td><?php echo $produit['quantite'];?></td>
                                            <td><a href="javascript: void(0);" onclick="deletProduitFromCommande(<?php echo $i ++ ?>)" class="text-danger"><i class="fa fa-remove"></i></a></td>
                                        </tr>
                                    <?php
                                    endforeach;
                                endif; ?>
                                </tbody>
                            </table>
                            <div class="clear">
                                <div class="right" style="padding-right: 28px; padding-bottom: 10px;">
                                    <!--                                <a data-toggle="modal" href="#addProduitOffre" onclick="loadProduitOffre()"><i class="fa fa-plus" style="color: #008000;"></i></a>-->
                                </div>
                            </div>
                        </div>
                        <div style="text-align: center; padding: 10px;">
                            <a href="commande/selectproduit" class="btn btn-success">Ajouter un produit</a>
                            <?php if (isset($_SESSION['listProduitInCommnade']) && !empty($_SESSION['listProduitInCommnade'])): ?>
                                <a href="javascript: void(0);" class="btn btn-primary" onclick="addProduitToCommande()">Terminer <i class="fa fa-arrow-right"></i></a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <form action="" method="post">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="row">
                                    <div class="col-md-11">
                                        <label for="">Offshore: </label>
                                        <select name="offshore" id="offshore" class="form-control" required>
                                            <option value="">Veillez selectioner un offshore</option>
                                            <?php foreach ($offshores as $offshore) : ?>
                                                <option value="<?php echo $offshore->offid; ?>"><?php echo $offshore->offdescription ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (isset($error['offshore'])) {?>
                                            <span class="text-danger">Veillez choisir un offshore</span>
                                        <?php
                                        }
                                        ?>
                                        <br>
                                    </div>
                                    <div class="col-md-11">
                                        <label for="">Client: </label>
                                        <select name="client" id="client" class="form-control" required>
                                            <option value="">Veillez chosir un client</option>
                                            <?php foreach ($clients as $client) : ?>
                                                <option value="<?php echo $client->cliid; ?>"><?php echo $client->clinom . ' ' . $client->cliprenom; ?></option>
                                            <?php endforeach; ?>
                                        </select><br>
                                    </div>
                                    <div class="col-md-11">
                                        <label for="">Mode de paiement: </label>
                                        <select name="paiement" id="paiement" class="form-control" required>
                                            <option value="">Choix de mode de paiement</option>
                                            <?php foreach ($paiements as $paiement) : ?>
                                                <option value="<?php echo $paiement->paiid; ?>"><?php echo $paiement->pailibelle; ?></option>
                                            <?php endforeach; ?>
                                        </select><br>
                                    </div>
                                    <div class="col-md-11">
                                        <label for="">Date de la commande <span class="text-success">(la date du jour sera prie par defaut)</span>: </label>
                                        <input type="text" name="dateCommande" id="dateCommande" class="form-control" placeholder="veillez choisir la date de la commande">
                                    </div>
                                    <div class="col-md-11" style="text-align: center;">
                                        <br>
                                        <input type="submit" value="Terminer" class="btn btn-success">
                                    </div>
                                </div>

                            </div>
                        </form>


                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script !src="">
    function deletProduitFromCommande(line) {
        $.ajax({
            method: "post",
            url: "commande/deletProduitFromCommande",
            data: {line: line},
            success: function (data) {
                window.location.replace('http://sygesto/commande/ajout');
            },
            error: function (xhr) {
                alert("Une erreure s'est produite veillez ressayer plutard");
            }
        })
    }

    function addProduitToCommande() {
        $.ajax({
            method: "post",
            url: "commande/addProduitToCommande",
            data: {type: 2},
            success: function (data) {
                window.location.replace('http://sygesto/commande/ajout');
            },
            error: function (xhr) {
                alert("Une erreure s'est produite veillez ressayer plutard");
            }
        })
    }


    $('#dateCommande').pickadate({
        format: 'yyyy-mm-dd'
    });
</script>