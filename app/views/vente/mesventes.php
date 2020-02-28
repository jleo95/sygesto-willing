<?php
/**
 * Created by PhpStorm.
 * User: LEOBA / Willing
 * Date: 09/11/2018
 * Time: 01:00
 */
?>

<div class="views-mesventes">
    <div class="container-table">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="triMesventes">Trier par: </label>
                        <select name="triMesventes" id="triMesventes" class="form-control" onchange="triVente(this.value)">
                            <option value="">Trier</option>
                            <option value="1">la journée</option>
                            <option value="2">Ventes du mois</option>
                            <option value="3">De l'année</option>
                            <!--<option value="o">Choisir une date</option>-->
                        </select><br>
                        <a href="javascript: void(0)" id="btnTriCalendarOneDate" class="btn btn-default">Chosir une date</a>
                        <br><br>
                        <a href="javascript: void(0)" id="btnTriCalendar" class="btn btn-default">Chosir une période</a>
                        <br>
                        <br>
                        <label for="triMesventes">Impression d'état: </label>
                        <select name="triMesventes" id="triMesventes" class="form-control" onchange="printVente(this.value)">
                            <option value="">Imprimer les ventes:</option>
                            <option value="1">De la journée</option>
                            <option value="2">Du mois</option>
                            <option value="3">De l'année</option>
                            <!--<option value="o">Choisir une date</option>-->
                        </select><br>
                        <a href="javascript: void(0)" id="btnPrintCalendarOneDate" class="btn btn-default">Chosir une date</a>
                        <br><br>
                        <a href="javascript: void(0)" id="btnPrintCalendar" class="btn btn-default">Chosir une période</a>
                        <br>
                        <br>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="fresh-table toolbar-color-azure">

                        <div class="toolbar">
                            <?php if (in_array('101', $_SESSION['stkdroits'])) : ?>
                                <a href="vente/add" class="btn btn-primary">Nouvelle vente</a>
                            <?php else: ?>
                                <button disabled class="btn btn-primary disabled">Nouvelle vente</button>
                            <?php endif; ?>
                            <!--                <button id="alertBtn" class="btn btn-default">Nouveau mesventes</button>-->
                        </div>

                        <input type="hidden" name="hiddenIdMesventes" id="hiddenIdMesventes">
                        <table id="mesventes-table" class="table table-responsive table-bordered">
                            <thead>
                                <th data-field="id">Num</th>
                                <!--                    <th data-field="id" data-sortable="false">ID</th>-->
                                <th data-field="name" data-sortable="true">Client</th>

                                <th data-field="date" data-sortable="true">Date</th>

                                <th data-field="prix" data-sortable="true">Prix totaux</th>

                                <th data-field="actions" data-formatter="operateFormatter" data-events="operateEvents">Actions</th>
                            </thead>

                            <tbody id="bodyTableMesventes">
                            <?php echo $tbodyMesventes; ?>
                            </tbody>
                        </table>

                    </div>
                    <div class="details">
                        <div class="row">
                            <div class="totaux col-md-6" style="font-size: 17px">
                                <strong>Dépenses: </strong> .......... <span class="values-depense"><?php echo number_format($valuesprix['depenses'], 2, ',', ' ') . ' fcfa'?></span>
                            </div>
                            <div class="totaux col-md-6" style="font-size: 17px">
                                <strong>Bénéfices: </strong> .......... <span class="values-benefice"><?php echo number_format($valuesprix['benefices'], 2, ',', ' ') . ' fcfa'?></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"></div>
                            <div class="totaux col-md-6" style="font-size: 17px">
                                <strong>Totales ventes: </strong> .......... <span class="values-totaux"><?php echo number_format($valuesprix['totaux_ventes'], 2, ',', ' ') . ' fcfa'?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!--        modal infos mesventes-->
        <div class="modal" id="showAllInfosMesventes">
            <div class="modal-dialog moyen-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Infos mesventes</h4>
                    </div>
                    <div class="modal-body">
                        show infos mesventes
                    </div>
                </div>
            </div>
        </div>

        <!--        modal editer un mesventes-->
        <div class="modal" id="editeMesventes">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Editer un mesventes</h4>
                    </div>
                    <div class="modal-body">
                        editer
                    </div>
                </div>
            </div>
        </div>

