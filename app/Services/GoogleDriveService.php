<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\Permission;
use Illuminate\Support\Facades\Storage;

class GoogleDriveService
{
    protected $client;
    protected $driveService;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('/credentials/google-drive.json'));
        $this->client->addScope(Drive::DRIVE_FILE);
        $this->client->setAccessType('offline');

        $this->driveService = new Drive($this->client);
    }

    /**
     * Upload file ke Google Drive
     */
    public function uploadFile($file)
    {
        $filePath = $file->store('temp'); // Simpan sementara di storage Laravel
        $fileData = file_get_contents(storage_path("app/$filePath"));

        $driveFile = new DriveFile();
        $driveFile->setName($file->getClientOriginalName());
        $driveFile->setParents([env('GOOGLE_DRIVE_FOLDER_ID')]); // Pastikan Folder ID di sini benar

        $createdFile = $this->driveService->files->create($driveFile, [
            'data' => $fileData,
            'mimeType' => $file->getClientMimeType(),
            'uploadType' => 'multipart'
        ]);



        Storage::delete($filePath); // Hapus file sementara

        // Buat file menjadi publik agar bisa diakses langsung
        $this->setFilePublic($createdFile->id);

        return $createdFile->id; // Kembalikan ID file Google Drive
    }

    /**
     * Mengatur izin file agar bisa diakses oleh siapa saja
     */
    public function setFilePublic($fileId)
    {
        $permission = new Permission();
        $permission->setType('anyone');
        $permission->setRole('reader');

        $this->driveService->permissions->create($fileId, $permission);
    }

    /**
     * Menghapus file dari Google Drive
     */
    public function deleteFile($fileId)
    {
        $this->driveService->files->delete($fileId);
    }
}
