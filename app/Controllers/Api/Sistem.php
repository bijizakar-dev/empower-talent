<?php

namespace App\Controllers\Api;

use App\Models\UsersModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\Masterdata\EmployeesModel;

class Sistem extends ResourceController
{
    function __construct() {
        $this->limit = 10;
        $this->m_employee = new EmployeesModel();
        $this->m_user = new UsersModel();
    }

    private function start($page){
        return (($page - 1) * $this->limit);

        // header('Content-Type: application/json');
        // die(json_encode($insEmployee));

    }

    /* DATA USER */
    public function getListUser(): ResponseInterface {
        // if(!$this->request->getVar('page')){
        //     return $this->respond(NULL, 400);
        // }

        $search = array(
            'search'            => $this->request->getVar('search'),
            'id_role'           => $this->request->getVar('id_role'),
            'active'            => $this->request->getVar('active')
        );

        $start = $this->start($this->request->getVar('page'));

        $data = $this->m_user->get_list_user($this->limit, $start, $search);
        $data['page'] = (int)$this->request->getVar('page');
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function getUser(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $data['data'] = $this->m_user->get_user($this->request->getVar('id'));
        $data['page'] = 1;
        $data['limit'] = $this->limit;

        if($data){
            return $this->respond($data, 200); 
        }else{
            return $this->respond(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    public function postUser(): ResponseInterface {
        $id = null;
        if($this->request->getPost('id')){
            $id = $this->request->getPost('id');
        }

        $add = array (
            'id' => $id,
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'id_employee' => $this->request->getPost('id_employee'),
            'id_role' => $this->request->getPost('id_role'),
            'active' => $this->request->getPost('active')
        );

        $data = $this->m_user->update_user($add);

        return $this->respond($data, 200);
    }

    public function deleteUser(): ResponseInterface {
        if(!$this->request->getVar('id')){
            return $this->respond(NULL, 400);
        }

        $result = $this->m_user->delete_user($this->request->getVar('id'));

        if($result){
            return $this->respond(array('status' => $result), 200); 
        }else{
            return $this->respond(array('status' => false), 200);
        }
    }
    /* DATA USER */
}
