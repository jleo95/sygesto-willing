<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 19/12/2018
 * Time: 00:14
 */
?>

<div class="views-mesventes">
    <div class="container-table">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="fresh-table toolbar-color-azure">
                        <table id="statVentes-table" class="table table-responsive table-bordered">
                            <thead>
                            <th data-field="id">Ref.</th>

                            <th data-field="name" data-sortable="true">Designation</th>

                            <th data-field="family" data-sortable="true">Famille</th>

                            <th data-field="date" data-sortable="true">Date de Peremption</th>

                            <th data-field="month" data-sortable="true">D. Mois</th>

                            <th data-field="actions" data-formatter="operateFormatter" data-events="operateEvents"></th>
                            </thead>
                            <tbody>
                                <?php echo $produits ?>
                            </tbody>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
