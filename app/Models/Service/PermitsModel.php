<?php

namespace App\Models\Service;

use CodeIgniter\Model;
use Exception;

class PermitsModel extends Model
{
    protected $table            = 'permits';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'id_employee', 'id_type', 'start_date', 'end_date', 'duration',
        'reason', 'reason_rejected', 'note', 'file', 'status', 'id_user_decide', 'deleted_at'
    ];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function get_list_permit($limit, $start, $search) {
        $q = '';
        // $limit = " limit $start , $limit";

        if ($search['search'] != '') {
            $q .= "AND ( e.name LIKE '%".$search['search']."%' 
                OR e.nip LIKE '%".$search['search']."%'
                OR t.name LIKE '%".$search['search']."%' 
                OR d.name LIKE '%".$search['search']."%'
                OR r.name LIKE '%".$search['search']."%' ) ";
        }

        if ($search['start_date'] != '' and $search['end_date'] != '') {
            $q.=" AND (date(p.start_date) between '".$search['start_date']."' AND '".$search['end_date']."')";
        }

        if ($search['created_start'] != '' and $search['created_end'] != '') {
            $q.=" AND (date(p.created_at) between '".$search['created_start']."' AND '".$search['created_end']."')";
        }

        if (!empty($search['status'])) {
            $statuses = explode(',', $search['status']);
        } else {
            $statuses = [];
        }
        
        if (!empty($statuses)) {
            // Escape each status and wrap with single quotes to prevent SQL injection
            $escapedStatuses = array_map(function($status) {
                return $this->escapeString(trim($status));
            }, $statuses);
            // Wrap each escaped status with single quotes
            $quotedStatuses = array_map(function($status) {
                return "'$status'";
            }, $escapedStatuses);
            // Join the quoted statuses into a single string
            $statusString = implode(',', $quotedStatuses);
        } else {
            $statusString = '';
        }

        if (!empty($statusString)) {
            $q .= " AND p.status IN ($statusString)";
        }

        $select = "SELECT p.* , e.name, e.nip, t.name as team_name, d.name as department_name, u.username ";
        $sql = " FROM permits p  
                JOIN employees e ON (p.id_employee = e.id)
                JOIN reference_types r ON (p.id_type = r.id)
                LEFT JOIN users u ON (p.id_user_decide = u.id)
                JOIN teams t ON (e.id_team = t.id)
                JOIN departments d ON (e.id_department = d.id)
                WHERE p.deleted_at is null 
                $q order by p.id desc ";

        // echo '<pre>'.$select.$sql.'</pre>'; die();

        $query = $this->query($select.$sql);
        $result['data'] = $query->getResult();

        $count = "SELECT count(*) as count ";
        $result['jumlah'] = $this->query($count.$sql)->getRow()->count;

        return $result;
    }

    function get_permit($id) {
        $sql = "SELECT p.* , e.name, e.nip, t.name as team_name, d.name as department_name, u.username, r.name as type_name
                FROM permits p  
                JOIN employees e ON (p.id_employee = e.id)
                JOIN reference_types r ON (p.id_type = r.id)
                LEFT JOIN users u ON (p.id_user_decide = u.id)
                JOIN teams t ON (e.id_team = t.id)
                JOIN departments d ON (e.id_department = d.id)
                WHERE p.deleted_at is null 
                    AND p.id = $id 
                LIMIT 1 ";

        return $this->query($sql)->getRow();
    }
    
    function update_permit($param) {
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
                $data['message'] = "Berhasil mengubah data Izin";
            } else {
                // Insert
                $res = $this->insert($param);
                if (!$res) {
                    throw new Exception($this->error()['message']);
                }
                $data['id'] = $this->insertID();
                $data['message'] = "Berhasil menambahkan data Izin";
            }

            $data['success'] = true;
        } catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    function delete_permit($id) {
        $data = array('deleted_at' => date('Y-m-d H:i:s'));
        
        $res = $this->where('id', $id)->update($id, $data);
        
        return $res;
    }
}
