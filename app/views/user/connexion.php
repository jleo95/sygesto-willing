<div class="views-user">
    <div class="container-table">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="fresh-table toolbar-color-azure">

                        <table id="connexion-table" class="table table-responsive table-bordered">
                            <thead>
                                <th data-field="start">Date debut</th>
                                <th data-field="computer">Machine</th>
                                <th data-field="address">Adresse</th>
                                <th data-field="connexion">Connexion</th>
                                <th data-field="end">Date fin</th>
                                <th data-field="connexion">Deconnexion</th>
                            </thead>
                            <tbody id="bodyTableUser">
                            <?php $i = 1; foreach ($connexions as $connexion) : ?>
                                <tr>
                                    <td><?php echo (date('Y-m-d', time()) === date('Y-m-d', strtotime($connexion->datedebut))) ? date('H:i', strtotime($connexion->datedebut)) : $connexion->datedebut; ?></td>
                                    <td><?php echo $connexion->machinesource; ?></td>
                                    <td><?php echo $connexion->ipsource; ?></td>
                                    <td><?php echo $connexion->connexion; ?></td>
                                    <td><?php echo (date('Y-m-d', time()) === date('Y-m-d', strtotime($connexion->datefin))) ? date('H:i', strtotime($connexion->datefin)) : $connexion->datefin; ?></td>
                                    <td><?php echo $connexion->deconnexion ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>