<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FileUploadService
{
    private $params;
    private $logger;

    public function __construct(ParameterBagInterface $params, LoggerInterface $logger)
    {
        $this->params = $params;
        $this->logger = $logger;
    }

    public function uploadFile(UploadedFile $file, string $directoryName, ?string $oldFileName = null): string
    {
        // Supprimer l'ancien fichier si nécessaire
        if ($oldFileName) {
            $this->logger->info("Deleting old file: {$oldFileName}--{$directoryName}");
            $this->deleteFile($oldFileName, $directoryName);
        }

        // Générer un nouveau nom de fichier unique
        $fileName = md5(uniqid()) . '.' . $file->getClientOriginalExtension();

        // Déplacer le fichier vers le répertoire cible
        $file->move($this->getUploadPath($directoryName), $fileName);

        return $fileName;
    }

    public function deleteFile(?string $fileName, string $directoryName): void
    {
        if ($fileName) {
            $filePath = $this->getUploadPath($directoryName) . '/' . $fileName;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }

    public function deleteDirectory(string $directoryPath): void
    {
        if (is_dir($directoryPath)) {
            $files = array_diff(scandir($directoryPath), ['.', '..']);
            foreach ($files as $file) {
                $filePath = "{$directoryPath}/{$file}";
                if (is_dir($filePath)) {
                    $this->deleteDirectory($filePath);
                } else {
                    unlink($filePath);
                }
            }
            rmdir($directoryPath);
        }
    }

    public function getUploadPath(string $directoryName): string
    {
        return $this->params->get($directoryName);
    }
}
