<?php

namespace App\DTO;

class Square
{
    public int $x;

    public int $y;


    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }
}
