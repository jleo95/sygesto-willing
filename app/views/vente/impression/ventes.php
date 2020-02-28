<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 20/01/2019
 * Time: 21:11
 */

$table = '<table border="1" cellpadding="10" cellspacing="0">';



$table .= '<tr>' .
        '<th style="font-weight: bold; font-size: 13px">Ref.</th>' .
        '<th style="font-weight: bold; font-size: 13px">Client</th>' .
        '<th style="font-weight: bold; font-size: 13px">Date</th>' .
        '<th style="font-weight: bold; font-size: 13px">Prix Totaux</th>' .
    '</tr>';

$depenses = 0;
$totaux_ventes = 0;
$benefices = 0;

if (count($ventes) > 0) {
    foreach ($ventes as $vente) {
        $date = new \App\Core\DateFR($vente->cmddate);
        if (date('Y', time()) === $date->getYear()) {
            $date_vente = $date->getDate() . ' ' . $date->getMois(3) . ' - ' . $date->getTime();
        }else {
            $date_vente = $date->getDate() . ' ' . $date->getMois(3) . ' ' . $date->getYear() . ' - ' . $date->getTime();
        }
        $nameClient = 'Inconnu';
        $client = $obj_vente->Client->get_by('cliid', $vente->cmdclient);
        if ($client) {
            $nameClient = ucfirst($client->clinom) . ' ' . ucfirst($client->cliprenom);
        }
        $table .= '<tr>' .
            '<td>' . $vente->cmdid . '</td>' .
            '<td>' . $nameClient . '</td>' .
            '<td>' . $date_vente . '</td>' .
            '<td>' . number_format($obj_vente->prixTauxVente($vente->cmdid)['prixTotaux'], 2, ',', ' ') . ' fcfa' . '</td>' .
            '</tr>';
        $totaux_ventes += $obj_vente->prixTauxVente($vente->cmdid)['prixTotaux'];
        $depenses += $obj_vente->prixTauxVente($vente->cmdid)['depenses'];
    }
    $table .= '<tr>' .
//        '<td colspan="2" align="center" style="border-color: #fff;">Aucun resultat</td>' .
        '<td colspan="4" align="center"><strong>Totales ventes : </strong>' . number_format($totaux_ventes, 2, ',', ' ') . ' fcfa</td>' .
        '</tr>';

}else {
    $table .= '<tr><td colspan="4" align="center">Aucun resultat</td></tr>';
}

$benefices = $totaux_ventes - $depenses;

$table .= '</table><br><br><br>';

$table .= '<div style="font-size: 15px;"> <strong>Dépenses : </strong> .......................... ' . number_format($depense, 2, ',', ' ') . ' fcfa </div>';
$table .= '<div style="font-size: 15px;"> <strong>Benefices : </strong> ......................... ' . number_format($benefice, 2, ',', ' ') . ' fcfa </div>';
//$table .= '<div style="font-size: 15px;"> <strong>Totaux ventes : </strong> ................ ' . number_format($totaux_ventes, 2, ',', ' ') . ' fcfa </div>';


$pdf->AddPage();


$x = 15;
$y = 20;

# Remettre l'opacite par defaut
$pdf->SetAlpha(1);
$pdf->setFont("Times", "", 7);
$corps = '<p style="text-align:center;margin:0; padding:0">BOUTIQUE KIKI<br/>
            BP : 50878, Douala / CAMEROUN<br/>
            T&eacute;l : (+237) 698 84 66 669<br/>
            ***************</p>';

//$pdf->WriteHTMLCell(0, 0, $x - 2, $y - 15, $corps);

$title = "<h1 style='text-align: center; text-decoration: underline'>$title_date</h1>";

$pdf->setFontSize(10);
$pdf->WriteHTMLCell(0, 0, 23, 45, $title);

$pdf->setFontSize(11.5);
$pdf->WriteHTMLCell(192, 5, 10, 58, $table);


$pdf->OutPut('ventes.pdf', 'I');