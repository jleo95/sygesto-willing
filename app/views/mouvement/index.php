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

            <div class="toolbar-table clear">
                <select name="trieEntree1" onchange="trieEntree1(this.value)" id="trieEntree1" class="form-control select" style="display: inline-block; width: 17%">
                    <option value="">Trier par </option>
                    <option value="1">Cette journée</option>
                    <option value="2">Ce mois</option>
                    <option value="3">Cette année</option>
                    <option value="o">Autre</option>
                </select>
                <select name="produitsListe" id="produitsListe" onchange="triByProduit(this.value, 1)" class="form-control select" style="display: inline-block; width: 30%">
                    <option value="">Trier par produit</option>
                    <?php  foreach ($produits as $produit) : ?>
                        <option value="<?php echo $produit->proid ?>"><?php echo ucfirst($produit->prodesignation) ?></option>
                    <?php   endforeach; ?>
                </select>

                <a href="boutique/impressionMouvement" class="btn btn-default text-primary" target="_blank" style="font-size: 22px;" title="imprimer liste des mouvement"><i class="fa fa-print"></i></a>
                <div class="right">
                    <!--<a href="boutique/entree" class="btn btn-primary" style="display: inline-block;"><i class="fa fa-plus-circle text-success" style="font-size: 17px"></i> Nouvelle entrée boutique</a>-->
                </div>
            </div>
            <div class="fresh-table toolbar-color-azure">

                <div class="toolbarEntree">
                    <!--                <button id="alertBtn" class="btn btn-default">Nouveau produit</button>-->
                    <a href="mouvement/entree" class="btn btn-primary" style="display: inline-block;"><i class="fa fa-plus-circle text-success" style="font-size: 17px"></i> Nouvelle livraison</a>
                </div>

                <table id="boutiqueEntrees-table" class="table table-responsive table-bordered">
                    <thead>
                        <th data-field="id">Ref.</th>

                        <th data-field="name" data-sortable="true">Offshore</th>

                        <th data-field="name" data-sortable="true">Commande</th>

                        <th data-field="mouvement" data-sortable="true">Produit</th>

                        <th data-field="quantite" data-sortable="true">Quantité</th>

                        <th data-field="date" data-sortable="true">date</th>
                    </thead>

                    <tbody id="bodyTableProduit">
                    <?php echo $entrees ; ?>
                    </tbody>
                </table>

            </div>

        </div>

    </div>
    
    <input type="hidden" id="hiddenIdTri" value="00">
       
    <div class="modal" id="triEntreeOtherProduit">
        <div class="modal-dialog moyen-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Chosissez un produit</h4>
                </div>
                <div class="modal-body">
                    <div class="modal-error-entree" style="display: none;">
                        <div class="alert alert-danger">
                            <p><strong>Attention !</strong> Veillez chosir un produit</p>
                        </div>
                    </div>
                    <select name="produitsListe" id="produitsListe" class="form-control">
                        <option value="">Liste des produits</option>
                        <?php  foreach ($produits as $produit) : ?>
                        <option value="<?php echo $produit->proid ?>"><?php echo ucfirst($produit->prodesignation) ?></option>
                        <?php   endforeach; ?>
                    </select>
                </div>
                <div class="modal-footer">
                    <input type="button" value="Trier" name="trieOtherProduit" onclick="trieOther(1)" class="btn btn-primary" />
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="triEntreeOtherFamille">
        <div class="modal-dialog moyen-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Chosissez une famille</h4>
                </div>
                <div class="modal-body">
                    <div class="modal-error-sortie" style="display: none">
                        <div class="alert alert-danger">
                            <p><strong>Attention !</strong> Veillez chosir une famille</p>
                        </div>
                    </div>
                    <select name="familleListe" id="familleListe" class="form-control">
                        <option value="">Liste des familles des produit</option>
                        <?php  foreach ($familles as $famille) : ?>
                        <option value="<?php echo $famille->famid ?>"><?php echo ucfirst($famille->famlibelle) ?></option>
                        <?php   endforeach; ?>
                    </select>
                </div>
                <div class="modal-footer">
                    <input type="button" value="Trier" name="triOtherFamille" onclick="trieOther(2)" class="btn btn-primary" />
                </div>
            </div>
        </div>
    </div>

</div>
