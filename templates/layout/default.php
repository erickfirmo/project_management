<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')); ?>

    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <main class="main">

        <?php
            $session = $this->request->getSession();

            $successMessage = $session->read('successMessage');
            $warningMessage = $session->read('warningMessage');
            $validationErrors = $session->read('ValidationErrors');
            $formData = $session->read('FormData');

            $session->delete('successMessage');
            $session->delete('warningMessage');
            $session->delete('ValidationErrors');
            $session->delete('FormData');
        ?>
        

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
                        <a href="<?= $this->Url->build(['controller' => 'Projects', 'action' => 'tasks', $project->id], ['fullBase' => true]) ?>" class="text-dark text-decoration-none"><?= h($project->name) ?></a>
                        <span class="text-dark">(<?= h($project->progress) ?>%)</span>
                        <span class="badge 
                            <?= $project->status === 'ativo' ? 'bg-success' : 
                            ($project->status === 'inativo' ? 'bg-secondary' : 'bg-info') ?>">
                            <?= h($project->status) ?>
                        </span>
                        <span class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></span>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal"
                            data-bs-editroute="<?= $this->Url->build(['controller' => 'Projects', 'action' => 'edit', $project->id]) ?>"
                            data-bs-getroute="<?= $this->Url->build(['controller' => 'Projects', 'action' => 'get-project', $project->id]) ?>">Editar</a></li>
                            <li><a class="dropdown-item" href="#" onclick="if (confirm('Você tem certeza que deseja deletar?')) { deleteRegister(this, '<?= $this->Url->build(['controller' => 'Projects', 'action' => 'delete', $project->id], ['fullBase' => true]) ?>') }">Deletar</a></li>
                        </ul>
                    </li>
                    <?php endforeach; ?>

                    <li class="nav-item dropdown">

                        <?= $this->Form->create(null, ['url' => ['action' => 'add'], 'class' => 'form-horizontal']) ?>

                        <div class="form-group d-flex">
                            <?= $this->Form->control('name', ['value' => '', 'label' => false, 'placeholder' => 'Digite o Nome do Projeto', 'class' => 'form-control']) ?>
                            <?php if (!empty($validationErrors['errors']) && isset($validationErrors['errors']['name']) && $validationErrors['entity'] == 'Projects' && $validationErrors['action'] == 'add'): ?>
                                <span class="text-danger"><?= reset($validationErrors['errors']['name']) ?></span>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-dark mx-2">
                                Adicionar
                            </button>
                        </div>

                        <?= $this->Form->end(); ?>

                        


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

                        <?= $this->Form->create(null, ['url' => '', 'id' => 'modalForm']) ?>

                        <div class="mb-3">
                            <?= $this->Form->control('name', [
                                'label' => 'Nome do Projeto', 
                                'class' => 'form-control',
                                'value' => !empty($formData['data']['name']) && $formData['entity'] == 'Projects' ? $formData['data']['name'] : ''
                            ]) ?>
                            <?php if (!empty($validationErrors['errors']) && isset($validationErrors['errors']['name']) && $validationErrors['entity'] == 'Projects'): ?>
                                <span class="text-danger"><?= reset($validationErrors['errors']['name']) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <?= $this->Form->control('description', [
                                'label' => 'Descrição', 
                                'type' => 'textarea', 
                                'class' => 'form-control',
                                'value' => !empty($formData['data']['description'])&& $formData['entity'] == 'Projects' ? $formData['data']['description'] : ''
                            ]) ?>
                            <?php if (!empty($validationErrors['errors']) && isset($validationErrors['errors']['description']) && $validationErrors['entity'] == 'Projects'): ?>
                                <span class="text-danger"><?= reset($validationErrors['errors']['description']) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <?= $this->Form->control('start_date', [
                                'label' => 'Data de Início', 
                                'type' => 'date', 
                                'class' => 'form-control',
                                'value' => !empty($formData['data']['start_date']) && $formData['entity'] == 'Projects' ? $formData['data']['start_date'] : ''
                            ]) ?>
                            <?php if (!empty($validationErrors['errors']) && isset($validationErrors['errors']['start_date']) && $validationErrors['entity'] == 'Projects'): ?>
                                <span class="text-danger"><?= reset($validationErrors['errors']['start_date']) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <?= $this->Form->control('end_date', [
                                'label' => 'Data de Término', 
                                'type' => 'date', 
                                'class' => 'form-control',
                                'value' => !empty($formData['data']['end_date']) && $formData['entity'] == 'Projects' ? $formData['data']['end_date'] : ''
                            ]) ?>
                            <?php if (!empty($validationErrors['errors']) && isset($validationErrors['errors']['end_date']) && $validationErrors['entity'] == 'Projects'): ?>
                                <span class="text-danger"><?= reset($validationErrors['errors']['end_date']) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <?= $this->Form->control('status', [
                                'label' => 'Status',
                                'options' => ['ativo' => 'Ativo', 'inativo' => 'Inativo', 'concluído' => 'Concluído'],
                                'class' => 'form-control',
                                'value' => !empty($formData['data']['status']) && $formData['entity'] == 'Projects' ? $formData['data']['status'] : ''
                            ]) ?>
                            <?php if (!empty($validationErrors['errors']) && isset($validationErrors['errors']['status']) && $validationErrors['entity'] == 'Projects'): ?>
                                <span class="text-danger"><?= reset($validationErrors['errors']['status']) ?></span>
                            <?php endif; ?>
                        </div>

                        <?= $this->Form->end(); ?>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-dark" onclick="document.getElementById('modalForm').submit()">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal -->

        <div class="container">
            <button class="btn btn-dark" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">Todos os projetos</button>
            
            <?php if ($error = $this->Flash->render()): ?>
                <div class="alert alert-danger d-flex align-items-center mt-4" role="alert">
                    <div>
                        <?= $error ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($warningMessage): ?>
                <div class="alert alert-warning d-flex align-items-center mt-4" role="alert">
                    <div>
                        <?= $warningMessage ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($successMessage): ?>
                <div class="alert alert-success d-flex align-items-center mt-4" role="alert">
                    <div>
                        <?= $successMessage ?>
                    </div>
                </div>
            <?php endif; ?>


            <?= $this->fetch('content') ?>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- MODAL SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.0/dist/cdn.min.js" defer></script>

    <script>
        var exampleModal = document.getElementById('exampleModal')
            exampleModal.addEventListener('show.bs.modal', function (event) {

                var button = event.relatedTarget
                var editRoute = button.getAttribute('data-bs-editroute');
                var getRoute = button.getAttribute('data-bs-getroute');

                var modalForm = document.getElementById('modalForm');

                modalForm.setAttribute('action', editRoute);

                var modalTitle = exampleModal.querySelector('.modal-title')
                var modalInputName = exampleModal.querySelector('#modalForm #name')
                var modalInputDesc = exampleModal.querySelector('#modalForm #description')
                var modalInputStartDate = exampleModal.querySelector('#modalForm #start-date')
                var modalInputEndDate = exampleModal.querySelector('#modalForm #end-date')
                var modalInputStatus = exampleModal.querySelector('#modalForm #status')

                fetch(getRoute, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro ao buscar dados do projeto');
                    }
                    return response.json();
                })
                .then(data => {
                    modalInputName.value = data.name
                    modalInputDesc.value = data.description
                    modalInputStartDate.value = data.start_date
                    modalInputEndDate.value = data.end_date
                    modalInputStatus.value = data.status
                    modalTitle.textContent = 'Editando projeto: ' + data.name
                })
                .catch(error => {
                    console.error('Erro:', error);
                });


            })

        const csrfToken = document.querySelector('meta[name="csrfToken"]').getAttribute('content');
        const deleteRegister = (el, deleteRoute) => {
            console.log(el);

            fetch(deleteRoute, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-Token': csrfToken
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao deletar o projeto');
                }
                return response.json();
            })
            .then(data => {
                el.parentElement.parentElement.parentElement.remove()
                alert(data.message)
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        }


        /*
        const offcanvasRight = document.getElementById('offcanvasRight')

        if(offcanvasRight) {
            offcanvasRight.addEventListener('show.bs.offcanvas', event => {

            })
        }
        */
    </script> 

    <!-- Abre modal quando form for inválido -->
     <?php if (!empty($validationErrors['errors']) && $validationErrors['entity'] == 'Projects' && $validationErrors['action'] == 'edit'): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {
            keyboard: false
            });
            myModal.show();
        });
    </script>
    <?php endif; ?>


</body>
</html>