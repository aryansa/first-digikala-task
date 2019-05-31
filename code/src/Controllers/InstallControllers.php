<?php

namespace App\Controllers;

use App\Models\InstallModel;
use Symfony\Component\HttpFoundation\Response;

class InstallControllers
{
    public function install()
    {
        (new InstallModel())->run();
        return Response::create('Done');
    }

}