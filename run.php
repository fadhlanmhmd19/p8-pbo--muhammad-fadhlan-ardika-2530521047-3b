<?php
declare(strict_types=1);
require __DIR__.'/../src/bootstrap.php';
$pass=0;
function check(bool $ok, string $label): void {
        global $pass; if(!$ok) throw new RuntimeException('FAIL: '.$label); $pass++; echo "PASS: $label\n";
        }
function fails(callable $fn, string $exception, string $label): void {
        try{$fn();}catch(Throwable $e){check($e instanceof $exception,$label.' -> '.get_class($e));return;}throw new RuntimeException('FAIL: '.$label);
        }
/** Menjalankan file eksperimen di proses PHP terpisah; mengembalikan [exit code, output]. */
function runExperiment(string $file): array {
        $cmd = escapeshellarg(PHP_BINARY).' -d display_errors=1 -d html_errors=0 -d log_errors=0 '.escapeshellarg(__DIR__.'/../experiments/'.$file).' 2>&1';
        exec($cmd, $out, $code); return [$code, implode("\n", $out)];
        }

$r=new StudentRepository();
$a=new RegularStudent('M01','Alya','Informatika',5000000);
$b=new ScholarshipStudent('M02','Bima','SI',5000000,40);
$p=new StudentPresenter();
$calc=new Calculator();

echo "== Skenario wajib T01-T10 ==\n";
// T01 variadic
check($calc->add(10,20)===30,'T01a add(10,20) = 30');
check($calc->add(10,20,30)===60,'T01b add(10,20,30) = 60');
check(count(array_filter((new ReflectionClass(Calculator::class))->getMethods(), fn($m)=>$m->getName()==='add'))===1,'T01c hanya ada 1 deklarasi add() (bukan overloading tradisional)');
// T02-T03 __call positif
check($p->showData('Ahmad')==='Nama: Ahmad','T02 showData("Ahmad") = Nama: Ahmad');
check($p->showData('2026001','Ahmad')==='NIM: 2026001, Nama: Ahmad','T03 showData("2026001","Ahmad") = NIM: 2026001, Nama: Ahmad');
check($p->showData('2026001','Ahmad','Informatika')==='NIM: 2026001, Nama: Ahmad, Prodi: Informatika','T03b (Tugas 2) showData(id,name,major) tiga string');
// T04-T05 __call negatif
fails(fn()=>$p->showData(),InvalidArgumentException::class,'T04 showData() tanpa argumen');
fails(fn()=>$p->unknown(),BadMethodCallException::class,'T05 method tidak dikenal pada presenter');
fails(fn()=>$p->showData(123),InvalidArgumentException::class,'T04b showData(123) bilangan');
fails(fn()=>$p->showData('M01','Alya',2026),InvalidArgumentException::class,'T04c showData(id,name,int) bukan string');
fails(fn()=>$p->showData('a','b','c','d'),InvalidArgumentException::class,'T04d showData 4 argumen');
// T06-T07 overriding calculateFee
check($a->calculateFee()===5000000.0,'T06 RegularStudent fee 5 juta = 5000000');
check($b->calculateFee()===3000000.0,'T07 ScholarshipStudent diskon 40% dari 5 juta = 3000000');
// T08 diskon invalid
fails(fn()=>new ScholarshipStudent('M04','Dina','TI',1000,-1),InvalidArgumentException::class,'T08a diskon -1');
fails(fn()=>new ScholarshipStudent('M04','Dina','TI',1000,101),InvalidArgumentException::class,'T08b diskon 101');
// T09 summary()
check($a->summary()==='M01 - Alya','T09a summary() RegularStudent = "M01 - Alya"');
check($b->summary()==='M02 - Bima [Beasiswa]','T09b summary() ScholarshipStudent = "M02 - Bima [Beasiswa]"');
// T10 final
check((new ReflectionMethod(Student::class,'getId'))->isFinal(),'T10a getId() bertanda final');
[$code,$msg]=runExperiment('exp2_override_final.php');
check($code!==0 && str_contains($msg,'Cannot override final method Student::getId()'),'T10b override final di file eksperimen -> Fatal error');

