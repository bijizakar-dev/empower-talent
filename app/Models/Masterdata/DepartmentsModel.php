<?php

namespace App\Models\Masterdata;

use CodeIgniter\Model;

class DepartmentsModel extends Model
{
    protected $table            = 'departments';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['name', 'description', 'active', 'deleted_at'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;


    function get_list_department($limit, $start, $search) {
        $q = '';
        $limit = " limit $start , $limit";

        if ($search['search'] != '') {
            $q .= "AND name LIKE '%".$search['search']."%' OR name LIKE '%".$search['search']."%'";
        }

        $count = "select count(id) as count ";
        $select = "SELECT *";
        $sql = " FROM departments 
                WHERE deleted_at is null  
                $q order by name asc ";

        $query = $this->query($select.$sql.$limit);
        $result['data'] = $query->getResult();
        $result['jumlah'] = $this->query($count.$sql)->getRow()->count;

        return $result;
    }

    function get_department($id) {
        $sql = "SELECT * FROM departments WHERE id = $id ";
        return $this->query($sql)->getRow();
    }

    function update_department($data) {
        $data['success'] = false;
        $data['message'] = '';

        if ($data['id']) {
            // Update
            $data['success'] = true;
            $this->where('id', $data['id'])->update($data['id'], $data);
            $data['id'] = $data['id'];
            
        }else{
            // insert
            $data['success'] = true;
            $this->insert($data);
            $data['id'] = $this->insertID();
        }
        
        return $data;
    }

    function delete_department($id) {
        $data = array('deleted_at' => date('Y-m-d H:i:s'));
        
        $res = $this->where('id', $id)->update($id, $data);
        
        return $res;
    }
}
