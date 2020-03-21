<div class="view-livraison">


    <div class="container-table">
        <div>
            <div class="row">
                <div class="col-md-3">
                    <label for="trier">Trier par</label><br>
                    <select name="trierLivraison" id="trierLivraison" class="form-control" style="width: 210px; display: inline-block;">
                        <option value="0">Trier par :</option>
                        <option value="1">Dernier mois</option>
                        <option value="2">Trois dernier mois</option>
                        <option value="3">L'année</option>
                        <option value="4">Autres</option>
                    </select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
                <div class="col-md-3">
                    <label for="impression">Imprimer: </label><br>
                    <select name="imprimerLivraison" id="imprimerLivraison" class="form-control" style="width: 210px; display: inline-block;" onchange="printLivraison(this.value)">
                        <option value="0">Imprimer les livraison :</option>
                        <option value="1">Du dernier mois</option>
                        <option value="2">Des trois dernier mois</option>
                        <option value="3">De toute l'année</option>
                        <option value="4">Toutes</option>
                        <option value="5">Autres</option>
                    </select>
                    <!--                    <a href="livraison/imprimer" class="btn btn-default text-primary" title="imprimer la liste de livraison" target="_blank"><i class="fa fa-print"></i></a>-->
                </div>
            </div>

        </div>
        <br>
        <div class="container-fluid">

            <div class="row">

                <div class="col-md-12">
                    <div class="fresh-table toolbar-color-azure">

                        <div class="toolbar">
                            <?php if (in_array('701', $_SESSION['stkdroits'])) : ?>
                                <a href="livraison/ajout" title="passer une commande" class="btn btn-round">Nouvelle livraison</a>
                            <?php else: ?>
                                <button disabled class="btn btn-primary disabled">Nouvelle commande</button>
                            <?php endif; ?>
                            <!--                <button id="alertBtn" class="btn btn-default">Nouveau commande</button>-->
                        </div>

                        <input type="hidden" name="hiddenIdcommande" id="hiddenIdcommande">
                        <table id="chargement-table" class="table table-responsive table-bordered">
                            <thead>
                            <th data-field="line">#</th>
                            <th data-field="offshore">Offshore</th>
                            <th data-field="dateoffshore">Dte fin offshore</th>
                            <th data-field="cmd">Num commande</th>
                            <th data-field="datecmd">Date cmd</th>
                            <th data-field="produit">Produit</th>
                            <th data-field="qte">Qté livriée</th>
                            <th data-field="qte2">Qté restante</th>
                            <th data-field="unite">Unité</th>
                            </thead>
                            <tbody id="bodyTableListLivraisons">
                            <?php echo $chargements; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal" id="choisirPeriode">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Produit</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type='radio' name='dateTrie' id='date' value="1">
                        <label for="date">Choisir une date: </label><br>
                        <input type="text" name="date" id="dateLivraison" placeholder="date" class="form-control">
                        <div class="error-date" style="padding: 5px; text-align: center;"></div>
                    </div><br>
                    <div class="form-group">
                        <input type='radio' name='dateTrie' id='periode' value="2">
                        <label for="periode">Choisir une période: </label>
                        <br>
                        <input type="text" name="dateDebut" id="dateDebut" placeholder="date de debut" class="form-control"><br>
                        <input type="text" name="dateFin" id="dateFin" placeholder="date de fin" class="form-control" disabled>
                        <div class="error-periode" style="padding: 5px; text-align: center;"></div>
                    </div>
                    <div class="error" style="padding: 5px; text-align: center;"></div>
                    <div class="btn-div" style='text-align: center;'>
                        <input type='button' id='btnTrieLivraison' class='btn btn-primary' value='Trier'>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!--    modal pour l'impression-->
    <div class="modal" id="choisirPeriodemprimerLivraison" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Produit</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type='radio' name='dateImpression' id='dateImpression' value="1">
                        <label for="dateImpression">Choisir une date: </label><br>
                        <input type="text" name="dateImpression" id="dateImpressionLivraison" placeholder="date" class="form-control">
                        <div class="error-date" style="padding: 5px; text-align: center;"></div>
                    </div><br>
                    <div class="form-group">
                        <input type='radio' name='dateImpression' id='impressionPeriode' value="2">
                        <label for="impressionPeriode">Choisir une période: </label>
                        <br>
                        <input type="text" name="dateDebutImpression" id="dateDebutImpression" placeholder="date de debut" class="form-control"><br>
                        <input type="text" name="dateFinImpression" id="dateFinImpression" placeholder="date de fin" class="form-control" disabled>
                        <div class="error-periode" style="padding: 5px; text-align: center;"></div>
                    </div>
                    <div class="error" style="padding: 5px; text-align: center;"></div>
                    <div class="btn-div" style='text-align: center;'>
                        <input type='button' id='btnImpressionLivraison' class='btn btn-primary' value='Imprimer'>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>


