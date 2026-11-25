<?php
// Tugas 4: signature tidak kompatibel (return type float -> string).
require __DIR__ . '/../src/Student.php';
class BadFeeStudent extends Student {
    public function getType(): string { return 'bad'; }
    public function calculateFee(): string { return 'gratis'; }   // <- ERROR
}
echo "tidak tercapai";
