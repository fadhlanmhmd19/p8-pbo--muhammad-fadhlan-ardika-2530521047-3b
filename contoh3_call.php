<?php
declare(strict_types=1);
// Contoh 3 - Overloading PHP melalui __call()
class StudentPresenter {
    public function __call(string $name, array $args): string {
        if ($name !== 'showData') {
            throw new BadMethodCallException('Unknown method: ' . $name);
        }
        if (count($args) === 1 && is_string($args[0])) {
            return 'Name: ' . $args[0];
        }
        if (count($args) === 2 && is_string($args[0]) && is_string($args[1])) {
            return 'ID: ' . $args[0] . ', Name: ' . $args[1];
        }
        throw new InvalidArgumentException('Expected 1 or 2 strings');
    }
}
$view = new StudentPresenter();
echo $view->showData('Ahmad') . PHP_EOL;
echo $view->showData('2026001', 'Ahmad') . PHP_EOL;

// Jawaban pertanyaan modul: showData() tanpa argumen / dengan bilangan
foreach ([[], [123], ['2026001', 2026]] as $args) {
    try { $view->showData(...$args); }
    catch (Throwable $e) { echo get_class($e) . ': ' . $e->getMessage() . PHP_EOL; }
}
