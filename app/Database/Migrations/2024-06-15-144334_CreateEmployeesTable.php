<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmployeesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'              => 'BIGINT',
                'constraint'        => 20,
                'unsigned'          => true,
                'auto_increment'    => true,
                'null'              => false,
            ],
            'nip' => [
                'type'              => 'varchar',
                'constraint'        => '255',
                'null'              => true
            ],
            'name' => [
                'type'              => 'VARCHAR',
                'constraint'        => '255',
            ],
            'gender' => [
                'type'              => 'VARCHAR',
                'constraint'        => '255',
                'null'              => true
            ],
            'birth_date' => [
                'type'              => 'DATE',
                'null'              => true
            ],
            'age' => [
                'type'              => 'INT',
                'null'              => true
            ],
            'phone_number' => [
                'type'              => 'VARCHAR',
                'constraint'        => '100',
                'null'              => true
            ],
            'address' => [
                'type'              => 'TEXT',
                'null'              => true
            ],
            'education' => [
                'type'              => 'varchar',
                'constraint'        => '225',
                'null'              => true
            ],
            'id_team' => [
                'type'              => 'BIGINT',
                'constraint'        => 20,
                'unsigned'          => true
            ],
            'id_department' => [
                'type'              => 'BIGINT',
                'constraint'        => 20,
                'unsigned'          => true
            ],
            'id_status_employment' => [
                'type'              => 'BIGINT',
                'constraint'        => 20,
                'unsigned'          => true
            ],
            'join_date' => [
                'type'              => 'DATE',
                'null'              => true
            ],
            'leaving_date' => [
                'type'              => 'DATE',
                'null'              => true
            ],
            'photo' => [
                'type'              => 'VARCHAR',
                'constraint'        => '225',
                'null'              => true
            ],
            'active' => [
                'type'              => 'INT',
                'default'           => 1
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp', 
            'deleted_at' => [
                'type'              => 'datetime',
                'null'              => true
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_team', 'teams', 'id', 'cascade');
        $this->forge->addForeignKey('id_department', 'departments', 'id', 'cascade');
        $this->forge->addForeignKey('id_status_employment', 'status_employments', 'id', 'cascade');
        $this->forge->createTable('employees');
    }

    public function down()
    {
        $this->forge->dropForeignKey('teams', 'employees_id_team_foreign');
        $this->forge->dropForeignKey('departments', 'employees_id_department_foreign');
        $this->forge->dropForeignKey('status_employments', 'employees_id_status_employment_foreign');
        $this->forge->dropTable('employees');
    }
}
