<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

$rows = DB::table('form_response_values')->whereNotNull('value')->where('value','like','%uploads/%')->get();
foreach ($rows as $r) {
    $path = $r->value;
    if (Storage::disk('local')->exists($path) && !Storage::disk('public')->exists($path)) {
        Storage::disk('public')->put($path, Storage::disk('local')->get($path));
        echo "moved {$path}\n";
    }
}
