<?php
// Contoh 5 - Aturan final dan signature (versi VALID)
class BaseReport {
    final public function getId(): string { return 'R-01'; }
    public function title(): string { return 'Report'; }
}
class CustomReport extends BaseReport {
    public function title(): string { return 'Custom Report'; }
    // getId() tidak dideklarasikan ulang karena final di parent.
}
$r = new CustomReport();
echo $r->getId() . ' | ' . $r->title() . PHP_EOL;
