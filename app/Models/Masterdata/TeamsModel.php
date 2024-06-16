<?php

namespace App\Models\Masterdata;

use CodeIgniter\Model;

class TeamsModel extends Model
{
    protected $table            = 'teams';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['name', 'description', 'active', 'deleted_at'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function get_list_team($limit, $start, $search) {
        $q = '';
        $limit = " limit $start , $limit";

        if ($search['search'] != '') {
            $q .= "WHERE name LIKE '%".$search['search']."%' OR name LIKE '%".$search['search']."%'";
        }

        $count = "select count(id) as count ";
        $select = "SELECT *";
        $sql = " FROM teams 
                WHERE deleted_at is null  
                $q order by name asc ";

        $query = $this->query($select.$sql.$limit);
        $result['data'] = $query->getResult();
        $result['jumlah'] = $this->query($count.$sql)->getRow()->count;

        return $result;
    }

    function get_team($id) {
        $sql = "SELECT * FROM teams WHERE id = $id ";
        return $this->query($sql)->getRow();
    }

    function update_team($data) {
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

    function delete_team($id) {
        $data = array('deleted_at' => date('Y-m-d H:i:s'));
        
        $res = $this->where('id', $id)->update($id, $data);
        
        return $res;
    }
}
