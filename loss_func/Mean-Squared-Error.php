<?php

// Loss Function
function mse($y, $y_pred)
{
    $c = array_map(function ($a, $b) {
        return ($a - $b) ** 2;
    }, $y, $y_pred);

    return array_sum($c) / count($c);
}

$y1 = array(2,4,6,8);

$y2 = array(1,3,5,7);

echo mse($y1, $y2);
