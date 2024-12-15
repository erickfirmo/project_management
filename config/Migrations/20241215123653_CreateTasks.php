<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateTasks extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('tasks');
        $table
            ->addColumn('project_id', 'integer', [
                'null' => false,
                'comment' => 'Chave estrangeira para o projeto',
            ])
            ->addColumn('name', 'string', [
                'limit' => 255,
                'null' => false,
                'comment' => 'Nome da tarefa',
            ])
            ->addColumn('description', 'text', [
                'null' => true,
                'comment' => 'Descrição da tarefa',
            ])
            ->addColumn('delivery_date', 'date', [
                'null' => true,
                'comment' => 'Data de entrega',
            ])
            ->addColumn('priority', 'enum', [
                'values' => ['alta', 'média', 'baixa'],
                'default' => 'média',
                'null' => false,
                'comment' => 'Prioridade da tarefa',
            ])
            ->addColumn('status', 'enum', [
                'values' => ['pendente', 'em andamento', 'concluída'],
                'default' => 'pendente',
                'null' => false,
                'comment' => 'Status da tarefa',
            ])
            ->addForeignKey('project_id', 'projects', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION',
                'constraint' => 'fk_tasks_projects',
            ])
            ->create();
    }
}
