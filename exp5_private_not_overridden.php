<?php
declare(strict_types=1);
// Tugas 4: method private parent bukan method warisan -> tidak ter-override.
class ParentClass {
    private function secret(): string { return 'secret milik PARENT'; }
    public function reveal(): string { return $this->secret(); }
}
class ChildClass extends ParentClass {
    private function secret(): string { return 'secret milik CHILD'; }   // method baru, bukan override
}
echo (new ChildClass())->reveal() . PHP_EOL;   // tetap memanggil secret() milik PARENT