<!--        modal add vente-->
        <div class="modal" id="addProuit">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Nouveau mesventes</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-form-AddMesventes">
                            <div class="errorAdd"></div>
                            <form action="" method="post" id="formAddMesventes">
                                <div class="form-group">
                                    <span class="fa fa-check"></span>
                                    <label for="designAddMesventes">Designation</label>
                                    <input type="text" name="designAddMesventes" id="designAddMesventes" value="Savon azure" class="form-control" placeholder="nom du mesventes" required>
                                    <span class="help-block"></span>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="prixachatAddMesventes">Prix d'achat</label>
                                            <input type="number" name="prixachatAddMesventes" id="prixachatAddMesventes" value="450" class="form-control" placeholder="prix unitaire d'achat" required>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="fournisseurAddMesventes">Fournisseur</label>
                                            <select name="fournisseurAddMesventes" id="fournisseurAddMesventes" class="form-control">
                                                <!--                                                <option value="">Fournisseurs</option>-->
                                                <?php foreach ($fournisseurs as $f) : ?>
                                                    <option value="<?php echo $f->fouid; ?>"><?php echo ucfirst($f->founom) . ' ' . ucfirst($f->fouprenom) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="prixUnitVenteAddMesventes">Prix unitaire de vente</label>
                                            <input type="number" name="prixUnitVenteAddMesventes" value="500" id="prixUnitVenteAddMesventes" class="form-control" placeholder="prix unitaire de vente" required>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="prixGlobVenteAddMesventes">Prix de vente global</label>
                                            <input type="number" name="prixGlobVenteAddMesventes" id="prixGlobVenteAddMesventes" class="form-control" placeholder="prix de vente globale">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nbblogAddMesventes">Nbre de mesventes par blog</label>
                                            <input type="text" name="nbblogAddMesventes" value="50" id="nbblogAddMesventes" class="form-control" placeholder="nombre de mesventes par blog">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="peremptionAddMesventes">Date de peremption</label>
                                            <input type="text" name="peremptionAddMesventes" id="peremptionAddMesventes" class="form-control" placeholder="le date d'expiration d'un mesventes" required>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="familleAddMesventes">Famille</label>
                                            <select name="familleAddMesventes" id="familleAddMesventes" class="form-control">
                                                <!--                                                <option value="">Famille du mesventes</option>-->
                                                <?php foreach ($familles as $famille) : ?>
                                                    <option value="<?php echo $famille->famid; ?>"><?php echo $famille->famlibelle; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="uniteAddMesventes">Unité de mesure</label>
                                            <select name="uniteAddMesventes" id="uniteAddMesventes" class="form-control" required>
                                                <!--                                                <option value="">Unité de mesure</option>-->
                                                <?php foreach ($unites as $unite) : ?>
                                                    <option value="<?php echo $unite->uniid; ?>"><?php echo $unite->unilibelle ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="seuilAddMesventes">Seuil d'alerte (stock)</label>
                                            <input type="text" name="seuilAddMesventes" id="seuilAddMesventes" value="5" class="form-control" placeholder="par exemple 5">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="clear">
                                    <div class="btn-group right">
                                        <button type="submit" class="btn btn-primary right" id="btnSubFormAdd">Ajouter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="triCalendar">
            <div class="modal-dialog small-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Chosir une période</h4>
                    </div>
                    <form name="formChoiceDateTrie" id="formChoiceDateTrie" method="post">
                        <div class="modal-body">
                                <div class="errorCalendarDate" style="display: none;">
                                    <div class="alert alert-danger"><strong>Attention !</strong> Veillez choisir une periode (date debut et fin)</div>
                                </div>
                                <div class="row" style="height: 150px">
                                    <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                                        <div class="form-group">
                                            <label for="startDateTrieVente">Debut: </label>
                                            <input class="datepicker form-control" id="startDateTrieVente" type="text"/>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                                        <div class="form-group">
                                            <label for="endDateTrieVente">Debut: </label>
                                            <input class="datepicker form-control disabled"  disabled="true" id="endDateTrieVente" type="text"/>
                                        </div>
                                    </div>
                                </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="validateCalendar4" class="btn btn-primary">Valider</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                        </div>
                    </form>
                    
                    <script type="text/javascript">
                        $('#btnTriCalendar').click(function () {
                            $('#startDateTrieVente').val('');
                            $('#endDateTrieVente').val('');
                            $('#triCalendar').modal('show');
                        });
                        $.extend($.fn.pickadate.defaults, {
                            monthsFull: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                            weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                            today: 'aujourd\'hui',
                            clear: 'effacer',
                            formatSubmit: 'yyyy/mm/dd'
                          });
                        $('#startDateTrieVente').pickadate({
                            format: 'dd/mm/yyyy',
                            formatSubmit: 'yyyy/mm/dd'
                        });

                        $('#startDateTrieVente').change(function () {
                            if ($(this).val() != '') {
                                var dateVal = $(this).val();
                                var d = dateVal.substr(0, 2),
                                    m = dateVal.substr(3, 2),
                                    y = dateVal.substr(6, 4);
                                $('#endDateTrieVente').pickadate({
                                    min: new Date(y,m,d),
                                    format: 'dd/mm/yyyy',
                                    formatSubmit: 'yyyy/mm/dd'
                                });
                                $('#endDateTrieVente').removeAttr('disabled');
                            }else {
                                $('#endDateTrieVente').attr('disabled');
                            }
                        });

                        $('#formChoiceDateTrie').submit(function(e) {
                            e.preventDefault();

                            console.log('soumit');

                            if ($('#startDateTrieVente').val() != '' && $('#endDateTrieVente').val() != '') {
                              $.ajax({
                                    url: 'vente/trieVente',
                                    type: 'post',
                                    dataType: 'json',
                                    data: {
                                        idTrie: 'o',
                                        startDate: $('#startDateTrieVente').val(),
                                        endDate: $('#endDateTrieVente').val()
                                    },
                                    success: function (data) {
                                        console.log(data);
                                        $table.bootstrapTable('removeAll');
                                        $table.bootstrapTable('append', data.ventes);
                                        $('#triCalendar').modal('hide');
                                        $('.values-benefice').text(data.benefices);
                                        $('.values-depense').text(data.depenses);
                                        $('.values-totaux').text(data.totaux_ventes);
                                    },
                                    error: function (xhr) {
                                        alert('Erreur de chargement veillez réessayer plutard');
                                    }
                                });   
                            } else {
//                                    $('#formChoiceDateTrie .errorCalendarDate').fadeIn();
                            }
                        });
                        
                        $('#btnTriCalendarOneDate').click(function() {
    
                        });
                    </script>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="triCalendarOneDate">
            <div class="modal-dialog small-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Chosir une période</h4>
                    </div>
                    <form name="formChoiceDateTrieOneDate" id="formChoiceDateTrieOneDate" method="post">
                        <div class="modal-body">
                            <div class="errorCalendarDate" style="display: none;">
                                <div class="alert alert-danger"><strong>Attention !</strong> Veillez choisir une date</div>
                            </div>
                            <div class="row" style="height: 150px">
                                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                                    <div class="form-group">
                                        <label for="startDateTrieVente">Date: </label>
                                        <input class="datepicker form-control" id="dateTrieVente" type="text" placeholder="veillez chosir une date"/>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="validateCalendar4" class="btn btn-primary">Valider</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                        </div>
                    </form>
                    
                    <script type="text/javascript">
                        $('#btnTriCalendarOneDate').click(function () {
                            $('#dateTrieVente').val('');
                            $('#triCalendarOneDate').modal('show');
                        });
                        $.extend($.fn.pickadate.defaults, {
                            monthsFull: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                            weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                            today: 'aujourd\'hui',
                            clear: 'effacer',
                            formatSubmit: 'yyyy/mm/dd'
                          });
                        $('#dateTrieVente').pickadate({
                            format: 'dd/mm/yyyy',
                            formatSubmit: 'yyyy/mm/dd'
                        });
                        

                        $('#formChoiceDateTrieOneDate').submit(function(e) {
                            e.preventDefault();

                            if ($('#dateTrieVente').val() != '') {
                              $.ajax({
                                    url: 'vente/trieVente',
                                    type: 'post',
                                    dataType: 'json',
                                    data: {
                                        idTrie: 'd',
                                        date: $('#dateTrieVente').val()
                                    },
                                    success: function (data) {
                                        $table.bootstrapTable('removeAll');
                                        $table.bootstrapTable('append', data.ventes);
                                        $('#triCalendarOneDate').modal('hide');
                                        $('.values-benefice').text(data.benefices);
                                        $('.values-depense').text(data.depenses);
                                        $('.values-totaux').text(data.totaux_ventes);
                                    },
                                    error: function (xhr) {
                                        alert('Erreur de chargement veillez réessayer plutard');
                                    }
                                });   
                            } else {
                                    $('#formChoiceDateTrieOneDate .errorCalendarDate').fadeIn();
                            }
                        });
                    </script>
                </div>
            </div>
        </div>

