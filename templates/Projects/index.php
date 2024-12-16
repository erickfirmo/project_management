<x-app-layout>
<h1 class="text-center my-4">Lista de Projetos</h1>

<div class="container" x-data="projectsData()">

    <div class="row">
        <div class="col-md-5 mb-4">
            <input x-ref="inputName" x-on:input.change="updateFilter('name', $event.target.value)" type="text" name="name" placeholder="Nome" data-filter class="form-control">
        </div>
        <div class="col-md-5 mb-4">
            <select x-ref="inputStatus" x-on:change="updateFilter('status', $event.target.value)" name="status" data-filter class="form-control">
                <option value="">Selecione o status</option>
                <option value="inativo">Inativo</option>
                <option value="em-andamento">Em Andamento</option>
                <option value="concluido">Concluído</option>
            </select>

        </div>
        <div class="col-md-3 mb-3">
            <button x-on:click="clearFilters();" class="btn btn-light w-100">Limpar</button>
        </div>
    </div>

    <hr>

    <div class="row mb-3">
        <?= $this->Form->create(null, ['url' => ['action' => 'add'], 'class' => 'form-horizontal']) ?>

            <div class="form-group d-flex">
                <?= $this->Form->control('name', ['value' => '', 'label' => false, 'placeholder' => 'Digite o Nome do Projeto', 'class' => 'form-control']) ?>
                <button type="submit" class="btn btn-dark mx-2">
                    Adicionar
                </button>
            </div>

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
                <th>Data de Início 
                    <span
                    x-on:click="updateOrder('order_start_date')" 
                    x-text="orders['order_start_date'] == 'DESC' || orders['order_start_date'] == '' ? '&#9660;' : '&#9650;'"
                    :class="orders['order_start_date'] == '' ? 'opacity-25' : ''"
                    >
                    </span>
                </th>
                <th>Data de Término 
                    <span
                    x-on:click="updateOrder('order_end_date')" 
                    x-text="orders['order_end_date'] == 'DESC' || orders['order_end_date'] == '' ? '&#9660;' : '&#9650;'"
                    :class="orders['order_end_date'] == '' ? 'opacity-25' : ''"
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
            </tr>
        </thead>
        <tbody>
            <template x-for="project in projects" :key="project.id">
                <tr>
                    <td><span x-text="project.id"></span></td>
                    <td><span x-text="project.name"></span></td>
                    <td>
                        <span :class="{
                            'bg-success badge': project.status === 'ativo',
                            'bg-secondary badge': project.status === 'inativo',
                            'bg-info badge': project.status !== 'ativo' && project.status !== 'inativo'
                        }">
                            <span x-text="project.status"></span>
                        </span>
                    </td>
                    <td><span x-text="project.start_date"></span></td>
                    <td><span x-text="project.end_date"></span></td>
                    <td><span x-text="project.progress"></span>%</td>
                </tr>
            </template>
            <template x-if="projects.length == 0">
                <div>Nenhum resultado</div>
            </template>
            
        </tbody>
    </table>

    <template x-if="projects.length > 0">
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
        const projectsData = () => {
            return {
                projects: [],
                links: [],
                previous: null,
                next: null,
                page: null,
                filters: {},
                orders: {
                    order_id: 'DESC',
                    order_name: '',
                    order_status: '',
                    order_start_date: '',
                    order_end_date: '',
                    order_progress: '',
                },
                ordersReset: {
                    order_id: '',
                    order_name: '',
                    order_status: '',
                    order_start_date: '',
                    order_end_date: '',
                    order_progress: '',
                },
                fetchProjects(page = 1) {

                    const queryParams = new URLSearchParams({ page, ...this.filters, ...this.orders }).toString();

                    this.page = page;
                    fetch(`/projects/list?${queryParams}`, {
                        method: 'GET',
                        credentials: 'same-origin',
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.projects = data.data.projects;
                        this.links = data.data.pagination.links;
                        this.previous = data.data.pagination.previous;
                        this.next = data.data.pagination.next;
                    })
                    .catch(error => {
                        console.error(error);
                    });
                },
                init() {
                    this.fetchProjects();
                },
                paginate(page = 1) {
                    this.fetchProjects(page);
                },
                updateFilter(key, value) {
                    if (value) {
                        this.filters[key] = value;
                    } else {
                        delete this.filters[key];
                    }
                    this.fetchProjects();
                },
                updateOrder(key) {
                    let value = this.orders[key]

                    this.orders['order_id'] = '';
                    this.orders['order_name'] = '';
                    this.orders['order_start_date'] = '';
                    this.orders['order_end_date'] = '';
                    this.orders['order_progress'] = '';
                    this.orders['order_status'] = '';

                    if (value == '') {
                        this.orders[key] = 'ASC';

                    } else if(value == 'ASC') {
                        this.orders[key] = 'DESC';

                    } else {
                        this.orders[key] = '';
                    }

                    this.fetchProjects();
                },
                clearFilters() {
                    this.filters = {};
                    document.querySelectorAll('[data-filter]').forEach(input => {
                        input.value = '';
                    });
                    this.fetchProjects();
                }

            }
        }
    </script>

</x-app-layout>
