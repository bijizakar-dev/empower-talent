<?php

namespace App\Models\Masterdata;

use CodeIgniter\Model;
use Exception;

class EmployeesModel extends Model
{
    protected $table            = 'employees';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'nip', 'name', 'gender', 'birth_date', 'birth_date',
        'age', 'phone_number', 'address', 'education', 'id_team', 'id_department',
        'id_status_employment', 'join_date', 'leaving_date', 'photo', 'active', 'deleted_at'
    ];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function get_list_employee($limit, $start, $search) {
        $q = '';
        $limit = " limit $start , $limit";

        if ($search['search'] != '') {
            $q .= "AND ( e.name LIKE '%".$search['search']."%' 
                OR e.nip LIKE '%".$search['search']."%'
                OR t.name LIKE '%".$search['search']."%' 
                OR d.name LIKE '%".$search['search']."%'
                OR se.name LIKE '%".$search['search']."%' ) ";
        }

        if ($search['nip'] != '') {
            $q .= "AND e.nip LIKE '%".$search['nip']."%' ";
        }
        if ($search['name'] != '') {
            $q .= "AND e.name LIKE '%".$search['name']."%' ";
        }
        if ($search['gender'] != '') {
            $q .= "AND e.gender LIKE '%".$search['gender']."%' ";
        }
        if ($search['birth_date'] != '') {
            $q .= "AND e.birth_date LIKE '%".$search['birth_date']."%' ";
        }
        if ($search['education'] != '') {
            $q .= "AND e.education LIKE '%".$search['education']."%' ";
        }
        if ($search['id_team'] != '') {
            $q .= "AND e.id_team LIKE '%".$search['id_team']."%' ";
        }
        if ($search['id_department'] != '') {
            $q .= "AND e.id_department LIKE '%".$search['id_department']."%' ";
        }
        if ($search['id_status_employment'] != '') {
            $q .= "AND e.id_status_employment LIKE '%".$search['id_status_employment']."%' ";
        }
        if ($search['active'] != '') {
            $q .= "AND e.active LIKE '%".$search['active']."%' ";
        }

        
        $select = "SELECT e.* , t.name as team_name, d.name as department_name, se.name as status_employment ";
        $sql = " FROM employees e  
                JOIN teams t ON (e.id_team = t.id)
                JOIN departments d ON (e.id_department = d.id)
                JOIN status_employments se ON (e.id_status_employment = se.id)
                WHERE e.deleted_at is null 
                $q order by e.id asc ";

        $query = $this->query($select.$sql.$limit);
        $result['data'] = $query->getResult();

        $count = "SELECT count(*) as count ";
        $result['jumlah'] = $this->query($count.$sql)->getRow()->count;

        return $result;
    }

    function get_employee($id) {
        $sql = "SELECT e.*, t.name as team_name, d.name as department_name, se.name as status_employment 
                FROM employees e  
                JOIN teams t ON (e.id_team = t.id)
                JOIN departments d ON (e.id_department = d.id)
                JOIN status_employments se ON (e.id_status_employment = se.id)
                WHERE e.deleted_at is null 
                    AND e.id = $id 
                LIMIT 1";
                
        return $this->query($sql)->getRow();
    }
    
    function update_employee($param) {
        $data = array(
            'success' => false,
            'message' => '',
            'id' => null
        );
        
        try {
            if (isset($param['id']) && !empty($param['id'])) {
                // Update
                $res = $this->where('id', $param['id'])->update($param['id'], $param);
                if (!$res) {
                    throw new Exception($this->error()['message']);
                }
                $data['id'] = $param['id'];
                $data['message'] = "Berhasil mengubah data pegawai";
            } else {
                // Insert
                $res = $this->insert($param);
                if (!$res) {
                    throw new Exception($this->error()['message']);
                }
                $data['id'] = $this->insertID();
                $data['message'] = "Berhasil menambahkan data pegawai";
            }

            $data['success'] = true;
        } catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    function delete_employee($id) {
        $data = array('deleted_at' => date('Y-m-d H:i:s'));
        
        $res = $this->where('id', $id)->update($id, $data);
        
        return $res;
    }
}
