<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Masterdata\EmployeesModel;
use App\Models\Masterdata\RolesModel;
use CodeIgniter\HTTP\ResponseInterface;

class Sistem extends BaseController
{
    function __construct() {
        $this->m_employee = new EmployeesModel();
        $this->m_role = new RolesModel();
    }

    public function getRole() {
        $data['title'] = "Role";

        return view('sistem/role', $data);
    }

    public function getUser() {
        $data['title'] = "User";
        $data['employee'] =  $this->m_employee->get_all_employee();
        $data['role'] =  $this->m_role->get_all_role();

        return view('sistem/user', $data);
    }

}