<!--        modal triCalendar print-->
        <div class="modal fade" id="printCalendar">
            <div class="modal-dialog small-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Impression</h4>
                    </div>
                    <form name="formChoiceDatePrint" id="formChoiceDatePrint" action="vente/impression_vente" target="_blank" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="idPrint" value="o">
                            <div class="errorCalendarPrintDate" style="display: none;">
                                <div class="alert alert-danger"><strong>Attention !</strong> Veillez choisir une periode (date debut et fin)</div>
                            </div>
                            <div class="row" style="height: 150px">
                                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                                    <div class="form-group">
                                        <label for="startDatePrintVente">Debut: </label>
                                        <input class="datepicker form-control" name="startDatePrintVente" id="startDatePrintVente" type="text"/>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                                    <div class="form-group">
                                        <label for="endDatePrintVente">Debut: </label>
                                        <input class="datepicker form-control disabled" name="endDatePrintVente"  disabled="true" id="endDatePrintVente" type="text"/>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="validatePrintCalendar4" name="validatePrintCalendar4" class="btn btn-primary">Imprimer</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                        </div>
                    </form>

                    <script type="text/javascript">
                        $('#btnPrintCalendar').click(function () {
                            $('#startDatePrintVente').val('');
                            $('#endDatePrintVente').val('');
                            $('#printCalendar').modal('show');
                        });
                        $.extend($.fn.pickadate.defaults, {
                            monthsFull: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                            weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                            today: 'aujourd\'hui',
                            clear: 'effacer',
                            formatSubmit: 'yyyy-mm-dd'
                        });
                        $('#startDatePrintVente').pickadate({
                            format: 'dd/mm/yyyy',
                            formatSubmit: 'yyyy-mm-dd'
                        });

                        $('#startDatePrintVente').change(function () {
                            if ($(this).val() != '') {
                                var dateVal = $(this).val();
                                var d = dateVal.substr(0, 2),
                                    m = dateVal.substr(3, 2),
                                    y = dateVal.substr(6, 4);
                                $('#endDatePrintVente').pickadate({
                                    min: new Date(y,m,d),
                                    format: 'dd/mm/yyyy',
                                    formatSubmit: 'yyyy-mm-dd'
                                });
                                $('#endDatePrintVente').removeAttr('disabled');
                            }else {
                                $('#endDatePrintVente').attr('disabled');
                            }
                        });

                        $('#formChoiceDatePrint').submit(function(e) {
                            // e.preventDefault();

                            console.log('soumit');

                            if ($('#startDatePrintVente').val() != '' && $('#endDatePrintVente').val() != '') {
                                $('#printCalendar').modal('hide');
                                return true;
                            }

                            return false;
                        });

                        $('#btnTriCalendarOneDate').click(function() {

                        });
                    </script>
                </div>
            </div>
        </div>

