<?php
$pdf->AddPage();

$date = new \App\Core\DateFR($commande->offdate);



$title = '<div class="condiv ">' .
            '<span style="text-decoration: underline; font-size: 12; font-weight: 700;">Date : </span>' .
            '<span>' . $date->getDate() . ' ' . $date->getMois() . ' ' . $date->getYear() . '</span>' .
        '</div>' .
        '<div class="content">' .
            '<span style="text-decoration: underline; font-size: 12; font-weight: 700;">Offshore : </span>' .
            '<span>' . $commande->offshore . '</span>' .
        '</div>' .
        '<div class="content">' .
            '<span style="text-decoration: underline; font-size: 12; font-weight: 700;">Mode de paiement : </span>' .
            '<span>' . $commande->pailibelle . '</span>' .
       '</div>';

$tab = '<table border="1" cellspacing="0" cellpadding="3">' .
            '<tr style="background-color:#ddd;">' .
                '<th>Famille</th>' .
                '<th>Designation</th>' .
                '<th>Quantite</th>' .
                '<th>Unite</th>' .
            '</tr>';

foreach ($groupByfamille as $k => $g) {
    $lenGroupe = count($g);
    $j = 0;
    $class = '';

    if ($j % 2 === 0)
        $color = '#ddd';

    if ($lenGroupe == 1) {
        $tab .= '<tr class="color- ' . $j . '" style="background-color: ' . $class . '">' .
                    '<td>' . $k . '</td>' .
                    '<td>' . $g[0]->produit . '</td>' .
                    '<td>' . $g[0]->quantite . '</td>' .
                    '<td>' . $g[0]->unite . '</td>' .
            '</tr>';
    }else {
//        $tab .= '<tr>'
//            '<td rowspan="' . $lenGroupe . '">' . $k . '</td>';
        foreach ($g as $d) {
            $tab .= '<tr class="color- ' . $j . '" style="background-color: ' . $class . '">' .
                '<td>' . $k . '</td>' .
                '<td>' . $d->produit . '</td>' .
                '<td>' . $d->quantite . '</td>' .
                '<td>' . $d->unite . '</td>' .
                '</tr>';
        }
    }

    $j ++;

}


$tab .= '</table>';

//echo $table;

$header = '<h1 align="center" style="font-size: 15px; text-decoration: underline;">Demande d\'achat: #' . $commande->offid  . '</h1>';
$pdf->setFontSize(13);
$pdf->WriteHTMLCell(0, 0, 23, 25, $header);
$pdf->setFontSize(10.5);
$pdf->WriteHTMLCell(0, 0, 23, 45, $title);
$pdf->WriteHTMLCell(192, 5, 10, 83, $tab);
$pdf->outPut('commande.pdf', 'I');
