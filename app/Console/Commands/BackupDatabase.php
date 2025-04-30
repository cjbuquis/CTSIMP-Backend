<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\DbDumper\Databases\MySql;

class BackupDatabase extends Command
{
    protected $signature   = 'db:backup {path?}';
    protected $description = 'Dump MySQL to a .sql file';

    public function handle()
    {
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        // determine path
        $rawPath = $this->argument('path') ?? database_path('dumps');
        if (!file_exists($rawPath) && !str_ends_with($rawPath, '.sql')) {
            mkdir($rawPath, 0755, true);
        }
        $file = str_ends_with($rawPath, '.sql')
            ? $rawPath
            : rtrim($rawPath, '/')."/{$database}_".now()->format('Ymd_His').'.sql';

        // run the dump
        MySql::create()
            ->setDbName($database)
            ->setUserName($username)
            ->setPassword($password)
            ->dumpToFile($file);

        $this->info("Database exported to {$file}");
    }
}
