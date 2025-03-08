<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\BackupSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BackupController extends Controller
{
    public function index()
    {
        $schedules = BackupSchedule::all();
        $backupFiles = $this->getBackupFiles();
        
        return view('settings.backup.index', compact('schedules', 'backupFiles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'frequency' => 'required|in:daily,weekly,monthly',
            'time' => 'required',
            'day' => 'required_if:frequency,weekly,monthly|nullable|integer|min:1|max:31',
        ]);

        BackupSchedule::create($validated);

        return redirect()
            ->route('settings.backup.index')
            ->with('success', 'Agendamento criado com sucesso!');
    }

    public function createBackup()
    {
        try {
            $backupPath = $this->generateBackup();
            return redirect()
                ->route('settings.backup.index')
                ->with('success', 'Backup realizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->route('settings.backup.index')
                ->with('error', 'Erro ao realizar backup: ' . $e->getMessage());
        }
    }

    private function generateBackup()
    {
        $filename = 'backup_' . date('Y-m-d_His') . '.sql';
        $path = storage_path('app/backups/' . $filename);

        // Garante que o diretório existe
        if (!file_exists(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        // Comando para Windows
        $command = sprintf(
            '"%s" -u root marmosys > "%s"',
            'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe',
            $path
        );

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception('Erro ao gerar backup');
        }

        return $filename;
    }

    private function getBackupFiles()
    {
        $path = storage_path('app/backups');
        if (!file_exists($path)) {
            return [];
        }

        $files = glob($path . '/*.sql');
        $backups = [];

        foreach ($files as $file) {
            $backups[] = [
                'name' => basename($file),
                'size' => $this->formatSize(filesize($file)),
                'date' => Carbon::createFromTimestamp(filemtime($file))->format('d/m/Y H:i:s')
            ];
        }

        return array_reverse($backups);
    }

    private function formatSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 2, ',', '.') . ' ' . $units[$power];
    }

    public function download($filename)
    {
        $path = storage_path('app/backups/' . $filename);
        
        if (file_exists($path)) {
            return response()->download($path);
        }
        
        return redirect()
            ->route('settings.backup.index')
            ->with('error', 'Arquivo não encontrado.');
    }

    public function delete($filename)
    {
        $path = storage_path('app/backups/' . $filename);
        
        if (file_exists($path)) {
            unlink($path);
            return redirect()
                ->route('settings.backup.index')
                ->with('success', 'Backup excluído com sucesso!');
        }
        
        return redirect()
            ->route('settings.backup.index')
            ->with('error', 'Arquivo não encontrado.');
    }
}
