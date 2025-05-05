<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class DailyDatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-db-backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a daily backup of the MySQL database and removes old backups.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ensure backup directory exists
        Storage::makeDirectory('backup');

        // Prepare backup file name and path
        $timestamp = 'database-backup-' . now()->format('Y-m-d-H-i-s');
        $filename = "$timestamp.sql.gz";
        $path = Storage::path("backup/$filename");

        // Use config instead of env() for safety
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $database = config('database.connections.mysql.database');

        // Build safe mysqldump command
        $command = [
            'sh',
            '-c',
            sprintf(
                'mysqldump -u%s -p%s -h%s %s | gzip > %s',
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($host),
                escapeshellarg($database),
                escapeshellarg($path)
            )
        ];

        // Run the command
        $process = Process::run($command);

        // Handle result
        if ($process->successful()) {
            $this->info("✅ Backup successful: $filename");
        } else {
            $this->error("❌ Backup failed: " . $process->errorOutput());
            return Command::FAILURE;
        }

        // Optional: Clean up backups older than 7 days
        $this->cleanOldBackups(7);

        return Command::SUCCESS;
    }

    /**
     * Deletes backup files older than the specified number of days.
     */
    protected function cleanOldBackups(int $days)
    {
        $files = Storage::files('backup');
        $deleted = 0;

        foreach ($files as $file) {
            $fullPath = Storage::path($file);
            $modified = File::lastModified($fullPath);
            if (Carbon::createFromTimestamp($modified)->lt(now()->subDays($days))) {
                Storage::delete($file);
                $deleted++;
            }
        }

        if ($deleted > 0) {
            $this->info("🧹 Deleted $deleted old backup file(s) older than $days days.");
        } else {
            $this->info("🧼 No old backups found for cleanup.");
        }
    }
}
