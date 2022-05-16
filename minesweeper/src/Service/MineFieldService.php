<?php

namespace App\Service;

use App\DTO\Field;
use App\DTO\Square;

class MineFieldService
{
    /**
     * @param array $minefields
     * @return array
     */
    public function solveMinefields(array $minefields): array
    {
        $arrayFields = $this->transformFieldStringToArray($minefields);
        foreach ($arrayFields as $arrayField) {
            $this->getMineFieldResult($arrayField);
        }
        return $this->transformFieldsToArrayOfStrings($arrayFields);
    }

    /**
     * @param array $minefields
     * @return array
     *
     * Map fields to
     */
    private function transformFieldStringToArray(array $minefields): array
    {
        $result = [];
        foreach ($minefields as $i => $iValue) {
            $tmp = explode(" ", $iValue);
            if (count($tmp) > 1) {
                [$n, $m] = $tmp;
                if ($n === $m && $m == 0) {
                    break;
                }
                $field = new Field($n, $m);
                $squares = [];
                for ($j = $i + 1; $j < $n + $i + 1; $j++) {
                    $squares[] = str_split($minefields[$j]);
                }
                $field->squares = $squares;
                $result[] = $field;
            }
        }
        return $result;
    }

    /**
     * @param Field[] $fields
     * @return array
     *
     * Transform each line of a field into a string
     * Format return array to desired design
     */
    public function transformFieldsToArrayOfStrings(array $fields): array
    {
        $result = [];
        foreach ($fields as $key => $field) {
            $result[] = "Field #" . ($key + 1) . ":";
            foreach ($field->squares as $line) {
                $result[] = implode($line);
            }
        }
        return $result;
    }

    /**
     * @param Field $field
     * @return void
     *
     * Compute the number of bombs for each square
     */
    private function getMineFieldResult(Field $field): void
    {
        $resultField = $this->createEmptyField($field->n, $field->m);
        $bombs = [];

        foreach ($field->squares as $keyL => $line) {
            foreach ($line as $keyC => $square) {
                if ($square === '*') {
                    $bombs[] = new Square($keyC, $keyL);
                    if ($keyC > 0) {
                        $resultField[$keyL][$keyC - 1] += 1;
                        if ($keyL > 0) {
                            $resultField[$keyL - 1][$keyC - 1] += 1;
                        }
                        if ($keyL < $field->n - 1) {
                            $resultField[$keyL + 1][$keyC - 1] += 1;
                        }
                    }
                    if ($keyC < $field->m - 1) {
                        $resultField[$keyL][$keyC + 1] += 1;
                        if ($keyL > 0) {
                            $resultField[$keyL - 1][$keyC + 1] += 1;
                        }
                        if ($keyL < $field->n - 1) {
                            $resultField[$keyL + 1][$keyC + 1] += 1;
                        }
                    }
                    if ($keyL > 0) {
                        $resultField[$keyL - 1][$keyC] += 1;
                    }
                    if ($keyL < $field->n - 1) {
                        $resultField[$keyL + 1][$keyC] += 1;
                    }
                }
            }
        }

        foreach ($bombs as $bomb) {
            $resultField[$bomb->y][$bomb->x] = '*';
        }

        $field->squares = $resultField;
    }

    /**
     * @param int $n number of rows
     * @param int $m number of columns
     * @return array
     *
     * Create an empty field of the desired dimensions
     */
    private function createEmptyField(int $n, int $m): array
    {
        $emptyField = [];
        for ($i = 0; $i < $n; $i++) {
            $emptyField[] = array_fill(0, $m, 0);
        }
        return $emptyField;
    }
}
