<?php
declare(strict_types=1);
// Contoh 2 - Variadic parameter (BUKAN overloading tradisional)
class Calculator {
    public function add(int ...$numbers): int {
        return array_sum($numbers);
    }
}
$calc = new Calculator();
echo $calc->add(10, 20) . PHP_EOL;      // 30
echo $calc->add(10, 20, 30) . PHP_EOL;  // 60
$jumlahDeklarasi = count(array_filter((new ReflectionClass(Calculator::class))->getMethods(), fn($m) => $m->getName() === 'add'));
echo "Jumlah deklarasi add(): $jumlahDeklarasi" . PHP_EOL;
