<x-app-layout>
    <h1 class="text-center my-4">Lista de Tarefas</h1>

    <?php
        $session = $this->request->getSession();

        $validationErrors = $session->read('ValidationErrors');
        $formData = $session->read('FormData');

        $session->delete('ValidationErrors');
        $session->delete('FormData');
    ?>
        

    <div class="container" x-data="tasksData(<?= $project->id ?>)">

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="name">Name</label>
                <input x-ref="inputName" x-on:input.change="updateFilter('name', $event.target.value)" type="text" name="name" placeholder="Nome da tarefa" data-filter class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label for="status">Status</label>
                <select x-ref="inputStatus" x-on:change="updateFilter('status', $event.target.value)" name="status" data-filter class="form-control">
                    <option value="">Selecione o status</option>
                    <option value="pendente">Pendente</option>
                    <option value="em-andamento">Em Andamento</option>
                    <option value="concluída">Concluída</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="priority">Prioridade</label>
                <select x-ref="inputPriority" x-on:change="updateFilter('priority', $event.target.value)" name="priority" data-filter class="form-control">
                    <option value="">Selecione a prioridade</option>
                    <option value="alta">Alta</option>
                    <option value="média">Média</option>
                    <option value="baixa">Baixa</option>
                </select>
            </div>
            <div class="form-group col-md-3 mb-4">
                <label for="delivery-start-date">Data de Entrega de:</label>
                <input 
                    class="form-control mx-1" 
                    type="date" 
                    name="delivery_start_date" 
                    id="delivery-start-date"
                    x-ref="inputDeliveryStartDate" x-on:change="updateFilter('delivery_start_date', $event.target.value)" data-filter class="form-control"
                >
            </div>
            <div class="form-group col-md-3 mb-4">
                <label for="delivery-end-date">Data de Entrega até:</label>
                <input 
                    class="form-control mx-1" 
                    type="date" 
                    name="delivery_end_date" 
                    id="delivery-end-date"
                    x-ref="inputDeliveryEndDate" x-on:change="updateFilter('delivery_end_date', $event.target.value)" data-filter class="form-control"

                >
            </div>
            <div class="col-md-3 mb-3">
                <button x-on:click="clearFilters();" class="btn btn-light w-100 my-4">Limpar</button>
            </div>
        </div>

        <hr>

        <div class="row mb-3">
            <?= $this->Form->create(null, ['url' => ['controller' => 'Tasks', 'action' => 'add'], 'class' => 'form-horizontal']) ?>
                <div class="form-group d-flex">
                    <?= $this->Form->control('name', ['label' => false, 'placeholder' => 'Nome da nova tarefa', 'class' => 'form-control']) ?>
                    <input type="hidden" name="project_id" value="<?= $project->id ?>">
                    <button type="submit" class="btn btn-dark mx-2">
                        Adicionar
                    </button>
                </div>
                <?php if (!empty($validationErrors['errors']) && isset($validationErrors['errors']['name']) && $validationErrors['entity'] == 'Tasks' && $validationErrors['action'] == 'add'): ?>
                        <span class="text-danger"><?= reset($validationErrors['errors']['name']) ?></span>
                <?php endif; ?>
            <?= $this->Form->end(); ?>
        </div>

        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID 
                        <span
                        x-on:click="updateOrder('order_id')" 
                        x-text="orders['order_id'] == 'DESC' || orders['order_id'] == '' ? '&#9660;' : '&#9650;'"
                        :class="orders['order_id'] == '' ? 'opacity-25' : ''"
                        >
                        </span>
                    </th>
                    <th>Nome 
                        <span
                        x-on:click="updateOrder('order_name')" 
                        x-text="orders['order_name'] == 'DESC' || orders['order_name'] == '' ? '&#9660;' : '&#9650;'"
                        :class="orders['order_name'] == '' ? 'opacity-25' : ''"
                        >
                        </span>
                    </th>
                    <th>Status 
                        <span
                        x-on:click="updateOrder('order_status')" 
                        x-text="orders['order_status'] == 'DESC' || orders['order_status'] == '' ? '&#9660;' : '&#9650;'"
                        :class="orders['order_status'] == '' ? 'opacity-25' : ''"
                        >
                        </span>
                    </th>
                    <th>Prioridade 
                        <span
                        x-on:click="updateOrder('order_priority')" 
                        x-text="orders['order_priority'] == 'DESC' || orders['order_priority'] == '' ? '&#9660;' : '&#9650;'"
                        :class="orders['order_priority'] == '' ? 'opacity-25' : ''"
                        >
                        </span>
                    </th>
                    <th>Data de Entrega 
                        <span
                        x-on:click="updateOrder('order_delivery_date')" 
                        x-text="orders['order_delivery_date'] == 'DESC' || orders['order_delivery_date'] == '' ? '&#9660;' : '&#9650;'"
                        :class="orders['order_delivery_date'] == '' ? 'opacity-25' : ''"
                        >
                        </span>
                    </th>
                    <th>Progresso 
                        <span
                        x-on:click="updateOrder('order_progress')" 
                        x-text="orders['order_progress'] == 'DESC' || orders['order_progress'] == '' ? '&#9660;' : '&#9650;'"
                        :class="orders['order_progress'] == '' ? 'opacity-25' : ''"
                        >
                        </span>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <template x-for="task in tasks" :key="task.id">
                    <tr>
                        <td><span x-text="task.id"></span></td>
                        <td><span x-text="task.name"></span></td>
                        <td>
                            <span :class="{
                                'bg-warning badge': task.status === 'pendente',
                                'bg-info badge': task.status === 'em-andamento',
                                'bg-success badge': task.status === 'concluída'
                            }">
                                <span x-text="task.status"></span>
                            </span>
                        </td>
                        <td><span x-text="task.priority"></span></td>
                        <td><span x-text="task.delivery_date"></span></td>
                        <td><span x-text="task.progress"></span>%</td>
                        <td>
                            <button
                                    type="button"
                                    data-bs-toggle="offcanvas" 
                                    data-bs-target="#offcanvasRight" 
                                    aria-controls="offcanvasRight"
                                    x-on:click="setSiderbarRight(task.id)"
                                    class="btn btn-primary"
                                    >
                                Editar
                            </button>
                        </td>
                    </tr>
                </template>
                <template x-if="tasks.length == 0">
                    <div>Nenhum resultado</div>
                </template>
            </tbody>
        </table>

        <template x-if="tasks.length > 0">
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item">
                    <span x-on:click="paginate(previous)" class="page-link" :class="!previous ? 'disabled' : ''">
                        Anterior
                    </span>
                    </li>

                    <template x-for="link in links" :key="link">
                    <li class="page-item" :class="{ active: link === page }">
                        <span x-on:click="paginate(link)" class="page-link">
                        <span x-text="link"></span>
                        </span>
                    </li>
                    </template>

                    <li class="page-item">
                    <span x-on:click="paginate(next)" class="page-link" :class="!next ? 'disabled' : ''">
                        Próximo
                    </span>
                    </li>
                </ul>
            </nav>
        </template>
    </div>

    <script>
        const tasksData = (projectId) => {
            return {
                projectId: projectId,
                tasks: [],
                links: [],
                previous: null,
                next: null,
                page: null,
                filters: {},
                orders: {
                    order_id: 'DESC',
                    order_name: '',
                    order_status: '',
                    order_delivery_start_date: '',
                    order_delivery_end_date: '',
                    order_progress: '',
                    order_priority: '',
                },
                ordersReset: {
                    order_id: '',
                    order_name: '',
                    order_status: '',
                    order_delivery_start_date: '',
                    order_delivery_end_date: '',
                    order_progress: '',
                    order_priority: '',
                },
                fetchTasks(page = 1) {

                    const queryParams = new URLSearchParams({ page, ...this.filters, ...this.orders }).toString();

                    this.page = page;
                    let projectId = this.projectId;
                    fetch(`/tasks/list/${projectId}?${queryParams}`, {
                        method: 'GET',
                        credentials: 'same-origin',
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.tasks = data.data.tasks;
                        this.links = data.data.pagination.links;
                        this.previous = data.data.pagination.previous;
                        this.next = data.data.pagination.next;
                    })
                    .catch(error => {
                        console.error(error);
                    });
                },
                init() {
                    this.fetchTasks();
                },
                paginate(page = 1) {
                    this.fetchTasks(page);
                },
                updateFilter(key, value) {
                    if (value) {
                        this.filters[key] = value;
                    } else {
                        delete this.filters[key];
                    }
                    this.fetchTasks();
                },
                updateOrder(key) {
                    let value = this.orders[key]

                    this.orders['order_id'] = '';
                    this.orders['order_name'] = '';
                    this.orders['order_status'] = '';
                    this.orders['order_delivery_start_date'] = '';
                    this.orders['order_delivery_end_date'] = '';
                    this.orders['order_progress'] = '';
                    this.orders['order_priority'] = '';

                    if (value == '') {
                        this.orders[key] = 'ASC';
                    } else if(value == 'ASC') {
                        this.orders[key] = 'DESC';
                    } else {
                        this.orders[key] = '';
                    }

                    this.fetchTasks();
                },
                clearFilters() {
                    this.filters = {};
                    document.querySelectorAll('[data-filter]').forEach(input => {
                        input.value = '';
                    });
                    this.fetchTasks();
                },
                setSiderbarRight(id) {
                    document.querySelector('#updateTaskForm').setAttribute('action', '/tasks/edit/' + id);
                    document.querySelector('#deleteTaskForm').setAttribute('action', '/tasks/delete/' + id);

                    fetch('/tasks/get-task/' + id, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                        },
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erro ao buscar dados da tarefa');
                        }
                        return response.json();
                    })
                    .then(data => {
                        document.querySelector('#updateTaskForm #name').value = data.name;
                        document.querySelector('#updateTaskForm #description').value = data.description;
                        document.querySelector('#updateTaskForm #delivery-start-date').value = data.delivery_start_date;
                        document.querySelector('#updateTaskForm #delivery-end-date').value = data.delivery_end_date;
                        document.querySelector('#updateTaskForm #priority').value = data.priority;
                        document.querySelector('#updateTaskForm #status').value = data.status;
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                    });
                }
            }
        }
    </script>




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
                <?= $this->Form->control('name', [
                    'label' => 'Nome da Tarefa', 
                    'class' => 'form-control',
                    'value' => !empty($formData['data']['name']) ? $formData['data']['name'] : ''
                ]) ?>
                <?php if (!empty($validationErrors['errors']) && isset($validationErrors['errors']['name']) && $validationErrors['entity'] == 'Tasks' && $validationErrors['action'] == 'edit'): ?>
                    <span class="text-danger"><?= reset($validationErrors['errors']['name']) ?></span>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <?= $this->Form->control('description', [
                    'label' => 'Descrição', 
                    'type' => 'textarea', 
                    'class' => 'form-control',
                    'value' => !empty($formData['data']['description']) ? $formData['data']['description'] : ''
                ]) ?>
                <?php if (!empty($validationErrors['errors']) && isset($validationErrors['errors']['description']) && $validationErrors['entity'] == 'Tasks'): ?>
                    <span class="text-danger"><?= reset($validationErrors['errors']['description']) ?></span>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <?= $this->Form->control('delivery_date', [
                    'label' => 'Data de Entrega', 
                    'type' => 'date', 
                    'class' => 'form-control',
                    'value' => !empty($formData['data']['delivery_date']) ? $formData['data']['delivery_date'] : ''
                ]) ?>
                <?php if (!empty($validationErrors['errors']) && isset($validationErrors['errors']['delivery_date']) && $validationErrors['entity'] == 'Tasks'): ?>
                    <span class="text-danger"><?= reset($validationErrors['errors']['delivery_date']) ?></span>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <?= $this->Form->control('priority', [
                    'label' => 'Prioridade',
                    'options' => ['alta' => 'Alta', 'média' => 'Média', 'baixa' => 'Baixa'],
                    'class' => 'form-control',
                    'value' => !empty($formData['data']['priority']) ? $formData['data']['priority'] : ''
                ]) ?>
                <?php if (!empty($validationErrors['errors']) && isset($validationErrors['errors']['priority']) && $validationErrors['entity'] == 'Tasks'): ?>
                    <span class="text-danger"><?= reset($validationErrors['errors']['priority']) ?></span>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <?= $this->Form->control('status', [
                    'label' => 'Status',
                    'options' => ['pendente' => 'Pendente', 'em andamento' => 'Em andamento', 'concluída' => 'Concluída'],
                    'class' => 'form-control',
                    'value' => !empty($formData['data']['status']) ? $formData['data']['status'] : ''
                ]) ?>
                <?php if (!empty($validationErrors['errors']) && isset($validationErrors['errors']['status']) && $validationErrors['entity'] == 'Tasks'): ?>
                    <span class="text-danger"><?= reset($validationErrors['errors']['status']) ?></span>
                <?php endif; ?>
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

<!-- Abre sidebar right quando form for inválido -->
<?php if (!empty($validationErrors['errors']) && $validationErrors['entity'] == 'Tasks' && $validationErrors['action'] == 'edit'): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var myOffcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasRight'));
            myOffcanvas.show();
        });
    </script>
<?php endif; ?>


</x-app-layout>
