<div class="views-commande">

    <?php
//    var_dump($commande);

    $date = new \App\Core\DateFR($commande->offdate);
    ?>

    <div class="content-commande">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="title-commande">
                    <span>Demande d'achat: </span>
                    <span><?php echo '#' . $commande->offid?> </span>
                </div>
                <div class="content">
                    <span>Date : </span>
                    <span><?php echo $date->getDate() . ' ' . $date->getMois() . ' ' . $date->getYear()?> </span>
                </div>
                <div class="content">
                    <span>Offshore : </span>
                    <span><?php echo $commande->offshore ?> </span>
                </div>
                <div class="content">
                    <span>Mode de paiement : </span>
                    <span><?php echo $commande->pailibelle ?> </span>
                </div>
            </div>
        </div>

        <div class="commande-detail">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <table class="table table-responsive table-bordered">
                        <thead>
                        <th>Produit</th>
                        <th>Designation</th>
                        <th>Quantité</th>
                        <th>Unité</th>
                        </thead>
                        <tbody>
                        <?php
                        $class = '';
                        $classIt = 0;
                        $colors = $argColors;
                        shuffle($argColors);
                        $lenthColors = count($argColors) - 1;
                        foreach ($groupByfamille as $k => $item) {
                            if (empty($colors)){
                                $colors = $argColors;
                                $lenthColors = count($colors) - 1;
                            }
                            $index = random_int(0, $lenthColors);
                            $lenthColors --;
                            //$indexColors = $colors[$index];
                            unset($colors[$index]);
                            $j = 1;
                            ?>
                            <tr >
                                <td class="<?php echo $class ?>" rowspan="<?php echo count($item); ?>" style="background: <?php //echo $indexColors ?>;"><?php echo $k ?></td>
                                <?php foreach ($item as $i) {
                                    ?>
                                    <td><?php echo $i->produit; ?></td>
                                    <td><?php echo $i->quantite; ?></td>
                                    <td><?php echo $i->unite; ?></td>
                                    <?php
                                    if (count($item) > 1)
                                        if ($j < count($item))
                                            echo '</tr><tr>';
                                        else
                                            echo '</tr>';

                                    $j ++;
                                }
                                if ($classIt % 2 == 0)
                                    $class = 'change-color';
                                else
                                    $class = '';

                                $classIt ++;
                                ?>
                            </tr>

                        <?php

                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div style="text-align: center;">
            <a href="commande/imprimer/<?php echo $commande->offid; ?>" target="_blank" class="btn btn-primary">Imprimer</a>
        </div>
    </div>

</div>

<style>
    .content{}
    .content span {
        font-size: 15px ;
    }
    .content span:nth-child(1) {
        font-size: 16px ;
        font-weight: 700;
        text-decoration: underline;
    }
    table tr td.change-color{
        background: #efefef;
    }
    .commande-detail {
        margin-top: 10px;
    }
</style>