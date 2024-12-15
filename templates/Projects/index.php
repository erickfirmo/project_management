<h1 class="text-center my-4">Lista de Projetos</h1>

<div class="container">
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Status</th>
                <th>Data de Início</th>
                <th>Data de Término</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($projects as $project): ?>
                <tr>
                    <td><?= h($project->id) ?></td>
                    <td><?= h($project->name) ?></td>
                    <td>
                        <span class="badge 
                            <?= $project->status === 'ativo' ? 'bg-success' : 
                               ($project->status === 'inativo' ? 'bg-secondary' : 'bg-info') ?>">
                            <?= h($project->status) ?>
                        </span>
                    </td>
                    <td><?= h($project->data_inicio) ?></td>
                    <td><?= h($project->data_fim) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        <ul class="pagination">
            <?= $this->Paginator->prev('<< Anterior', ['class' => 'page-item']) ?>
            <?= $this->Paginator->numbers(['class' => 'page-item']) ?>
            <?= $this->Paginator->next('Próximo >>', ['class' => 'page-item']) ?>
        </ul>
    </div>
</div>
