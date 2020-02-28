<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 18/12/2018
 * Time: 23:08
 */
?>

<div class="views-boutique">
    <div class="container-table">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="toolbar-table clear">
                        <select name="trieEntree1" onchange="trieEntree1(this.value)" id="trieEntree1" class="form-control select" style="display: inline-block; width: 17%">
                            <option value="">Trier par </option>
                            <option value="1">Cette journée</option>
                            <option value="2">Ce mois</option>
                            <option value="3">Ce cette année</option>
                            <option value="4">Autre</option>
                        </select>

                        <select name="triProduit" id="triProduit" style="display: inline-block; width: 22%" class="form-control" onchange="printStock(this.value)">
                            <option value=""><i class="fa fa-print"></i> Impression</option>
                            <option value="1">En stock</option>
                            <option value="2">En alerte de stock</option>
                            <option value="3">En rupture</option>
                        </select>
                    </div>
                    <div class="fresh-table toolbar-color-azure">

                        <div class="toolbar">
                            <!--                <button id="alertBtn" class="btn btn-default">Nouveau produit</button>-->
                        </div>

                        <table id="boutiqueStock-table" class="table table-responsive table-bordered">
                            <thead>
                            <th data-field="id">Ref.</th>

                            <th data-field="name" data-sortable="true">Designation</th>

                            <th data-field="mouvement" data-sortable="true">Famille</th>

<!--                            <th data-field="punitaire" data-sortable="true">P.Uni Vente</th>-->

                            <th data-field="quantite" data-sortable="true">Qté</th>

                            <th data-field="prixqtite" data-sortable="true">Prix Qté. (f cfa)</th>

                            <th data-field="date" data-sortable="true">Statue</th>
                            </thead>

                            <tbody id="bodyTableProduit">
                            <?php echo $produits ; ?>
                            </tbody>
                        </table>

                    </div>

                    <div align="right"><strong>Prix totaux: </strong> <?php echo $prixtotaux ?></div>
                </div>
            </div>
        </div>

    </div>

</div>
