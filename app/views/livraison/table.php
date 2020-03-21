<?php
$line = 1;
//var_dump($chargements);
$date = new \App\Core\DateFR();
foreach ($chargements as $chargement) {
    $date->setSource($chargement->offre_date);
    ?>
    <tr>
        <td><?php echo $line ++; ?></td>
        <td><?php echo '#' . $chargement->offshore_id ?></td>
        <td><?php echo formatDate($chargement->offshore_datef, 'd/m/Y') ?></td>
        <td><?php echo '#' . $chargement->offre ?></td>
        <td><?php echo $date->getDate() . ' ' . $date->getMois() . ' ' . $date->getYear() ?></td>
        <td><?php echo $chargement->produit ?></td>
        <td><?php echo $chargement->quantite ?></td>
        <td><?php echo $chargement->quantite_restante ?></td>
        <td><?php echo $chargement->unite ?></td>
    </tr>

<?php
}
?>

