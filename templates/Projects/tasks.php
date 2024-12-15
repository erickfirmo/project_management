<h1 class="text-center my-4">Tarefas do projeto <?= h($project->name) ?></h1>

<div class="container">
    <div>
        <div class="card text-bg-light mb-3 p-2" style="max-width: 40rem;display: block;margin:auto;">
        <div class="card-header mb-3">LISTA DE TAREFAS</div>
            <?php foreach ($tasks as $task): ?>
                <div class="card text-bg-light mb-2 task-body"
                    data-task="<?= $project->id ?>"
                    >
                    <div class="card-body">
                        <h5 class="card-title"><?= h($task->name) ?></h5>
                        <p class="card-text"><?= h($task->description) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>

                <div class="card text-bg-light mb-2">
                    <div class="card-body">
                        <?= $this->Form->create(null, ['url' => ['controller' => 'Tasks', 'action' => 'add'], 'class' => 'form-horizontal']) ?>
                            <div class="form-group d-flex">
                                <?= $this->Form->control('name', ['label' => false, 'placeholder' => 'Digite o Nome da Tarefa', 'class' => 'form-control']) ?>
                                <input type="hidden" name="project_id" value="<?= $project->id ?>">
                                <button type="submit" class="btn btn-dark mx-2">
                                    +
                                </button>
                            </div>

                        <?= $this->Form->end(); ?>
                    </div>
                </div>
        </div>
    </div>
</div>
