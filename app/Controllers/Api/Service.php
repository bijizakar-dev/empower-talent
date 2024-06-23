<?php

namespace App\Controllers\Api;

use App\Models\Masterdata\EmployeesModel;
use App\Models\Service\PermitsModel;
use App\Models\UsersModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use DateTime;

class Service extends ResourceController
{
    function __construct() {
        $this->limit = 10;
        $this->m_employee = new EmployeesModel();
        $this->m_user = new UsersModel();
        $this->m_permit = new PermitsModel();
    }

    private function start($page){
        return (($page - 1) * $this->limit);

        // header('Content-Type: application/json');
        // die(json_encode($insEmployee));

    }

    /* DATA USER */
    public function getListPermit(): ResponseInterface {
        // if(!$this->request->getVar('page')){
        //     return $this->respond(NULL, 400);
        // }

        $search = array(
            'search'                => $this->request->getVar('search'),
            'id_employee'           => $this->request->getVar('end_date'),
            'id_type'               => $this->request->getVar('id_type'),
            'start_date'            => $this->request->getVar('start_date'),
            'end_date'              => $this->request->getVar('end_date'),
            'created_start'         => $this->request->getVar('created_start'),
            'created_end'           => $this->request->getVar('created_end'),
            'status'                => $this->request->getVar('status'),
        );

        $start = $this->start($this->request->getVar('page'));

        $data = $this->m_permit->get_list_permit($this->limit, $start, $search);
        $data['page'] = (int)$this->request->getVar('page');
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function getPermit(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $data['data'] = $this->m_permit->get_permit($this->request->getVar('id'));
        $data['page'] = 1;
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function postPermit(): ResponseInterface {
        $id = null;
        if($this->request->getPost('id')){
            $id = $this->request->getPost('id');
        }

        // check Permit Duration Type (Hari atau Jam)
        $duration = '';
        $duration_type = $this->request->getPost('duration_type');
        $start_date = new DateTime(strval($this->request->getPost('start_date')));
        $end_date = new DateTime(strval($this->request->getPost('end_date')));

        

        if (!empty($duration_type) && $duration_type == 'Hari') {
            $interval = $start_date->diff($end_date);
            $duration = $interval->days + 1 .' Hari'; 
        } else {
            $interval = $start_date->diff($end_date);
            $duration = $interval->h .':'. $interval->i.' Jam'; 
        }

        // header('Content-Type: application/json');
        // die(json_encode($duration));

        $add = array (
            'id' => $id,
            'id_employee' => $this->request->getPost('id_employee'),
            'id_type' => $this->request->getPost('id_type'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'duration' => $duration,
            'reason' => $this->request->getPost('reason'),
            'file' => NULL,
            'status' => 'Submitted'
        );

        $data = $this->m_permit->update_permit($add);

        return $this->respond($data, 200);
    }

    function postUpdateStatusPermit() {
        $id = null;
        if($this->request->getPost('id')){
            $id = $this->request->getPost('id');
        } else {
            return $this->respond(array('status' => false), 200);
        }

        $param = array (
            'id' => $id,
            'status' => $this->request->getPost('status'),
            'reason_rejected' => $this->request->getPost('reason_rejected'),
            'note' => $this->request->getPost('note'),
            'id_user_decide' => 1, // nnti diambil dari session login
        );

        $data = $this->m_permit->update_permit($param);

        return $this->respond($data, 200);
    }

    public function deletePermit(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $result = $this->m_permit->delete_permit($this->request->getVar('id'));

        if($result){
            return $this->respond(array('status' => $result), 200); 
        }else{
            return $this->respond(array('status' => false), 200);
        }
    }
}
