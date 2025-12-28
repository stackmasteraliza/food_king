<?php
/**
 * Created by PhpStorm.
 * User: Dipok
 * Date: 12.05.16
 * Time: 07:24
 */

return [
    'pathToEnv'       => base_path('.env'),
    'backupPath'      => resource_path('backups/dotenv-editor/'),
    'filePermissions' => env('FILE_PERMISSIONS', 0755),
];
