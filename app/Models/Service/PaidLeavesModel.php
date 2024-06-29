<?php

namespace App\Models\Service;

use App\Models\Masterdata\PaidLeaveQuotaModel;
use CodeIgniter\Model;
use Exception;

class PaidLeavesModel extends Model
{
    protected $table            = 'paid_leaves';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'id_employee', 'id_type', 'start_date', 'end_date', 'duration',
        'reason', 'reason_rejected', 'note', 'file', 'status', 'id_user_decide', 'deleted_at'
    ];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;


    function get_list_paid_leave($limit, $start, $search) {
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
            $q.=" AND (date(pl.start_date) between '".$search['start_date']."' AND '".$search['end_date']."')";
        }

        if ($search['created_start'] != '' and $search['created_end'] != '') {
            $q.=" AND (date(pl.created_at) between '".$search['created_start']."' AND '".$search['created_end']."')";
        }

        if (!empty($search['status'])) {
            $statuses = explode(',', $search['status']);
        } else {
            $statuses = [];
        }
        
        if (!empty($statuses)) {
            $escapedStatuses = array_map(function($status) {
                return $this->escapeString(trim($status));
            }, $statuses);

            $quotedStatuses = array_map(function($status) {
                return "'$status'";
            }, $escapedStatuses);

            $statusString = implode(',', $quotedStatuses);
        } else {
            $statusString = '';
        }

        if (!empty($statusString)) {
            $q .= " AND pl.status IN ($statusString)";
        }

        $select = "SELECT pl.* , e.name, e.nip, t.name as team_name, d.name as department_name, u.username ";
        $sql = " FROM paid_leaves pl  
                JOIN employees e ON (pl.id_employee = e.id)
                JOIN reference_types r ON (pl.id_type = r.id)
                LEFT JOIN users u ON (pl.id_user_decide = u.id)
                JOIN teams t ON (e.id_team = t.id)
                JOIN departments d ON (e.id_department = d.id)
                WHERE pl.deleted_at is null 
                $q order by pl.id desc ";

        // echo '<pre>'.$select.$sql.'</pre>'; die();

        $query = $this->query($select.$sql);
        $result['data'] = $query->getResult();

        $count = "SELECT count(*) as count ";
        $result['jumlah'] = $this->query($count.$sql)->getRow()->count;

        return $result;
    }

    function get_paid_leave($id) {
        $sql = "SELECT pl.* , e.name, e.nip, t.name as team_name, d.name as department_name, u.username, r.name as type_name
                FROM paid_leaves pl  
                JOIN employees e ON (pl.id_employee = e.id)
                JOIN reference_types r ON (pl.id_type = r.id)
                LEFT JOIN users u ON (pl.id_user_decide = u.id)
                JOIN teams t ON (e.id_team = t.id)
                JOIN departments d ON (e.id_department = d.id)
                WHERE pl.deleted_at is null 
                    AND pl.id = $id 
                LIMIT 1 ";

        return $this->query($sql)->getRow();
    }

    function update_paid_leave($param) {
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
                $data['message'] = "Berhasil mengubah data Cuti";
            } else {
                // Insert
                $res = $this->insert($param);
                if (!$res) {
                    throw new Exception($this->error()['message']);
                }
                $data['id'] = $this->insertID();
                $data['message'] = "Berhasil menambahkan data Cuti";
            }

            $data['success'] = true;
        } catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    function delete_paid_leave($id) {
        $data = array('deleted_at' => date('Y-m-d H:i:s'));
        
        $res = $this->where('id', $id)->update($id, $data);
        
        return $res;
    }
}
