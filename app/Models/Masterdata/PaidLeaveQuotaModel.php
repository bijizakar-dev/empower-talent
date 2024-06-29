<?php

namespace App\Models\Masterdata;

use CodeIgniter\Model;

class PaidLeaveQuotaModel extends Model
{
    protected $table            = 'paid_leave_quotas';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['name', 'id_employee', 'id_type', 'quota', 'quota_used', 'deleted_at'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
}
