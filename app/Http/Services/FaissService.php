<?php

namespace App\Services;

class FaissService
{
    protected $scriptPath;

    public function __construct()
    {
        $this->scriptPath = base_path('app/python-scripts/faiss_service.py');
    }

    public function addEmbedding(string $jsonFilePath): string
    {
        $command = escapeshellcmd("python {$this->scriptPath} add_embedding \"{$jsonFilePath}\"");
        return shell_exec($command);
    }

    public function searchEmbedding(string $queryJson): string
    {
        $command = escapeshellcmd("python {$this->scriptPath} search \"{$queryJson}\"");
        return shell_exec($command);
    }
}
