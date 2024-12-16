<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Project Entity
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Cake\I18n\Date $start_date
 * @property \Cake\I18n\Date|null $end_date
 * @property string $status
 *
 * @property \App\Model\Entity\Task[] $tasks
 */
class Project extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'name' => true,
        'description' => true,
        'start_date' => true,
        'end_date' => true,
        'status' => true,
        'tasks' => true,
    ];

    protected function _getProgress()
    {
        $tasksTable = TableRegistry::getTableLocator()->get('Tasks');

        $totalTasks = $tasksTable->find()
            ->where([
                'project_id' => $this->id,
            ])
            ->count();

        $completedTasks = $tasksTable->find()
            ->where([
                'project_id' => $this->id,
                'status' => 'concluída',
            ])
            ->count();

        return $totalTasks > 0 ? floor(($completedTasks / $totalTasks) * 100) : 0;
    }

}
