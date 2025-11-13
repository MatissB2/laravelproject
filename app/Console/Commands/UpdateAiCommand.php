<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\SyncState;

class UpdateAiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * php artisan update:ai
     * php artisan update:ai --date=YYYY-MM-DD
     * php artisan update:ai --since=YYYY-MM-DDTHH:MM:SS
     * php artisan update:ai --all
     */
    protected $signature = 'update:ai
                            {--date= : Specify a date (YYYY-MM-DD)}
                            {--since= : Specify a start datetime (YYYY-MM-DDTHH:MM:SS)}
                            {--all : Show all available custom AI update commands}';

    /**
     * The console command description.
     */
    protected $description = 'Run AI update tasks based on date, datetime, or all available updates.';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        SyncState::updateOrCreate(
        ['context' => 'ai_update'],
        ['last_synced_at' => now()]
        );

        $this->info('✅ Kops pēdējās sinhronizācijas: ' . now()->toDateTimeString());

        // Handle --all option
        if ($this->option('all')) {
            return $this->showAllCommands();
        }

        // Handle --since option
        if ($since = $this->option('since')) {
            $sinceDate = Carbon::parse($since, 'Europe/Riga');
            $this->info("Running AI updates since: " . $sinceDate->toDateTimeString());
            return $this->runSince($sinceDate);
        }

        // Handle --date option
        if ($date = $this->option('date')) {
            $targetDate = Carbon::parse($date, 'Europe/Riga')->startOfDay();
            $this->info("Running AI updates for date: " . $targetDate->toDateString());
            return $this->runForDate($targetDate);
        }

        // Default: use yesterday's date in Europe/Riga
        $yesterday = Carbon::now('Europe/Riga')->subDay()->startOfDay();
        $this->info("No options provided — running AI updates for yesterday ({$yesterday->toDateString()}) in Europe/Riga timezone.");
        return $this->runForDate($yesterday);

        
    }

    /**
     * Example: Run updates for a specific date.
     */
    protected function runForDate(Carbon $date)
    {
        // Your custom logic here
        $this->line("➡️ Processing AI updates for {$date->toDateString()}...");
        // Example: Dispatch a job or call a service
        // AiUpdaterService::updateForDate($date);
        $this->info("✅ Done for {$date->toDateString()}.");
    }

    /**
     * Example: Run updates since a datetime.
     */
    protected function runSince(Carbon $since)
    {
        // Your custom logic here
        $this->line("➡️ Processing AI updates since {$since->toDateTimeString()}...");
        // Example: AiUpdaterService::updateSince($since);
        $this->info("✅ Done since {$since->toDateTimeString()}.");
    }

    /**
     * Show all available AI update-related commands.
     */
    protected function showAllCommands()
    {
        $this->info("🧠 Available AI Update Commands:");
        $commands = [
            'update:ai' => 'Run AI update (defaults to yesterday in Europe/Riga)',
            'update:ai --date=YYYY-MM-DD' => 'Run update for a specific date',
            'update:ai --since=YYYY-MM-DDTHH:MM:SS' => 'Run update since a specific datetime',
            'update:ai --all' => 'List all AI update commands',
        ];

        foreach ($commands as $cmd => $desc) {
            $this->line("  - <info>{$cmd}</info>: {$desc}");
        }
    }
}
