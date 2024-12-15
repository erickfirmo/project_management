<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <main class="main">
        

        <!-- Sidebar Left -->
        <div class="offcanvas offcanvas-start show" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasScrollingLabel">GESTÃO DE PROJETOS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">

                <h6 class="mb-4">Meus Projetos</h6>
                <ul class="navbar-nav">
                    <?php foreach ($projects as $project): ?>
                    <li class="nav-item dropdown mb-3">
                        <a class="dropdown-toggle text-dark text-decoration-none"  data-bs-toggle="dropdown" aria-expanded="false">
                            <?= h($project->name) ?>
                            <span class="badge 
                                <?= $project->status === 'ativo' ? 'bg-success' : 
                                ($project->status === 'inativo' ? 'bg-secondary' : 'bg-info') ?>">
                                <?= h($project->status) ?>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-route="<?= $this->Url->build(['controller' => 'Projects', 'action' => 'edit', $project->id]) ?>">Editar</a></li>
                            <li><a class="dropdown-item" href="#">Deletar</a></li>
                        </ul>
                    </li>
                    <?php endforeach; ?>

                    <li class="nav-item dropdown">

                        <?= $this->Form->create(null, ['url' => ['action' => 'add'], 'class' => 'form-horizontal']) ?>

                        <div class="form-group d-flex">
                            <?= $this->Form->control('name', ['label' => false, 'placeholder' => 'Digite o Nome do Projeto', 'class' => 'form-control']) ?>
                            <button type="submit" class="btn btn-primary mx-2">
                                Adicionar
                            </button>
                        </div>

                    </li>
                </ul>
            </div>
        </div>
        <!-- End Siderbar Left -->

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editando projeto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <?= $this->Form->create($project, ['url' => '']) ?>

                            <div class="mb-3">
                                <?= $this->Form->control('name', ['label' => 'Nome do Projeto', 'class' => 'form-control']) ?>
                            </div>
                            <div class="mb-3">
                                <?= $this->Form->control('description', ['label' => 'Descrição', 'type' => 'textarea', 'class' => 'form-control']) ?>
                            </div>
                            <div class="mb-3">
                                <?= $this->Form->control('data_inicio', ['label' => 'Data de Início', 'type' => 'date', 'class' => 'form-control']) ?>
                            </div>
                            <div class="mb-3">
                                <?= $this->Form->control('data_fim', ['label' => 'Data de Término', 'type' => 'date', 'class' => 'form-control']) ?>
                            </div>
                            <div class="mb-3">
                                <?= $this->Form->control('status', [
                                    'label' => 'Status',
                                    'options' => ['ativo' => 'Ativo', 'inativo' => 'Inativo', 'concluído' => 'Concluído'],
                                    'class' => 'form-control'
                                ]) ?>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal -->

        <div class="container">
            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">Todos os projetos</button>
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- MODAL SCRIPTS -->
    <script>
        var exampleModal = document.getElementById('exampleModal')
        exampleModal.addEventListener('show.bs.modal', function (event) {
        // Button that triggered the modal
        var button = event.relatedTarget
        // Extract info from data-bs-* attributes
        var editRoute = button.getAttribute('data-bs-route');

        var modalForm = document.getElementById('#modalForm');

        modalForm.setAttribute('action', editRoute);


        
        var modalTitle = exampleModal.querySelector('.modal-title')
        var modalInputName = exampleModal.querySelector('.modal-body input#name')

        modalTitle.textContent = 'Editando projeto: ' + editRoute
        modalInputName.value = editRoute
        })
    </script> 
</body>
</html>