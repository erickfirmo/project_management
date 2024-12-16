<h1 class="text-center my-4">Tarefas do projeto <?= h($project->name) ?></h1>



<div class="container">
    <div class="row mb-4">
        <form action="" method="GET" class="mx-auto row w-100">
            <div class="form-group col-md-3 mb-4">
                <label for="">Nome</label>
                <input class="form-control mx-1" type="text" name="name" id="search-name" placeholder="Nome da Tarefa">
            </div>

            <div class="form-group form-group col-md-3 mb-4">
                <label for="">Status</label>
                <select class="form-control mx-1" name="status">
                    <option value="">-</option>
                    <option value="pendente">Pendende</option>
                    <option value="em andamento">Em Andamento</option>
                    <option value="concluída">Concluída</option>
                </select>
            </div>

            <div class="form-group col-md-3 mb-4">
                <label for="">Data de Entrega de:</label>
                <input class="form-control mx-1" type="date" name="delivery_start_date" id="delivery-start-date">
            </div>
            <div class="form-group col-md-3 mb-4">
                <label for="">Data de Entrega até:</label>
                <input class="form-control mx-1" type="date" name="delivery_end_date" id="delivery-end-date">
            </div>
            <div>
                <button type="submit" class="mx-1 btn btn-dark">Buscar</button>
                <a href="">
                    <button type="button" class="mx-1 btn btn-light">Limpar</button>
                </a>
            </div>

                    
                    
        </form>
    </div>
    <div class="row">
        <div class="col-12 mb-4 mx-1">
            <div class="card text-bg-light mb-3 p-2 w-75" style="display: block;margin:auto;">
                <div class="card-header mb-3">LISTA DE TAREFAS</div>

                

                <?php foreach ($tasks as $task): ?>
                    <div class="card text-bg-light mb-2 task-body" style="height: 140px;"
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
                    'options' => ['pendente' => 'Pendente', 'em andamento' => 'Em andamento', 'concluída' => 'Concluída'],
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
