<?php

namespace App\Models\Masterdata;

use CodeIgniter\Model;

class HolidaysModel extends Model
{
    protected $table            = 'holidays';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['date', 'name', 'national_holiday', 'active', 'deleted_at'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function get_list_holiday($limit, $start, $search) {
        $q = '';
        $limit = " limit $start , $limit";

        if ($search['search'] != '') {
            $q .= "AND name LIKE '%".$search['search']."%' OR name LIKE '%".$search['search']."%'";
        }

        $count = "select count(id) as count ";
        $select = "SELECT *";
        $sql = " FROM holidays 
                WHERE deleted_at is null  
                $q order by date desc ";

        $query = $this->query($select.$sql);
        $result['data'] = $query->getResult();
        $result['jumlah'] = $this->query($count.$sql)->getRow()->count;

        return $result;
    }

    function get_holiday($id) {
        $sql = "SELECT * FROM holidays WHERE id = $id ";
        return $this->query($sql)->getRow();
    }

    function update_holiday($data) {
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

    function delete_holiday($id) {
        $data = array('deleted_at' => date('Y-m-d H:i:s'));
        
        $res = $this->where('id', $id)->update($id, $data);
        
        return $res;
    }
}
