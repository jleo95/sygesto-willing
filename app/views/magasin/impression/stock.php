<?php

$title = '<h1 align="center" style="font-size: 15px;">Liste des produit en rupture stock (Magasin)</h1>';
if ($printable == 1) {
    $title = '<h1 align="center" style="font-size: 15px;">Liste des produit en stock (Magasin)</h1>';
}elseif ($printable == 2) {
    $title = '<h1 align="center">Liste des produit en alerte stock (Boutique)</h1>';
}

$table = '<table border="1" cellspacing="0" cellpadding="3">' .
            '<tr>' .
                '<th width="7%">Ref.</th>' .
                '<th width="34%">Designation</th>' .
                '<th width="12%">Famille</th>' .
                '<th width="17%">P. Achat</th>' .
                '<th width="17%">P. Vente</th>' .
                '<th width="13%">Qté.</th>' .
            '</tr>';
foreach ($produits as $produit) {
    $table .= '<tr>' .
                '<td>' . $produit->proid . '</td>' .
                '<td>' . ucfirst($produit->prodesignation) . '</td>' .
                '<td>' . $produit->famille . '</td>' .
                '<td>' . number_format($produit->proprixUnitAchat, 2, ',', ' ') . '</td>' .
                '<td>' . number_format($produit->proprixUnitVente, 2, ',', ' ') . '</td>';

    if ($printable == 1) {
        $table .= '<td>' . $stock_function->stock_magasin_by_produit($produit->proid) . '</td>';
    }elseif ($printable == 2) {
        $table .= '<td><span class="text-warning">' . $stock_function->stock_magasin_by_produit($produit->proid) . '</span> <span style="font-font-style:italic;; font-size: 10px;">(en alert)</span></td>';
    }else {
        $table .= '<td><span class="text-danger" style="font-font-style:italic;; font-size: 10px;">en rupture</span></td>';
    }
    $table .= '</tr>';
}
$table .= '</table';



//echo $table;
//
//die();

$pdf->SetPrintFooter(true);
$pdf->AddPage();
$pdf->setFontSize(13);
$pdf->WriteHTMLCell(0, 0, 23, 45, $title);
$pdf->setFontSize(10.5);
//$pdf->WriteHTMLCell(190, 0, 10, 60, $table);
$pdf->WriteHTMLCell(0, 5, 14, 60, $table);
if (count($produits) <= 0) {
    $empty = '<p style="font-size: 15px; text-align: center">Aucun resultat</p>';
    $pdf->WriteHTMLCell(190, 0, 10, 80, $empty);
}

$pdf->OutPut();