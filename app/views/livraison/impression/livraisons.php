<?php
$pdf->addPage();

$table = '<table border="1" cellspacing="0" cellpadding="1">';
$table .= '<tr style="background-color:#fefefe; padding: 2px;">' .
                '<th>#</th>' .
                '<th>Offshore</th>' .
                '<th>Dte fin offshore</th>' .
                '<th>Num. commande</th>' .
                '<th>Date cmd.</th>' .
                '<th>Produit</th>' .
                '<th>Qté. livriée</th>' .
                '<th>Qté. restante</th>' .
                '<th>Unite</th>' .
            '</tr>';
$table .= $data;
$table .= '</table>';


$header = '<h1 align="center" style="font-size: 15px; text-decoration: underline;">Liste des livraisons</h1>';
$pdf->setFontSize(13);
$pdf->WriteHTMLCell(0, 0, 23, 25, $header);
$pdf->setFontSize(10.5);
$pdf->WriteHTMLCell(0, 0, 23, 45, $table);

$pdf->outPut('list_livraison.pdf', 'I');