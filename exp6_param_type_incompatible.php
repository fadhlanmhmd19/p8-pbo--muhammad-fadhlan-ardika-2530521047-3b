<?php
declare(strict_types=1);
// Tugas 4: signature parameter tidak kompatibel (menambah parameter wajib).
class Report { public function render(string $title): string { return $title; } }
class PdfReport extends Report {
    public function render(string $title, string $format): string { return "$title.$format"; }  // <- ERROR
}
echo "tidak tercapai";
