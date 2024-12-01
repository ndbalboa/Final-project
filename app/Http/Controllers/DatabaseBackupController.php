<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use ZipArchive;

class DatabaseBackupController extends Controller
{
    public function __construct()
    {
        // Ensure authentication and authorization
        $this->middleware('auth:api');
        $this->middleware('can:admin');
    }

    public function backup()
    {
        try {
            // Define backup paths
            $timestamp = now()->format('Ymd_His');
            $backupDir = storage_path('backups');
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }
            $databaseBackupFile = $backupDir . "/database_backup_{$timestamp}.sql";
            $filesBackupFile = $backupDir . "/files_backup_{$timestamp}.zip";

            // Run database backup using mysqldump
            $process = new Process([
                'mysqldump',
                '-u' . env('root'),
                '-p' . env(''),
                env('capstone_project_web')
            ]);
            $process->setTimeout(600);
            $process->mustRun();
            file_put_contents($databaseBackupFile, $process->getOutput());

            // Create ZIP archive of the storage directory
            $storagePath = storage_path('app/public');
            $zip = new ZipArchive();
            if ($zip->open($filesBackupFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($storagePath),
                    \RecursiveIteratorIterator::SELF_FIRST
                );
                foreach ($files as $file) {
                    if (!$file->isDir()) {
                        $zip->addFile($file->getRealPath(), str_replace($storagePath . '/', '', $file->getRealPath()));
                    }
                }
                $zip->close();
            } else {
                throw new \Exception('Failed to create ZIP archive.');
            }

            // Return the database backup file as a response
            return response()->download($databaseBackupFile, basename($databaseBackupFile))->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Backup failed: ' . $e->getMessage()], 500);
        }
    }

    public function restore(Request $request)
    {
        try {
            // Validate uploaded file
            $request->validate([
                'backup_file' => 'required|file',
            ]);

            $file = $request->file('backup_file');
            $filePath = $file->getRealPath();

            // Restore database using mysql command
            $process = new Process([
                'mysql',
                '-u' . env('root'),
                '-p' . env(''),
                env('capstone_project_web')
            ]);
            $process->setInput(file_get_contents($filePath));
            $process->mustRun();

            return response()->json(['message' => 'Database restore successful.']);
        } catch (ProcessFailedException $e) {
            return response()->json(['error' => 'Restore process failed: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Restore failed: ' . $e->getMessage()], 500);
        }
    }
}