<!--        modal triCalendar print one date-->
        <div class="modal fade" id="printCalendarOneDate">
            <div class="modal-dialog small-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Impression</h4>
                    </div>
                    <form name="formChoiceDatePrintOneDate" action="vente/impression_vente" target="_blank" id="formChoiceDatePrintOneDate" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="idPrint" value="d">
                            <div class="errorCalendarDate" style="display: none;">
                                <div class="alert alert-danger"><strong>Attention !</strong> Veillez choisir une date</div>
                            </div>
                            <div class="row" style="height: 150px">
                                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                                    <div class="form-group">
                                        <label for="startOneDatePrintVente">Date: </label>
                                        <input class="datepicker form-control" id="startOneDatePrintVente" name="startOneDatePrintVente" type="text" placeholder="veillez chosir une date"/>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="validateCalendar4" class="btn btn-primary">Valider</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                        </div>
                    </form>

                    <script type="text/javascript">
                        $('#btnPrintCalendarOneDate').click(function () {
                            $('#startOneDatePrintVente').val('');
                            $('#printCalendarOneDate').modal('show');
                        });
                        $.extend($.fn.pickadate.defaults, {
                            monthsFull: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                            weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                            today: 'aujourd\'hui',
                            clear: 'effacer',
                            formatSubmit: 'yyyy-mm-dd'
                        });
                        $('#startOneDatePrintVente').pickadate({
                            format: 'dd/mm/yyyy',
                            formatSubmit: 'yyyy-mm-dd'
                        });


                        $('#formChoiceDatePrintOneDate').submit(function(e) {
                            // e.preventDefault();

                            if ($('#startOneDatePrintVente').val() != '') {
                                $('#printCalendarOneDate').modal('hide');
                                return true;
                            } else {
                                $('#formChoiceDatePrintOneDate .errorCalendarDate').fadeIn();
                                return false;
                            }
                        });
                    </script>
                </div>
            </div>
        </div>

    </div>

    <?php
    if (isset($success) AND $success == true) {
        ?>
        <script>
            $.gritter.add({
                title: 'Félicitation',
                text: 'Un nouveau mesventes a été ajouté',
                image: 'assets/img/confirm.png'
            });
        </script>
        <?php
    }
    ?>
</div>
