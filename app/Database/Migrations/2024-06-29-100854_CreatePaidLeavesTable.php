<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaidLeavesTable extends Migration
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
            'start_date' => [
                'type'              => 'DATETIME',
            ],
            'end_date' => [
                'type'              => 'DATETIME',
            ],
            'duration' => [
                'type'              => 'VARCHAR',
                'constraint'        => 20,
                'null'              => true
            ],
            'reason' => [
                'type'              => 'TEXT',
                'null'              => true
            ],
            'reason_rejected' => [
                'type'              => 'TEXT',
                'null'              => true
            ],
            'note' => [
                'type'              => 'TEXT',
                'null'              => true
            ],
            'file' => [
                'type'              => 'VARCHAR',
                'constraint'        => 20,
                'null'              => true
            ],
            'status' => [
                'type'              => 'ENUM',
                'constraint'        => ['Submitted', 'Pending', 'Approved', 'Rejected', 'Cancelled'],
                'default'           => 'Submitted',
            ],
            'id_user_decide' => [
                'type'              => 'BIGINT',
                'constraint'        => 20,
                'unsigned'          => true,
                'null'              => true
            ],
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
        $this->forge->addForeignKey('id_user_decide', 'users', 'id', 'cascade');
        $this->forge->createTable('paid_leaves');
    }

    public function down()
    {
        $this->forge->dropForeignKey('employees', 'paid_leaves_id_employee_foreign');
        $this->forge->dropForeignKey('reference_types', 'paid_leaves_id_reference_types_foreign');
        $this->forge->dropForeignKey('users', 'paid_leaves_id_users_foreign');
        $this->forge->dropTable('paid_leaves');
    }
}