echo "\n== Tugas 1: variadic bukan overloading ==\n";
fails(fn()=>$calc->add(1,'x'),TypeError::class,'add(1,"x") ditolak strict_types (TypeError)');
check($calc->add()===0,'add() tanpa argumen = 0');
[$code,$msg]=runExperiment('exp1_redeclare_add.php');
check($code!==0 && str_contains($msg,'Cannot redeclare Calculator::add()'),'Dua deklarasi add() -> Fatal error Cannot redeclare');

echo "\n== Tugas 3: InternationalStudent (polimorfisme via Student) ==\n";
$c=new InternationalStudent('2530521047','Muhammad Fadhlan Ardika','Teknik Informatika',5000000,50);
check($c instanceof Student,'InternationalStudent adalah Student');
check($c->calculateFee()===7500000.0,'Overriding biaya internasional (50%) = 7500000');
check($c->summary()==='2530521047 - Muhammad Fadhlan Ardika [Internasional]','Overriding summary() + parent::');
fails(fn()=>new InternationalStudent('M06','Eka','TI',1000,-5),InvalidArgumentException::class,'Biaya tambahan negatif ditolak');
$ref=new ReflectionMethod(StudentRepository::class,'create');
check((string)$ref->getParameters()[0]->getType()==='Student','Repository bertipe parent Student, tidak mengenal nama child');
check(!str_contains((string)file_get_contents(__DIR__.'/../src/StudentRepository.php'),'International'),'StudentRepository tidak menyebut InternationalStudent');
$total=0.0;
foreach([$a,$b,$c] as $s){ $total+=$s->calculateFee(); }
check($total===15500000.0,'Dispatch dinamis: total 5.000.000 + 3.000.000 + 7.500.000 = 15500000 tanpa if/else tipe');

echo "\n== Tugas 4: final, private, visibility, signature ==\n";
foreach([
    ['exp3_signature_incompatible.php','Declaration of BadFeeStudent::calculateFee(): string must be compatible','Return type tidak kompatibel -> Fatal error'],
    ['exp6_param_type_incompatible.php','must be compatible with Report::render(string $title): string','Parameter wajib tambahan -> Fatal error'],
    ['exp4_visibility_stricter.php','Access level to ProtectedFeeStudent::calculateFee() must be public','Visibility lebih ketat -> Fatal error'],
] as [$file,$needle,$label]) {
    [$code,$msg]=runExperiment($file);
    check($code!==0 && str_contains($msg,$needle),$label);
}
[$code,$msg]=runExperiment('exp5_private_not_overridden.php');
check($code===0 && trim($msg)==='secret milik PARENT','Method private parent tidak ter-override (tetap memanggil milik parent)');

echo "\n== CRUD polymorphic (pengujian bawaan) ==\n";
check($a instanceof Student,'Inheritance regular');
check($b instanceof Student,'Inheritance scholarship');
check(str_contains($b->summary(),'[Beasiswa]'),'Overriding + parent::');
$r->create($a);$r->create($b);
check(count($r->all())===2,'Create');
check($r->find('M02')===$b,'Read');
fails(fn()=>$r->create($a),InvalidArgumentException::class,'NIM duplikat ditolak');
$r->update('M01',new RegularStudent('M03','Cici','Informatika',4000000));
check($r->find('M01')===null && $r->find('M03')!==null,'Update NIM');
$r->create($c);
check(count($r->all())===3 && $r->find('2530521047')?->calculateFee()===7500000.0,'Create InternationalStudent lewat repository yang sama');
$r->delete('M03');
check(count($r->all())===2,'Delete');
fails(fn()=>$r->delete('TIDAK-ADA'),InvalidArgumentException::class,'Delete NIM tidak ada ditolak');
check($p->showData('Alya')==='Nama: Alya','__call satu argumen');
check(str_contains($p->showData('M01','Alya'),'NIM:'),'__call dua argumen');
check(str_contains($p->showData('M01','Alya','TI'),'Prodi:'),'__call tiga argumen');
fails(fn()=>new ScholarshipStudent('M04','Dina','TI',1000,101),InvalidArgumentException::class,'Diskon invalid');
echo "TOTAL: $pass tests passed\n";
