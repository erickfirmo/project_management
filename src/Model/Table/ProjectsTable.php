<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Projects Model
 *
 * @property \App\Model\Table\TasksTable&\Cake\ORM\Association\HasMany $Tasks
 *
 * @method \App\Model\Entity\Project newEmptyEntity()
 * @method \App\Model\Entity\Project newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Project> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Project get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Project findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Project patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Project> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Project|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Project saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Project>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Project>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Project>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Project> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Project>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Project>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Project>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Project> deleteManyOrFail(iterable $entities, array $options = [])
 */
class ProjectsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('projects');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Tasks', [
            'foreignKey' => 'project_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name', 'O nome deve ser uma string.')
            ->maxLength('name', 255, 'O nome não pode exceder 255 caracteres.')
            ->requirePresence('name', 'create', 'O campo nome é obrigatório na criação.')
            ->notEmptyString('name', 'O nome não pode estar vazio.');

        $validator
            ->scalar('description', 'A descrição deve ser uma string.')
            ->allowEmptyString('description', 'A descrição é opcional.');

        $validator
            ->date('start_date', ['ymd'], 'A data de início deve ser uma data válida.')
            ->requirePresence('start_date', 'update', 'A data de início é obrigatória para atualização.')
            ->allowEmptyDate('start_date', 'A data de início é opcional.');
            
        $validator
            ->scalar('status')
            ->requirePresence('status', 'update')
            ->notEmptyString('status');
            
        $validator
            ->date('end_date', ['ymd'], 'A data de término deve ser uma data válida.')
            ->allowEmptyDate('end_date', 'A data de término é opcional.')
            ->add('end_date', 'custom', [
                'rule' => function ($value, $context) {
                    $startDate = $context['data']['start_date'] ?? null;
                    if ($startDate && $value) {
                        return strtotime($value) >= strtotime($startDate);
                    }
                    return true;
                },
                'message' => 'A data de término deve ser igual ou posterior à data de início.'
            ]);

        return $validator;
    }
}
