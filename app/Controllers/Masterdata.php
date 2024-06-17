<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Masterdata extends BaseController
{

    public function getDepartment() {
        
        return view('masterdata/department');
    }
}
