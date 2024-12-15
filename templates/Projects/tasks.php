<h1 class="text-center my-4">Tarefas do projeto <?= h($project->name) ?></h1>

<div class="container">
    <div>
        <div class="card text-bg-light mb-3 p-2" style="max-width: 40rem;display: block;margin:auto;">
        <div class="card-header mb-3">LISTA DE TAREFAS</div>
            <?php foreach ($tasks as $task): ?>
                <div class="card text-bg-light mb-2 task-body"
                    data-bs-editroute="<?= $this->Url->build(['controller' => 'Tasks', 'action' => 'edit', $task->id]) ?>"
                    data-bs-deleteroute="<?= $this->Url->build(['controller' => 'Tasks', 'action' => 'delete', $task->id]) ?>"
                    data-bs-getroute="<?= $this->Url->build(['controller' => 'Tasks', 'action' => 'get-task', $task->id]) ?>"
                    type="button"
                    data-bs-toggle="offcanvas" 
                    data-bs-target="#offcanvasRight" 
                    aria-controls="offcanvasRight"
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

<!-- Sidebar right - Editar task -->

<!--button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Toggle right offcanvas</button>-->

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">Detalhes da Tarefa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <?= $this->Form->create(null, ['url' => '', 'id' => 'updateTaskForm']) ?>
            <div class="mb-3">
                <?= $this->Form->control('name', ['label' => 'Nome da Tarefa', 'class' => 'form-control']) ?>
            </div>
            <div class="mb-3">
                <?= $this->Form->control('description', ['label' => 'Descrição', 'type' => 'textarea', 'class' => 'form-control']) ?>
            </div>
            <div class="mb-3">
                <?= $this->Form->control('delivery_date', ['label' => 'Data de Entrega', 'type' => 'date', 'class' => 'form-control']) ?>
            </div>
            <div class="mb-3">
                <?= $this->Form->control('priority', [
                    'label' => 'Prioridade',
                    'options' => ['alta' => 'Alta', 'média' => 'Média', 'baixa' => 'Baixa'],
                    'class' => 'form-control'
                ]) ?>
            </div>
            <div class="mb-3">
                <?= $this->Form->control('status', [
                    'label' => 'Status',
                    'options' => ['pendente' => 'Ativo', 'em andamento' => 'Em andamento', 'concluída' => 'Concluída'],
                    'class' => 'form-control'
                ]) ?>
            </div>
            <button type="submit" class="btn btn-dark">Atualizar</button>
        <?= $this->Form->end(); ?>
        <hr>
        <?= $this->Form->create(null, ['url' => '', 'id' => 'deleteTaskForm']) ?>
        
        <?= $this->Form->end(); ?>

        <button type="button" class="btn btn-danger" onclick="if (confirm('Você tem certeza que deseja deletar?')) { document.getElementById('deleteTaskForm').submit() }">Deletar</button>
    </div>
</div>
<!-- Fim Sidebar right - Editar task -->
