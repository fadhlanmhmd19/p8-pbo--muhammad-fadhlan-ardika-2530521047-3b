<?php
// Tugas 1: PHP menolak dua deklarasi method bernama sama (overloading tradisional).
class Calculator {
    public function add(int $a, int $b): int { return $a + $b; }
    public function add(int $a, int $b, int $c): int { return $a + $b + $c; }
}
echo "tidak tercapai";
