<?php
/**
 * Created by PhpStorm.
 * User: LEOBA / Willing
 * Date: 20/11/2018
 * Time: 16:07
 */
?>

<?php
$i = 0;
foreach ($produits as $produit) {
//    if (calculDate($produit->prodatePeremption) > 0)
    ?>
    <tr>
        <td><?php echo $produit->proid ?></td>
        <td><?php echo $produit->prodesignation; ?></td>
        <td><?php echo $produit->famille ?></td>
        
        <!--td><?php //if ($stock_function->stock_boutique_by_produit($produit->proid) == 0) { ?>
            <span style="font-style: italic; font-size: 12px;">rupture</span>
            <?php
        //} //else {
           // echo $stock_function->stock_boutique_by_produit($produit->proid);
        //}
        ?>
        </td-->
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
<?php endif; ?>
