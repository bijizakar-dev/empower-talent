<?php

namespace App\Models\Masterdata;

use CodeIgniter\Model;

class ReferenceTypesModel extends Model
{
    protected $table            = 'reference_types';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['category', 'name', 'active', 'deleted_at'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function get_list_reference_type($limit, $start, $search) {
        $q = '';
        $limit = " limit $start , $limit";

        if ($search['search'] != '') {
            $q .= "AND name LIKE '%".$search['search']."%' OR name LIKE '%".$search['search']."%'";
        }

        $count = "select count(id) as count ";
        $select = "SELECT *";
        $sql = " FROM reference_types 
                WHERE deleted_at is null  
                $q order by name asc ";

        $query = $this->query($select.$sql.$limit);
        $result['data'] = $query->getResult();
        $result['jumlah'] = $this->query($count.$sql)->getRow()->count;

        return $result;
    }

    function get_reference_type($id) {
        $sql = "SELECT * FROM reference_types WHERE id = $id ";
        return $this->query($sql)->getRow();
    }

    function update_reference_type($data) {
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

    function delete_reference_type($id) {
        $data = array('deleted_at' => date('Y-m-d H:i:s'));
        
        $res = $this->where('id', $id)->update($id, $data);
        
        return $res;
    }
}
