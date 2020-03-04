<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileUploader
{
    private $targetDirectory;

    // private $fs;

    // private $filePath;

    public function __construct($targetDirectory)//, Filesystem $fs, string $filePath
    {
        // $this->fs = $fs;
        // $this->filePath = $filePath;
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            dump($e);
        }

        return $fileName;
    }

    // public function delete(string $path)
    // {
    //     $this->fs->remove(
    //         $this->filePath . '/' . $path
    //     );
    // }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}