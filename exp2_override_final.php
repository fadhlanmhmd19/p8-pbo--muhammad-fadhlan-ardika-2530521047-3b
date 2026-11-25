<?php
// Tugas 4 / T10: override method final.
require __DIR__ . '/../src/Student.php';
class HackedStudent extends Student {
    public function getType(): string { return 'x'; }
    public function calculateFee(): float { return 0; }
    public function getId(): string { return 'HACK'; }   // <- ERROR: parent final
}
echo "tidak tercapai";
