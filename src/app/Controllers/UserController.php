<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\Interfaces\UserControllerInterface;
use App\Services\UserService;
use Exception;

class UserController implements UserControllerInterface
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @throws Exception
     */
    public function handleFileImport(): void
    {
        /**
         * @todo Унифицировать под загрузку файла под любым ключом, а не только под ключом csv_data_file
         */
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_data_file'])) {
            $file = $_FILES['csv_data_file'];

            if ($file['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Error while uploading file');
            }

            $tmpFilePath = $file['tmp_name'];
            $this->userService->importUsersFromCSV($tmpFilePath);
        } else {
            throw new Exception('File don\'t uploaded');
        }
    }
}