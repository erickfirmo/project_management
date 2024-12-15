<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * Projects seed.
 */
class ProjectsSeed extends BaseSeed
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
                'name' => 'Projeto 1',
                'description' => 'Descrição do Projeto 1',
                'start_date' => '2024-01-01',
                'end_date' => '2024-06-30',
                'status' => 'ativo',
            ],
            [
                'id' => 2,
                'name' => 'Projeto 2',
                'description' => 'Descrição do Projeto 2',
                'start_date' => '2024-02-01',
                'end_date' => '2024-07-30',
                'status' => 'inativo',
            ],
            [
                'id' => 3,
                'name' => 'Projeto 3',
                'description' => 'Descrição do Projeto 3',
                'start_date' => '2024-03-01',
                'end_date' => '2024-09-30',
                'status' => 'concluído',
            ],
        ];

        $table = $this->table('projects');
        $table->insert($data)->save();
    }
}
