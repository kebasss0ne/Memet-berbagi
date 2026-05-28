<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Symfony\Component\Process\Process;

class DatabaseBackupController extends Controller
{
    public function download()
    {
        $filename = 'backup-' . now()->format('Y-m-d-H-i-s') . '.sql';

        $backupDir = storage_path('app/private/backups');
        $backupPath = $backupDir . '/' . $filename;

        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0750, true);
        }

        $database = config('database.connections.mysql.database');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        $configFile = storage_path('app/private/mysqldump-' . uniqid() . '.cnf');

        file_put_contents($configFile, implode(PHP_EOL, [
            '[client]',
            'host=' . $host,
            'port=' . $port,
            'user=' . $username,
            'password=' . $password,
        ]));

        chmod($configFile, 0600);

        $process = new Process([
            'mysqldump',
            '--defaults-extra-file=' . $configFile,
            '--single-transaction',
            '--quick',
            '--lock-tables=false',
            $database,
            '--result-file=' . $backupPath,
        ]);

        $process->setTimeout(120);
        $process->run();

        @unlink($configFile);

        if (!$process->isSuccessful() || !file_exists($backupPath)) {
            abort(500, 'Backup database gagal.');
        }

        return response()
            ->download($backupPath, $filename)
            ->deleteFileAfterSend(true);
    }
}
