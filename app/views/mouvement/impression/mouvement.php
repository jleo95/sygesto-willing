<?php

$pdf->AddPage();

$title = '<h2 align="center">Liste des produits ';
if ($printable == 1) {
    $title .= 'en stock';
}elseif ($printable == 2) {
    $title .= 'en alerte';
} else {
    $title .= 'en rupture';
}
$title .= ' (mouvement)</h2>';


$table = '<table id="tableRecu" border="1" style="border-spacing: 0; border-collapse: collapse; width: 100%; border: .1px solid #111; ">' .
    '<tr>' .
    '<th style="width: 10%; border: .1px solid #111;">Ref.</th>' .
    '<th style="width: 44%; border: .1px solid #111;">Designation</th>' .
    '<th style="width: 11%; border: .1px solid #111;">Mvt.</th>' .
    '<th style="width: 11%; border: .1px solid #111;">Quant.</th>' .
    '<th style="width: 11%; border: .1px solid #111;">U. mesure</th>' .
    '<th style="width: 15%; border: .1px solid #111;">Date</th>' .
    '</tr>';
foreach ($entrees as $entree) {
    
    $table .= '<tr>';
    $table .= "<td>{$entree->proid}</td>";
    $table .= "<td>{$entree->prodesignation}</td>";
    $table .= '<td><span class="text-success"><i class="fa fa-sign-out"></i>Entrée</span></td>';
    $table .= "<td>{$entree->mvtquantite}</td>";
    $table .= "<td>{$entree->uniabv}</td>";
    $table .= "<td>{$entree->mvtdate}</td>";
    $table .= '</tr>';
}

$table .= '</table>';

$pdf->WriteHTMLCell(0, 0,10, 50 , $title);
//$pdf->WriteHTMLCell(100, 0,10, 70 , $table);
$pdf->WriteHTMLCell(180, 5, 14, 60, $table);

$pdf->output('stock.pdf', 'I');