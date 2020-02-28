<?php


namespace App\Core;

use App\Core\DateFR;
use App\Core\Model;

require APP . DS . 'lib' . DS . 'tcpdf' . DS . 'tcpdf.php';

class PDF extends \TCPDF {

    private $model;

    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4') {
        parent::__construct($orientation, $unit, $format, true, 'UTF-8', false);

        $this->fontpath = BASEURL . "lib/tcpdf/fonts";

        $this->logo = BASEURL . "assets/img/" . LOGO;

        $this->setCreator(PDF_CREATOR);
        $this->setHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->setDefaultMonospacedFont('helvetica');
        $this->setFooterMargin(PDF_MARGIN_FOOTER);
        $this->setMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
        $this->setAutoPageBreak(TRUE, 2);

        $this->model = new Model();
    }

    public function Header() {

        $header_gauche = <<<EOD
                <p style = "text-align:center;line-height: 10px">
                    Minist&egrave;re des Enseignements Secondaires<br/>
                                *************<br/>
                    D&eacute;l&eacute;gation R&eacute;gionale du Centre<br/>
                                *************<br/>
                    D&eacute;l&eacute;gation D&eacute;partementale de la MEFOU<br/>
                                AFAMBA<br/>
                                *************<br/>
                    <b>INSTITUT POLYVALENT WAGU&Eacute;</b><br/>
                    <i>&nbsp;&nbsp;Autorisation d'ouverture N° 79/12/MINESEC</i><br/>
                    BP 5062 YAOUNDE<br/>
                    T&eacute;l&eacute;phone: +237 697 86 84 99<br/>
                    Email: institutwague@yahoo.fr<br/>
                    www.institutwague.com
                </p>
                        
EOD;
        global $url; //$url est une variable globale defini dans Router.php
        $urlArray = explode("/", $url);

        // Set font
        //$this->WriteHTML
        $header_droit = <<<EOD
                <p style = "text-align:center">R&eacute;publique du Cameroun<br/>
                    <i>Paix-Travail-Patrie<br/>***********</p>
EOD;
        $this->Image($this->logo, 80, 5, 35, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->writeHTMLCell(50, 5, 156, 5, $header_droit);
        $this->SetFont('helvetica', 'B', 20);
        // set document information
        $this->SetCreator("BAACK Group");
        $this->SetAuthor('BAACK Group');
        # set auto page breaks
        //$this->CEll
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        # $this->writeHTMLCell(50, 50, 20, 20, $this->GetY(), 1);
    }

    public function Footer() {

        $user = $this->model->execute('SELECT * FROM USERS WHERE useid = ?', [$_SESSION['stkiduser']], true);
        // Position at 15 mm from bottom
        $this->SetY(-20);
        $line_width = (0.85 / $this->k);
        $this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $this->footer_line_color));

        $this->SetFont('helvetica', 'B', 7);
        $d = new DateFR();
        $signature = '<p style="text-align:right">Imprim&eacute; par : ' . $user->uselogin . " / " . $d->getJour(3) . " " . $d->getDate()
            . " " . $d->getMois(3) . " " . $d->getYear() . '</p>';
        $this->writeHTML($signature);

        $this->SetFont('helvetica', 'B', 8);
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}
