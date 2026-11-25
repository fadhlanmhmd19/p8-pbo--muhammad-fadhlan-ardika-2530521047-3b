<?php
declare(strict_types=1);
/**
 * Tugas 3: child class baru. calculateFee() di-override dengan perilaku berbeda
 * (biaya dasar + biaya tambahan internasional). StudentRepository TIDAK diubah.
 */
final class InternationalStudent extends Student {
    public function __construct(string $id, string $name, string $major, float $baseFee, private float $surcharge = 50.0) {
        if (!is_finite($surcharge) || $surcharge < 0 || $surcharge > 300) throw new InvalidArgumentException('Biaya tambahan harus 0 sampai 300%.');
        parent::__construct($id, $name, $major, $baseFee);
    }
    public function getType(): string { return 'international'; }
    public function getSurcharge(): float { return $this->surcharge; }
    public function calculateFee(): float { return $this->baseFee * (1 + $this->surcharge / 100); }
    public function summary(): string { return parent::summary() . ' [Internasional]'; }
}
