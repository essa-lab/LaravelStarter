<?php

namespace App\Services\Interfaces;

interface UploadFilesInterface
{
    public function getPath(string $fileName): string;
    public function save(array $filePath): string;
    public function update(string $filePath,array $newFile);
    public function delete(string $filePath):void;   
}
