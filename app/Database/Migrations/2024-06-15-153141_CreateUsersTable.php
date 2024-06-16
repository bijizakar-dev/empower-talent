<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
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
            'username' => [
                'type'              => 'varchar',
                'constraint'        => '128'
            ],
            'email' => [
                'type'              => 'varchar',
                'constraint'        => '255'
            ],
            'password' => [
                'type'              => 'varchar',
                'constraint'        => '255'
            ],
            'id_employee' => [
                'type'              => 'BIGINT',
                'constraint'        => 20,
                'unsigned'          => true
            ],
            'id_role' => [
                'type'              => 'BIGINT',
                'constraint'        => 20,
                'unsigned'          => true
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
        $this->forge->addForeignKey('id_employee', 'employees', 'id', 'cascade');
        $this->forge->addForeignKey('id_role', 'roles', 'id', 'cascade');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropForeignKey('employees', 'users_id_employee_foreign');
        $this->forge->dropForeignKey('roles', 'users_id_role_foreign');
        $this->forge->dropTable('users');
    }
}
