

<div class="container-table">
    <div class="container-fluid">
        <div class="col-md-10 col-md-offset-1">
            <div class="fresh-table toolbar-color-azure">
                <div class="toolbar">
                    <a href="javascript: void(0)" class="btn" id="btnGraphiqueProduitVendu">Afficher le graphique</a>
                </div>
                <table id="ventesproduit-table" class="table table-responsive table-bordered">
                    <thead>
                    <th data-field="id">Ref</th>

                    <th data-field="name" data-sortable="true">Designation</th>

                    <th data-field="quantite" data-sortable="true">Quantité</th>

                    <th data-field="prix" data-sortable="true">Prix totaux</th>

                    <th data-field="taux" data-sortable="true">Taux en %</th>
                    </thead>

                    <tbody>
                    <?php echo $tbody ?>
                    </tbody>

                </table>
            </div>

            <div class="modal fade" id="graphiqueModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">x</button>
                            <h4 class="modal-title">Statistique</h4>
                        </div>
                        <div class="modal-body"></div>
                        <div class="modal-footer"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#btnGraphiqueProduitVendu').click(function () {
            $('#graphiqueModal').modal('show');
        });
    })
</script>