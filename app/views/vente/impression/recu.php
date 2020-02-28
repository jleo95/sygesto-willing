<?php

$d = new App\Core\DateFR();
$height = count($ventes) * 15;
if (count($ventes) <= 10) {
    $height += 15;
}

if ($height > 105) {
    $height -= 102;
}


$produits = '<table id="tableRecu" style="border-spacing: 0; border-collapse: collapse; width: 100%; border: .1px solid #111; ">' .
    '<tr>' .
    '<th style="width: 10%; border: .1px solid #111;">Ref.</th>' .
    '<th style="width: 44%; border: .1px solid #111;">Designation</th>' .
    '<th style="width: 11%; border: .1px solid #111;">Quant.</th>' .
    '<th style="width: 15%; border: .1px solid #111;">Prix uni.</th>' .
    '<th style="width: 20%; border: .1px solid #111;">Prix total</th>' .
    '</tr>';

$prixtotaux = 0;
foreach ($ventes as $vente) {
    $produits .= '<tr>';
    $produits .= "<td>{$vente->produit}</td>";
    $produits .= "<td>{$vente->prodesignation}</td>";
    $produits .= "<td>{$vente->quantite}</td>";
    $produits .= "<td>{$vente->proprixUnitVente}</td>";
    $produits .= "<td>{$vente->prixtotal}</td>";
    $produits .= '</tr>';

    $prixtotaux += $vente->prixtotal;
}

$produits .= '</table>';


$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);


$pdf->AddPage();


$x = 15;
$y = 20;

//$pdf->Rect($x - 5, $y - 5, 190, $height);
$logo = BASEURL . 'assets/img/' . DS . LOGO;

$pdf->Image($logo, $x - 10, $y - 15, 19, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);

# Remettre l'opacite par defaut
$pdf->SetAlpha(1);
$pdf->setFont("Times", "", 7);
$corps = '<p style="text-align:center;margin:0; padding:0">BOUTIQUE KIKI<br/>
            BP : 50878, Douala / CAMEROUN<br/>
            T&eacute;l : (+237) 698 84 66 669<br/>
            ***************</p>';

$pdf->WriteHTMLCell(0, 0, $x - 2, $y - 15, $corps);
$pdf->setFont("Times", "", 6);

# Reference
$reference = '<h2 style="text-align: center;">R&eacute;&ccedil;u: PRD158</h2>';
$pdf->WriteHTMLCell(100, 0, $x - 10, $y , $reference);


# date de recu
$pdf->SetFillColor(211, 211, 211);

# $this->SetTextColor(255);
$pdf->SetDrawColor(128, 150, 60);

$pdf->setFont("Times", "", 7);

$infopersonnel = '<br><br><p align="left" style="font-weight: bold;">Imprim&eacute; par ' . $user->uselogin . ' / '
    .  $d->getJour(3) . ' ' . $d->getDate() . ' ' . $d->getMois() . ' ' . $d->getYear() . '</p>';

$produits .= '<br>' .
    '<p align="right">' . 'Prix totaux : ' . number_format($prixtotaux, 2, ',' , ' ') . ' <em>fcfa</em>' . '</p>';

$produits .= $infopersonnel;
$pdf->WriteHTMLCell(95, 0, $x - 10, $y + 6, $produits);


//$pdf->WriteHTMLCell(0, 0, $x + 40, $height, "Prix totaux : " . number_format($prixtotaux, 2, ',' , ' ') . ' <em>fcfa</em>' );

$pdf->setFont("Times", "", 7);

//$pdf->WriteHTMLCell(0, 0, $x - 10, $height + 10, $infopersonnel);


$pdf->OutPut('recu.pdf', 'I');