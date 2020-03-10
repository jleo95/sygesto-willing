<?php $i = 1; foreach ($commandes as $commande): ?>
    <tr>
        <td><?php echo $i++ ?></td>
        <td><?php echo 'CMD#' . $commande->offid ?></td>
        <td><?php echo substr($commande->offshore, 0, 50) . ' ...'?></td>
        <td><?php echo formatDate($commande->offdate, 'd/m/yy') ?></td>
        <td></td>
    </tr>

<?php endforeach; ?>