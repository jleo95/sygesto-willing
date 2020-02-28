<?php

$pdf->AddPage();

$table = '<table border="1" cellspacing="0" cellpadding="3">' .
                '<tr style="background-color: #efefef;">' .
                    '<th width="7%">Ref.</th>' .
                    '<th width="44%">Designation</th>' .
                    '<th width="27%">Famille</th>' .
                    '<th width="22%">D. Peremp.</th>' .
                   
                '</tr>';

foreach ($produits as $produit) {
    $table .= '<tr>' .
                '<td align="left">' . $produit->proid . '</td>' .
                '<td align="left">' . ucfirst($produit->prodesignation) . '</td>' .
                '<td align="left">' . $produit->famille . '</td>' .
                '<td align="left">' . date('d/m/Y', strtotime($produit->prodatePeremption)) . '</td>' .
                
              '</tr>';
}

$table .= '</table>';

$title = '<h1 align="center" style="font-size: 15px; text-decoration: underline;">Liste des produits</h1>';
$pdf->setFontSize(13);
$pdf->WriteHTMLCell(0, 0, 23, 45, $title);
$pdf->setFontSize(10.5);
$pdf->WriteHTMLCell(192, 5, 10, 58, $table);

$pdf->outPut('list_produit.pdf', 'I');
