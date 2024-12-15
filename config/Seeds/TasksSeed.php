<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * Tasks seed.
 */
class TasksSeed extends BaseSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/migrations/4/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'project_id' => 1,
                'name' => 'Tarefa 1',
                'description' => 'Descrição da Tarefa 1',
                'delivery_date' => '2024-05-01',
                'priority' => 'alta',
                'status' => 'pendente',
            ],
            [
                'id' => 2,
                'project_id' => 1,
                'name' => 'Tarefa 2',
                'description' => 'Descrição da Tarefa 2',
                'delivery_date' => '2024-06-15',
                'priority' => 'média',
                'status' => 'em andamento',
            ],
            [
                'id' => 3,
                'project_id' => 2,
                'name' => 'Tarefa 3',
                'description' => 'Descrição da Tarefa 3',
                'delivery_date' => '2024-07-20',
                'priority' => 'baixa',
                'status' => 'concluída',
            ],
            [
                'id' => 4,
                'project_id' => 3,
                'name' => 'Tarefa 4',
                'description' => 'Descrição da Tarefa 4',
                'delivery_date' => '2024-08-10',
                'priority' => 'alta',
                'status' => 'pendente',
            ],
        ];

        $table = $this->table('tasks');
        $table->insert($data)->save();
    }
}
