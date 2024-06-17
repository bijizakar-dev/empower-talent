<?php

namespace App\Controllers\Api;

use App\Models\Masterdata\EmployeesModel;
use App\Models\Masterdata\DepartmentsModel;
use App\Models\Masterdata\HolidaysModel;
use App\Models\Masterdata\ReferenceTypesModel;
use App\Models\Masterdata\RolesModel;
use App\Models\Masterdata\StatusEmploymentsModel;
use App\Models\Masterdata\TeamsModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Masterdata extends ResourceController
{
    function __construct() {
        $this->limit = 10;
        $this->m_department = new DepartmentsModel();
        $this->m_team = new TeamsModel();
        $this->m_status_employment = new StatusEmploymentsModel();
        $this->m_role = new RolesModel();
        $this->m_reference_type = new ReferenceTypesModel();
        $this->m_holiday = new HolidaysModel();
        $this->m_employee = new EmployeesModel();
    }

    private function start($page){
        return (($page - 1) * $this->limit);
    }

    /* DEPARTMENTS */
    public function getListDepartment(): ResponseInterface {
        // if(!$this->request->getVar('page')){
        //     return $this->respond(NULL, 400);
        // }
        
        $search = array(
            'search' => $this->request->getVar('search')
        );

        $start = $this->start($this->request->getVar('page'));

        $data = $this->m_department->get_list_department($this->limit, $start, $search);
        $data['page'] = (int)$this->request->getVar('page');
        $data['limit'] = $this->limit;
        
        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function getDepartment(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $data['data'] = $this->m_department->get_department($this->request->getVar('id'));
        $data['page'] = 1;
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function postDepartment(): ResponseInterface {
        $id = null;
        if($this->request->getPost('id')){
            $id = $this->request->getPost('id');
        }

        $add = array (
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'active' => $this->request->getPost('active')
        );

        $data = $this->m_department->update_department($add);

        return $this->respond($data, 200);
    }

    public function deleteDepartment(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $result = $this->m_department->delete_department($this->request->getVar('id'));

        if($result){
            return $this->respond(array('status' => $result), 200); 
        }else{
            return $this->respond(array('status' => false), 200);
        }
    }
    /* DEPARTMENTS */

    /* TEAMS */
    public function getListTeam(): ResponseInterface {
        if(!$this->request->getVar('page')){
            return $this->respond(NULL, 400);
        }
        
        $search = array(
            'search' => $this->request->getVar('search')
        );

        $start = $this->start($this->request->getVar('page'));

        $data = $this->m_team->get_list_team($this->limit, $start, $search);
        $data['page'] = (int)$this->request->getVar('page');
        $data['limit'] = $this->limit;
        
        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function getTeam(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $data['data'] = $this->m_team->get_team($this->request->getVar('id'));
        $data['page'] = 1;
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function postTeam(): ResponseInterface {
        $id = null;
        if($this->request->getPost('id')){
            $id = $this->request->getPost('id');
        }

        $add = array (
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'active' => $this->request->getPost('active')
        );

        $data = $this->m_team->update_team($add);

        return $this->respond($data, 200);
    }

    public function deleteTeam(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $result = $this->m_team->delete_team($this->request->getVar('id'));

        if($result){
            return $this->respond(array('status' => $result), 200); 
        }else{
            return $this->respond(array('status' => false), 200);
        }
    }
    /* TEAMS */

    /* STATUS EMPLOYMENT*/
    public function getListStatusEmployment(): ResponseInterface {
        if(!$this->request->getVar('page')){
            return $this->respond(NULL, 400);
        }
        
        $search = array(
            'search' => $this->request->getVar('search')
        );

        $start = $this->start($this->request->getVar('page'));

        $data = $this->m_status_employment->get_list_status_employment($this->limit, $start, $search);
        $data['page'] = (int)$this->request->getVar('page');
        $data['limit'] = $this->limit;
        
        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function getStatusEmployment(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $data['data'] = $this->m_status_employment->get_status_employment($this->request->getVar('id'));
        $data['page'] = 1;
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function postStatusEmployment(): ResponseInterface {
        $id = null;
        if($this->request->getPost('id')){
            $id = $this->request->getPost('id');
        }

        $add = array (
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'active' => $this->request->getPost('active')
        );

        $data = $this->m_status_employment->update_status_employment($add);

        return $this->respond($data, 200);
    }

    public function deleteStatusEmployment(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $result = $this->m_status_employment->delete_status_employment($this->request->getVar('id'));

        if($result){
            return $this->respond(array('status' => $result), 200); 
        }else{
            return $this->respond(array('status' => false), 200);
        }
    }
    /* STATUS EMPLOYMENT*/

    /* ROLES */
    public function getListRole(): ResponseInterface {
        if(!$this->request->getVar('page')){
            return $this->respond(NULL, 400);
        }
        
        $search = array(
            'search' => $this->request->getVar('search')
        );

        $start = $this->start($this->request->getVar('page'));

        $data = $this->m_role->get_list_role($this->limit, $start, $search);
        $data['page'] = (int)$this->request->getVar('page');
        $data['limit'] = $this->limit;
        
        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function getRole(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $data['data'] = $this->m_role->get_role($this->request->getVar('id'));
        $data['page'] = 1;
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function postRole(): ResponseInterface {
        $id = null;
        if($this->request->getPost('id')){
            $id = $this->request->getPost('id');
        }

        $add = array (
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'active' => $this->request->getPost('active')
        );

        $data = $this->m_role->update_role($add);

        return $this->respond($data, 200);
    }

    public function deleteRole(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $result = $this->m_role->delete_role($this->request->getVar('id'));

        if($result){
            return $this->respond(array('status' => $result), 200); 
        }else{
            return $this->respond(array('status' => false), 200);
        }
    }
    /* ROLES */

    /* REFERENCE TYPES */
    public function getListReferenceType(): ResponseInterface {
        if(!$this->request->getVar('page')){
            return $this->respond(NULL, 400);
        }
        
        $search = array(
            'search' => $this->request->getVar('search'),
            'category' => $this->request->getVar('category'),
            'name' => $this->request->getVar('name'),
        );

        $start = $this->start($this->request->getVar('page'));

        $data = $this->m_reference_type->get_list_reference_type($this->limit, $start, $search);
        $data['page'] = (int)$this->request->getVar('page');
        $data['limit'] = $this->limit;
        
        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function getReferenceType(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $data['data'] = $this->m_reference_type->get_reference_type($this->request->getVar('id'));
        $data['page'] = 1;
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function postReferenceType(): ResponseInterface {
        $id = null;
        if($this->request->getPost('id')){
            $id = $this->request->getPost('id');
        }

        $add = array (
            'id' => $id,
            'category' => $this->request->getPost('category'),
            'name' => $this->request->getPost('name'),
            'active' => $this->request->getPost('active')
        );

        $data = $this->m_reference_type->update_reference_type($add);

        return $this->respond($data, 200);
    }

    public function deleteReferenceType(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $result = $this->m_reference_type->delete_reference_type($this->request->getVar('id'));

        if($result){
            return $this->respond(array('status' => $result), 200); 
        }else{
            return $this->respond(array('status' => false), 200);
        }
    }
    /* REFERENCE TYPES */

    /* HOLIDAYS */
    public function getListHoliday(): ResponseInterface {
        if(!$this->request->getVar('page')){
            return $this->respond(NULL, 400);
        }
        
        $search = array(
            'search' => $this->request->getVar('search'),
            'date' => $this->request->getVar('date'),
            'name' => $this->request->getVar('name'),
        );

        $start = $this->start($this->request->getVar('page'));

        $data = $this->m_holiday->get_list_holiday($this->limit, $start, $search);
        $data['page'] = (int)$this->request->getVar('page');
        $data['limit'] = $this->limit;
        
        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function getHoliday(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $data['data'] = $this->m_holiday->get_holiday($this->request->getVar('id'));
        $data['page'] = 1;
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function postHoliday(): ResponseInterface {
        $id = null;
        if($this->request->getPost('id')){
            $id = $this->request->getPost('id');
        }

        $add = array (
            'id' => $id,
            'date' => $this->request->getPost('date'),
            'name' => $this->request->getPost('name'),
            'national_holiday' => $this->request->getPost('national_holiday'),
            'active' => $this->request->getPost('active')
        );

        $data = $this->m_holiday->update_holiday($add);

        return $this->respond($data, 200);
    }

    public function deleteHoliday(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $result = $this->m_holiday->delete_holiday($this->request->getVar('id'));

        if($result){
            return $this->respond(array('status' => $result), 200); 
        }else{
            return $this->respond(array('status' => false), 200);
        }
    }

    public function getNationalHolidayAPI(): ResponseInterface {
        $url = "https://dayoffapi.vercel.app/api";

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        curl_close($curl);

        $data = json_decode($response);
        if(!empty($data)) {
            foreach ($data as $val) {
                $add = array (
                    'id' => null,
                    'date' => $val->tanggal,
                    'name' => $val->keterangan,
                    'national_holiday' => $val->is_cuti,
                    'active' => 1
                );
        
                $data = $this->m_holiday->update_holiday($add);
            }

            return $this->respond($data, 200); 
        } else {
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    /* HOLIDAYS */
    
    /* EMPLOYEES */
    public function getListEmployee(): ResponseInterface {
        if(!$this->request->getVar('page')){
            return $this->respond(NULL, 400);
        }

        $search = array(
            'search'            => $this->request->getVar('search'),
            'nip'               => $this->request->getVar('nip'),
            'name'              => $this->request->getVar('name'),
            'gender'            => $this->request->getVar('gender'),
            'birth_date'        => $this->request->getVar('birth_date'),
            'education'         => $this->request->getVar('education'),
            'id_team'           => $this->request->getVar('id_team'),
            'id_department'     => $this->request->getVar('id_department'),
            'id_status_employment'  => $this->request->getVar('id_status_employment'),
            'active'            => $this->request->getVar('active'),
        );

        $start = $this->start($this->request->getVar('page'));

        $data = $this->m_employee->get_list_employee($this->limit, $start, $search);
        $data['page'] = (int)$this->request->getVar('page');
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function getEmployee(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $data['data'] = $this->m_employee->get_employee($this->request->getVar('id'));
        $data['page'] = 1;
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    private function generateNIP($seq, $idTeam, $joinYear): String {
        $seq = str_pad($seq, 3, '0', STR_PAD_LEFT);
        $idTeam = str_pad($idTeam, 2, '0', STR_PAD_LEFT);

        $nip = $seq.$idTeam.$joinYear;

        return $nip;
    }

    public function postEmployee(): ResponseInterface {
        $id = null;
        if($this->request->getPost('id')){
            $id = $this->request->getPost('id');
        }

        $add = array (
            'id' => $id,
            'nip' => $this->request->getPost('nip'),
            'name' => $this->request->getPost('name'),
            'gender' => $this->request->getPost('gender'),
            'birth_date' => $this->request->getPost('birth_date'),
            'age' => null,
            'phone_number' => $this->request->getPost('phone_number'),
            'address' => $this->request->getPost('address'),
            'education' => $this->request->getPost('education'),
            'id_team' => $this->request->getPost('id_team'),
            'id_department' => $this->request->getPost('id_department'),
            'id_status_employment' => $this->request->getPost('id_status_employment'),
            'join_date' => $this->request->getPost('join_date'),
            'leaving_date' => $this->request->getPost('leaving_date') != '' ?  $this->request->getPost('leaving_date') : NULL,
            'photo' => null,
            'active' => $this->request->getPost('active')
        );

        $insEmployee = $this->m_employee->update_employee($add);

        // header('Content-Type: application/json');
        // die(json_encode($insEmployee));

        if($this->request->getPost('nip') == '' || $this->request->getPost('nip') == null) {
            if($insEmployee) {
                $joinYear = $this->request->getPost('join_date') == null ? date('Y-m-d') : $this->request->getPost('join_date');
                $nip = $this->generateNIP($insEmployee['id'], $this->request->getPost('id_team'), date('Y', strtotime($joinYear)));
                $addNIP = array (
                    'id' => $insEmployee['id'],
                    'nip' => $nip,
                );
                $this->m_employee->update_employee($addNIP);
            }
        }
        

        return $this->respond($insEmployee, 200);
    }

    public function deleteEmployee(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $result = $this->m_employee->delete_employee($this->request->getVar('id'));

        if($result){
            return $this->respond(array('status' => $result), 200); 
        }else{
            return $this->respond(array('status' => false), 200);
        }
    }
    /* EMPLOYEES */

}
