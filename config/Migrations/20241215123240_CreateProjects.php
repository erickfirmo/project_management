<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateProjects extends BaseMigration
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
        $table = $this->table('projects');
        $table
            ->addColumn('name', 'string', [
                'limit' => 255,
                'null' => false,
                'comment' => 'Nome do projeto',
            ])
            ->addColumn('description', 'text', [
                'null' => true,
                'comment' => 'Descrição do projeto',
            ])
            ->addColumn('start_date', 'date', [
                'null' => false,
                'comment' => 'Data de início do projeto',
            ])
            ->addColumn('end_date', 'date', [
                'null' => true,
                'comment' => 'Data de término do projeto',
            ])
            ->addColumn('status', 'enum', [
                'values' => ['ativo', 'inativo', 'concluído'],
                'default' => 'ativo',
                'null' => false,
                'comment' => 'Status do projeto',
            ])
            ->create();
    }
}
