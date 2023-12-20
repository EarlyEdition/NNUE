<?php

$xArr = array(1, 1, 2, 3, 5, 8, 13, 21, 34);
$yArr =  array(5, 5, 7, 9,13,19, 29, 45, 73);

$count = count($xArr);
$learnc = 0.000001;
$weight = 0;
$bias = 1;
$cost;

// Stochastic gradient descent
do {
    $wx;
    $err = 0;
    $w_deriv = 0;
    $b_deriv = 0;
    for ($i = 0; $i < $count; $i++) {
        $wx = $yArr[$i] - ($weight * $xArr[$i] + $bias);
        $err +=  $wx ** 2;
        $w_deriv += -2 * $wx * $xArr[$i];
        $b_deriv += -2 * $wx;
    }

    $weight -= ($w_deriv / $count) * $learnc;
    $bias -= ($b_deriv / $count) * $learnc;
    $cost = $err / $count;
} while ($cost > 1);

echo "weight : $weight , ";
echo "bias : $bias , ";
echo "cost : $cost ";
