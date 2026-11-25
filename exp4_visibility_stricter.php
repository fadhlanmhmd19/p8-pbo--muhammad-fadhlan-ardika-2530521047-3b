<?php
// Tugas 4: visibility child lebih ketat dari parent (public -> protected).
require __DIR__ . '/../src/Student.php';
class ProtectedFeeStudent extends Student {
    public function getType(): string { return 'prot'; }
    protected function calculateFee(): float { return 1.0; }   // <- ERROR
}
echo "tidak tercapai";
