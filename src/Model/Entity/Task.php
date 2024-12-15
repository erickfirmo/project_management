<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Task Entity
 *
 * @property int $id
 * @property int $project_id
 * @property string $name
 * @property string|null $description
 * @property \Cake\I18n\Date|null $delivery_date
 * @property string $priority
 * @property string $status
 *
 * @property \App\Model\Entity\Project $project
 */
class Task extends Entity
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
        'project_id' => true,
        'name' => true,
        'description' => true,
        'delivery_date' => true,
        'priority' => true,
        'status' => true,
        'project' => true,
    ];
}
