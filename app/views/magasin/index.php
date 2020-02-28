<?php
/**
 * Created by PhpStorm.
 * User: LEOBA / Willing
 * Date: 09/11/2018
 * Time: 13:09
 */
?>

<div class="views-boutique">
    <div class="container-table">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <ul class="nav nav-pills">
                        <li class="active"><a href="#entreeBoutique" data-toggle="tab">Entrées</a></li>
                        <li><a href="#sortieBoutique" data-toggle="tab">Sorites</a></li>
                    </ul>
                    <div class="tab-content" style="padding-top: 10px;">
                        <div class="tab-pane active" id="entreeBoutique">

                            <div class="toolbar-table clear">
                                <select name="trieEntree1" onchange="trieEntree1(this.value)" id="trieEntree1" class="form-control" style="display: inline-block; width: 17%">
                                    <option value="">Trier par </option>
                                    <option value="1">Cette journée</option>
                                    <option value="2">Ce mois</option>
                                    <option value="3">Cette année</option>
                                    <option value="4">Autre</option>
                                </select>
                                
                                
                                <select name="produitsListe" id="produitsListe" onchange="triByProduit(this.value, 1)" class="form-control select" style="display: inline-block; width: 30%">
                                    <option value="">Trier par produit</option>
                                    <?php  foreach ($produits as $produit) : ?>
                                    <option value="<?php echo $produit->proid ?>"><?php echo ucfirst($produit->prodesignation) ?></option>
                                    <?php   endforeach; ?>
                                </select>
                                
                                <a href="boutique/imprissionMouvement" target="blank"><i class="fa fa-print"></i></a>
                                <div class="right">
                                    <a href="achat/add" class="btn btn-primary" style="display: inline-block;"><i class="fa fa-plus-circle text-success" style="font-size: 17px"></i> Nouvelle entrée</a>
                                </div>
                            </div>

                            <div class="fresh-table toolbar-color-azure">

                                <div class="toolbar">
                                    <!--                <button id="alertBtn" class="btn btn-default">Nouveau produit</button>-->
                                </div>

                                <table id="boutiqueEntrees-table" class="table table-responsive table-bordered">
                                    <thead>
                                    <th data-field="id">Ref.</th>

                                    <th data-field="name" data-sortable="true">Designation</th>

                                    <th data-field="mouvement" data-sortable="true">Mouvement</th>

                                    <th data-field="quantite" data-sortable="true">Quantité</th>

                                    <th data-field="date" data-sortable="true">date</th>
                                    </thead>

                                    <tbody id="bodyTableProduit">
                                    <?php echo $entrees ; ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="tab-pane" id="sortieBoutique">
                            <div class="container-fluid">

                                <div class="toolbar-table clear">
                                    <select name="trieSortie1" onchange="trieSortie1(this.value)" id="trieSortie1" class="form-control" style="display: inline-block; width: 17%">
                                        <option value="">Trier par </option>
                                        <option value="1">Cette journée</option>
                                        <option value="2">Ce mois</option>
                                        <option value="3">Cette année</option>
                                        <option value="4">Autre</option>
                                    </select>
                                    
                                <select name="produitsListe" id="produitsListe" onchange="triByProduit(this.value, 2)" class="form-control select" style="display: inline-block; width: 30%">
                                    <option value="">Trier par produit</option>
                                    <?php  foreach ($produits as $produit) : ?>
                                    <option value="<?php echo $produit->proid ?>"><?php echo ucfirst($produit->prodesignation) ?></option>
                                    <?php   endforeach; ?>
                                </select>
                                    <select name="triProduit" id="triProduit" style="display: inline-block; width: 22%" class="form-control" onchange="printProduit(this.value)">
                                        <option value=""><i class="fa fa-print"></i> Impression</option>
                                        <option value="1">En stock</option>
                                        <option value="2">En alerte de stock</option>
                                        <option value="3">En rupture</option>
                                    </select>
                                    <div class="right">
                                        <a href="boutique/entree" class="btn btn-primary" style="display: inline-block;"><i class="fa fa-minus-circle text-danger" style="font-size: 17px"></i> Nouvelle sortie</a>
                                    </div>
                                </div>

                                <div class="fresh-table toolbar-color-azure">

                                    <table id="boutiqueSorties-table" class="table table-responsive table-bordered">
                                        <thead>
                                        <th data-field="id">Ref.</th>

                                        <th data-field="name" data-sortable="true">Designation</th>

                                        <th data-field="mouvement" data-sortable="true">Mouvement</th>

                                        <th data-field="quantite" data-sortable="true">Quantité</th>

                                        <th data-field="date" data-sortable="true">date</th>
                                        </thead>

                                        <tbody id="bodyTableProduit">
                                        <?php echo $sorties; ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
