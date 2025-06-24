<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DeploymentController extends Controller
{
    public function storageLink()
    {
        $target = storage_path('app/public');
        $link = public_path('storage');

        if (file_exists($link)) {
            return 'The "public/storage" directory already exists.';
        }

        try {
            symlink($target, $link);
            return 'The [public/storage] directory has been linked.';
        } catch (\Exception $e) {
            return 'Error creating symlink: ' . $e->getMessage();
        }
    }
}
