<?php
/**
 * Created by PhpStorm.
 * User: LEOBA / Willing
 * Date: 20/11/2018
 * Time: 16:07
 */
?>

<?php
//var_dump($offshore);
foreach ($offshore as $offshores) {
//    if (calculDate($produit->prodatePeremption) > 0)
    ?>
    <tr>
        <td><?php  echo $offshores->offid;?></td>
        <td><?php  echo $offshores->offdescription; ?></td>
        <td><?php echo $offshores->empnom.' '.ucfirst(substr($offshores->empprenom, 0,1)).'.' ?></td>
        <td><span style="font-style: italic;"><?php echo  $offshores->clinom.' '.$offshores->cliprenom; ?></span></td>
        <td><span style="font-style: italic;"><?php echo substr($offshores->offdatedebut, 0,10) ?></span></td>
        <td><?php 
        //$dateNow = date("Y-m-d H:i:s");
        //$dateInit = strtotime($offshores->offdatefin);
        //$dateInit = date('Y-m-d H:i:s', $dateInit);
        //echo date_diff($dateInit, $dateNow); 
        $date_expire = $offshores->offdatefin;    
        $date = new DateTime($date_expire);
        $now = new DateTime();
        if  (calculDate($offshores->offdatefin) > 0){
            echo $date->diff($now)->format("%d J, %h H");  
        }
        else{
            echo "Cloturé";
    }?>

            
        </td>
        <td></td>
    </tr>

    <?php
}

if (isset($script) AND $script) :
    ?>

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
                url: 'offshores/laodDataForDeleteOffshore',
                type: 'post',
                dataType: 'json',
                data: {idProduit: row.id},
                success: function (data) {
                    nameProduit = data.nameProduit;
                    $('#deletOffshore .modal-body').html(data.modalBody);
                    $('#deletOffshore .modal-content .modal-header h4').html('Suppression');
                },
                error: function (xhr) {
                    alert('Erreur de chargement');
                }
            });
            // console.log(rowProduit);
            // $table.bootstrapTable('remove', {
            //     field: 'id',
            //     values: [row.id]
            // });
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
            '<a rel="tooltip" title="Supprimer" class="table-action remove-produit text-danger" data-toggle="modal" href="#deletOffshore">',
            '<i class="fa fa-trash-o"></i>',
            '</a>'
        ].join('');
    }
</script>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script type="text/javascript" src="countdown3.js"></script>
  
<?php endif; ?>
