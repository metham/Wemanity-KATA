<?php

namespace App\DTO;


class Field
{
    public int $m;

    public int $n;

    public array $squares;

    public function __construct(int $n, int $m)
    {
        $this->m = $m;
        $this->n = $n;
    }
}
