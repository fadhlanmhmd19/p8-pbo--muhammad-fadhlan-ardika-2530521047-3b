<?php
declare(strict_types=1);
/**
 * Tugas 1: variadic BUKAN method overloading tradisional.
 * Hanya ada SATU deklarasi add(); jumlah argumen bervariasi karena operator "...".
 */
final class Calculator {
    public function add(int ...$numbers): int {
        return array_sum($numbers);
    }
}
