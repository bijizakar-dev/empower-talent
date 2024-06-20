<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Masterdata\DepartmentsModel;
use App\Models\Masterdata\StatusEmploymentsModel;
use App\Models\Masterdata\TeamsModel;
use CodeIgniter\HTTP\ResponseInterface;

class Masterdata extends BaseController
{
    function __construct() {
        $this->m_department = new DepartmentsModel();
        $this->m_team = new TeamsModel();
        $this->m_status_employment = new StatusEmploymentsModel();
    }

    public function getDepartment() {
        $data['title'] = "Department";

        return view('masterdata/department', $data);
    }

    public function getTeam() {
        $data['title'] = "Team";

        return view('masterdata/team', $data);
    }

    public function getHoliday() {
        $data['title'] = "Jadwal Libur";

        return view('masterdata/holiday', $data);
    }

    public function getStatusEmployee() {
        $data['title'] = "Status Kepegawaian";

        return view('masterdata/status_employee', $data);
    }

    public function getReferenceType() {
        $data['title'] = "Referensi Jenis";

        return view('masterdata/reference_type', $data);
    }

    public function getEmployee() {
        $data['title'] = "Pegawai";
        $data['department'] = $this->m_department->get_all_department();
        $data['team'] = $this->m_team->get_all_team();
        $data['status_employment'] = $this->m_status_employment->get_all_statEmployment();

        return view('masterdata/employee', $data);
    }
}
