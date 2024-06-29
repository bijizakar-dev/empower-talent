<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaidLeaveQuotasTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'              => 'BIGINT',
                'constraint'        => 20,
                'unsigned'          => true,
                'auto_increment'    => true
            ],
            'id_employee' => [
                'type'              => 'BIGINT',
                'constraint'        => 20,
                'unsigned'          => true
            ],
            'id_type' => [
                'type'              => 'BIGINT',
                'constraint'        => 20,
                'unsigned'          => true
            ],
            'quota' => array(
                'type'              => 'BIGINT',
                'constraint'        => 20,
                'default'           => 0,
            ),
            'quota_used' => array(
                'type'              => 'BIGINT',
                'constraint'        => 20,
                'default'           => 0,
            ),
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp', 
            'deleted_at' => [
                'type'              => 'datetime',
                'null'              => true
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_employee', 'employees', 'id', 'cascade');
        $this->forge->addForeignKey('id_type', 'reference_types', 'id', 'cascade');
        $this->forge->createTable('paid_leave_quotas');
    }

    public function down()
    {
        $this->forge->dropForeignKey('employees', 'paid_leave_quotas_id_employee_foreign');
        $this->forge->dropForeignKey('reference_types', 'paid_leave_quotas_id_reference_types_foreign');
        $this->forge->dropTable('paid_leave_quotas');
    }
}
