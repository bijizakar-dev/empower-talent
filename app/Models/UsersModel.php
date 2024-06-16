<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class UsersModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['username', 'email', 'password', 'id_employee', 'id_role', 'active', 'deleted_at'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    protected function hashPassword(String $password)
    {
        if ($password != '') {
            $password = password_hash($password, PASSWORD_DEFAULT);
        }
        return $password;
    }

    function get_list_user($limit, $start, Array $search) {
        $q = '';
        $limit = " limit $start , $limit";

        if ($search['search'] != '') {
            $q .= "AND u.username LIKE '%".$search['search']."%' ";
        }
        if ($search['id_role'] != '') {
            $q .= "AND u.id_role LIKE '%".$search['id_role']."%' ";
        }
        if ($search['active'] != '') {
            $q .= "AND u.active LIKE '%".$search['active']."%' ";
        }

        
        $select = "SELECT u.* , e.name as employee_name, r.name as role_name ";
        $sql = " FROM users u 
                JOIN employees e ON (u.id_employee = e.id)
                JOIN roles r ON (u.id_role = r.id)
                WHERE u.deleted_at is null 
                $q order by u.username asc";

        $query = $this->query($select.$sql.$limit);
        $result['data'] = $query->getResult();

        $count = "SELECT count(*) as count ";
        $result['jumlah'] = $this->query($count.$sql)->getRow()->count;

        return $result;
    }

    function get_user($id) {
        $sql = "SELECT u.* , e.name as employee_name, r.name as role_name 
                FROM users u 
                JOIN employees e ON (u.id_employee = e.id)
                JOIN roles r ON (u.id_role = r.id)
                WHERE u.deleted_at is null  
                    AND u.id = $id 
                LIMIT 1";
                
        return $this->query($sql)->getRow();
    }

    function update_user(Array $param) {
        $data = [
            'success' => false,
            'message' => '',
            'id' => null,
        ];
    
        $validation = service('validation'); 
        $validation->setRules([
            'username'  => 'required|is_unique[users.username,id,'          . (isset($param['id']) ? $param['id'] : 'NULL') . ']',
            'email'     => 'required|valid_email|is_unique[users.email,id,' . (isset($param['id']) ? $param['id'] : 'NULL') . ']',
            'password'  => isset($param['password']) ? 'required' : ''
        ]);
    
        if (!$validation->run($param)) {
            $data['message'] = implode('<br>', $validation->getErrors());
            return $data;
        }
    
        $param['password'] = $this->hashPassword($param['password']);
    
        try {
            if (isset($param['id']) && !empty($param['id'])) {
                // Update
                $res = $this->update($param['id'], $param);
                if (!$res) {
                    throw new Exception($this->error()['message']);
                }
                $data['id'] = $param['id'];
                $data['message'] = "Berhasil mengubah data user";
            } else {
                // Insert
                $res = $this->insert($param);
                if (!$res) {
                    throw new Exception($this->error()['message']);
                }
                $data['id'] = $this->insertID();
                $data['message'] = "Berhasil menambahkan data user";
            }
    
            $data['success'] = true;
        } catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }
    
        return $data;
    }
    
    function delete_user($id) {
        $data = array('deleted_at' => date('Y-m-d H:i:s'));
        
        $res = $this->where('id', $id)->update($id, $data);
        
        return $res;
    }
}
