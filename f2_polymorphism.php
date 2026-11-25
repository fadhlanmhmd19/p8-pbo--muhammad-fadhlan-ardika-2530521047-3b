<?php
declare(strict_types=1);
// F.2 - Demonstrasi polymorphism memakai class proyek (src/)
require __DIR__ . '/../src/bootstrap.php';
$students = [
    new RegularStudent('M01', 'Alya', 'Informatika', 5000000),
    new ScholarshipStudent('M02', 'Bima', 'Sistem Informasi', 5000000, 40),
    new InternationalStudent('2530521047', 'Muhammad Fadhlan Ardika', 'Teknik Informatika', 5000000, 50),
];
foreach ($students as $student) {
    printf('%s | %s | Rp%.0f%s', $student->summary(), $student->getType(), $student->calculateFee(), PHP_EOL);
}
