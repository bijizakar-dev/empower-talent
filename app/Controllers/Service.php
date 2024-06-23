<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Masterdata\EmployeesModel;
use App\Models\Masterdata\ReferenceTypesModel;
use App\Models\Masterdata\RolesModel;
use CodeIgniter\HTTP\ResponseInterface;

class Service extends BaseController
{
    function __construct() {
        $this->m_employee = new EmployeesModel();
        $this->m_type = new ReferenceTypesModel();
    }

    public function getPermit() {
        $data['title'] = "Izin";
        $data['employee'] =  $this->m_employee->get_all_employee();
        $data['reference_type'] =  $this->m_type->get_all_type('Izin');

        return view('service/permit', $data);
    }

}
