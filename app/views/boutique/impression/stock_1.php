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
$title .= ' (boutique)</h2>';


$table = '<table id="tableRecu" border="1" style="border-spacing: 0; border-collapse: collapse; width: 100%; border: .1px solid #111; ">' .
    '<tr>' .
    '<th style="width: 10%; border: .1px solid #111;">Ref.</th>' .
    '<th style="width: 44%; border: .1px solid #111;">Designation</th>' .
    '<th style="width: 11%; border: .1px solid #111;">Famille.</th>' .
    '<th style="width: 11%; border: .1px solid #111;">Quant.</th>' .
    '<th style="width: 15%; border: .1px solid #111;">Un. mesure</th>' .
    '</tr>';
foreach ($produits as $produit) {
    $stk = $stock->stock_boutique_by_produit($produit->proid);
    $stock_span = '';
    
    if ($stk > 0 AND $stk > $produit->proseuilalert) {
        $stock_span = '<span>' . $stk . '</span>';
    }elseif ($stk > 0 AND $stk <= $produit->proseuilalert) {
        $stock_span = '<span>' . $stk . ' (en alert)</span>';
    } else {
        $stock_span = '<span>en rupture</span>';
    }
    
    $table .= '<tr>';
    $table .= "<td>{$produit->proid}</td>";
    $table .= "<td>{$produit->prodesignation}</td>";
    $table .= "<td>{$produit->famille}</td>";
    $table .= "<td>{$stock_span}</td>";
    $table .= "<td>{$produit->unite}</td>";
    $table .= '</tr>';
}

$table .= '</table>';

$pdf->WriteHTMLCell(0, 0,10, 50 , $title);
//$pdf->WriteHTMLCell(100, 0,10, 70 , $table);
$pdf->WriteHTMLCell(200, 5, 14, 60, $table);

$pdf->output('stock.pdf', 'I');