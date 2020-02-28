<?php

$d = new App\Core\DateFR();

$title = '<h1 align="center" style="font-size: 15px;">Liste des produits</h1>';
$table = '<table border="1" cellspacing="0" cellpadding="3">' .
                '<tr>' .
                    '<th width="7%">Ref.</th>' .
                    '<th width="32%">Designation</th>' .
                    '<th width="12%">Famille</th>' .
                    '<th width="15%">Date Peremp.</th>' .
                    '<th width="17%">P. Achat</th>' .
                    '<th width="17%">P. Vente</th>' .
                '</tr>';
foreach ($produits as $produit) {
    $d->setSource($produit->prodatePeremption);
    $table .= '<tr>' .
                '<td align="left">' . $produit->proid . '</td>' .
                '<td align="left">' . ucfirst($produit->prodesignation) . '</td>' .
                '<td align="left">' . $produit->famille . '</td>' .
                '<td align="left">' . date('d/m/Y', strtotime($produit->prodatePeremption)) . '</td>' .
                '<td align="left">' . number_format($produit->proprixUnitAchat, 2, ',', ' ') . '</td>' .
                '<td align="left">' . number_format($produit->proprixUnitVente, 2, ',', ' ') . '</td>' .
              '</tr>';
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
$pdf->WriteHTMLCell(0, 5, 14, 60, $table);
if (count($produits) <= 0) {
    $empty = '<p style="font-size: 15px; text-align: center">Aucun resultat</p>';
    $pdf->WriteHTMLCell(190, 0, 10, 80, $empty);
}

$pdf->OutPut('liste_produit.pdf', 'I');