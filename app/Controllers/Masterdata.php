<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Masterdata extends BaseController
{

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
}
