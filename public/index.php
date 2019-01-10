<?php declare(strict_types=1);


echo $_GET['page'];



echo "<br>";


function add(int $x, int $y) : int {
    return $x + $y;
}

echo add(2,3);