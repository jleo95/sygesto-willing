<?php
/**
 * Created by PhpStorm.
 * User: antol
 * Date: 3/11/2020
 * Time: 1:22 AM
 */

?>


<div class="views-commande">
    <div class="container-table">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="trierpar">Trier par :</label>
                        <select name="trierpar" id="trierpar" class="form-control" onchange="trieAchat(this.value)">
                            <option value="">Trier</option>
                            <option value="1">Livrées</option>
                            <option value="2">En cours</option>
                            <option value="3">Annulée</option>
                            <option value="4">Suspendu</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="fresh-table toolbar-color-azure">

                        <div class="toolbar">
                            <?php if (in_array('701', $_SESSION['stkdroits'])) : ?>
                                <a href="commande/ajout" title="passer une commande" class="btn btn-round">Nouvelle commande</a>
                            <?php else: ?>
                                <button disabled class="btn btn-primary disabled">Nouvelle commande</button>
                            <?php endif; ?>
                            <!--                <button id="alertBtn" class="btn btn-default">Nouveau commande</button>-->
                        </div>

                        <input type="hidden" name="hiddenIdcommande" id="hiddenIdcommande">
                        <table id="commande-table" class="table table-responsive table-bordered">
                            <thead>
                                <th data-field="line">#</th>
                                <th data-field="id">Num commande</th>
                                <th data-field="contact">Offshore</th>
                                <th data-field="date">Date</th>
                                <th data-field="actions" data-formatter="operateFormatter" data-events="operateEvents">Actions</th>
                            </thead>
                            <tbody id="bodyTableListCommandes">
                            <?php echo $commandes; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>


        <!--        modal supprimer un commande-->
        <div class="modal" id="deletcommande">
            <div class="modal-dialog small-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Supprimer un commande</h4>
                    </div>
                    <div class="modal-body">
                        delete
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deletecommande()">Supprimer</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </div>
        </div>
</div>


<script type="text/javascript">
    window.operateEvents = {
        'click .more-infos': function (e, value, row, index) {
            showAllInfosProduit (row.id);
        },
        'click .edit-produit': function (e, value, row, index) {
            laodForEditeProduit(row.id);
        },
        'click .remove-produit': function (e, value, row, index) {
            rowProduit = row;
            valueProduit = value;
            indexProduit = index;
            actionEven = e;
            $.ajax({
                url: 'produit/laodDataForDeleteProduit',
                type: 'post',
                dataType: 'json',
                data: {idProduit: row.id},
                success: function (data) {
                    nameProduit = data.nameProduit;
                    $('#deletProuit .modal-body').html(data.modalBody);
                    $('#deletProuit .modal-content .modal-header h4').html('Suppression');
                },
                error: function (xhr) {
                    alert('Erreur de chargement');
                }
            });
        }

    };
    function operateFormatter(value, row, index) {
        return [
            '<a rel="tooltip" title="Voir plus" href="#showAllInfosProduit" data-toggle="modal" class="table-action more-infos text-primary" href="javascript:void(0)">',
            '<i class="fa fa-eye"></i>',
            '</a>',
            '<a rel="tooltip" title="Editer" class="table-action edit-produit text-success" data-toggle="modal" href="#editeProduit">',
            '<i class="fa fa-edit"></i>',
            '</a>',
            '<a rel="tooltip" title="Supprimer" class="table-action remove-produit text-danger" data-toggle="modal" href="#deletProuit">',
            '<i class="fa fa-trash-o"></i>',
            '</a>'
        ].join('');
    }
</script>
