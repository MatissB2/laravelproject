<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\Doctors;
use App\Models\Patients;
use App\Models\Invoices;

class ExportModelsAsJson extends Command
{
    protected $signature = 'export:models';
    protected $description = 'Export Doctors, Patients, and Invoices models as JSON files';

    protected array $models = [
        'Doctors' => Doctors::class,
        'Patients' => Patients::class,
        'Invoices' => Invoices::class,
    ];

    public function handle()
    {
        $directory = storage_path('exports/json');
        File::ensureDirectoryExists($directory);

        foreach ($this->models as $name => $class) {
            $this->info("📦 Exporting {$name}...");
            $data = $class::all();
            $json = $data->toJson(JSON_PRETTY_PRINT);
            File::put("{$directory}/{$name}.json", $json);
            $this->info("✅ Exported {$data->count()} {$name} records to {$directory}/{$name}.json");
        }

        $this->info('🎉 All models exported successfully!');
    }
}