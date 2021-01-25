<?php

namespace Devlob\Http\Middlewares;

use Exception;

class CheckForMaintenance
{
    /**
     * Exit if the application is under maintenance.
     *
     * @throws Exception
     */
    public function handle()
    {
        if (file_exists('down')) {
            view('vendor.maintenance');

            exit();
        }

        return true;
    }
}