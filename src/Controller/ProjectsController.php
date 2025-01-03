<?php
declare(strict_types=1);

namespace App\Controller;

use App\Traits\ApiPagination;

/**
 * Projects Controller
 *
 * @property \App\Model\Table\ProjectsTable $Projects
 */
class ProjectsController extends AppController
{
    use ApiPagination;
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Projects->find();
        $projects = $this->paginate($query);

        $this->set(compact('projects'));
    }


    public function list()
    {
        $page = $this->request->getQuery('page') ? $this->request->getQuery('page') : 1;
        $perPage = $this->request->getQuery('per_page') ? $this->request->getQuery('per_page') : 5;

        $name = $this->request->getQuery('name');
        $status = $this->request->getQuery('status');

        $orderId = $this->request->getQuery('order_id');
        $orderName = $this->request->getQuery('order_name');
        $orderStatus = $this->request->getQuery('order_status');
        $orderStartDate = $this->request->getQuery('order_start_date');
        $orderEndDate = $this->request->getQuery('order_end_date');
        $orderProgress = $this->request->getQuery('order_progress');

        $filters = [];

        if($name) {
            $filters['Projects.name LIKE'] = "%$name%";
        }

        if($status) {
            $filters['Projects.status'] = $status;
        }

        $allProjects = $this->Projects->find();

        $query = $this->Projects->find()->select([
            'id',
            'name',
            'description',
            'start_date',
            'end_date',
            'status',
            'progress' => $allProjects->func()->coalesce([
                $allProjects->newExpr('(SUM(CASE WHEN Tasks.status = "concluída" THEN 1 ELSE 0 END) / COUNT(Tasks.id)) * 100'),
                0
            ]),
        ])
        ->leftJoinWith('Tasks')
        ->group('Projects.id')
        ->where($filters);
        
        $orders = [];

        if($orderId) {
            $orders['Projects.id'] = $orderId;
        }

        if($orderName) {
            $orders['Projects.name'] = $orderName;
        }

        if($orderStatus) {
            $orders["FIELD(Projects.status, 'ativo', 'concluído', 'inativo')"] = $orderStatus;
        }

        if($orderStartDate) {
            $orders['Projects.start_date'] = $orderStartDate;
        }

        if($orderEndDate) {
            $orders['Projects.end_start'] = $orderEndDate;
        }

        if($orderProgress) {
            $orders['progress'] = $orderProgress;
        }

        if(empty($orders)) {
            $query->order(['Projects.id' => 'DESC']);
        }

        $query->order($orders);            

        $projects = $query->limit($perPage)
            ->offset(($page - 1) * $perPage)
            ->all();

        $total = $allProjects->count();
        $totalFiltered = $projects->count();

        $apiPagination = $this->apiPagination($total, $totalFiltered, $perPage, $page);

        $response = [
            'status' => 200,
            'message' => '',
            'data' => [
                'projects' => $projects,
                'pagination' => $apiPagination
            ]
        ];

        return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode($response));
    }

    /**
     * View method
     *
     * @param string|null $id Project id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function tasks($id = null)
    {
        $project = $this->Projects->get($id, contain: [
            'Tasks',
        ]);

        $tasks = $project->tasks;

        $query = $this->Projects->find();
        $projects = $this->paginate($query);

        $this->set(compact('projects', 'project', 'tasks'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $project = $this->Projects->newEmptyEntity();
        if ($this->request->is('post')) {
            $project = $this->Projects->patchEntity($project, $this->request->getData());
            $project->status = 'ativo';

            if ($this->Projects->save($project)) {
                $this->request->getSession()->write('successMessage', 'Projeto salvo com sucesso.');

                return $this->redirect(['action' => 'index']);

            }
            $this->Flash->error(__('O projeto não pôde ser salvo. Por favor, tente novamente.'));
            $this->request->getSession()->write('ValidationErrors', [ 'errors' => $project->getErrors(), 'entity' => 'Projects', 'action' => 'add']);
            $this->request->getSession()->write('FormData', [ 'data' => $this->request->getData(), 'entity' => 'Projects', 'action' => 'add']);
        }

        return $this->redirect(['action' => 'index']);

    }

    /**
     * Edit method
     *
     * @param string|null $id Project id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $project = $this->Projects->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $project = $this->Projects->patchEntity($project, $this->request->getData());
            if ($this->Projects->save($project)) {
                $this->request->getSession()->write('successMessage', 'Projeto atualizado com sucesso.');

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('O projeto não pôde ser salvo. Por favor, tente novamente.'));

            $this->request->getSession()->write('ValidationErrors', [ 'errors' => $project->getErrors(), 'entity' => 'Projects', 'action' => 'edit']);
            $this->request->getSession()->write('FormData', [ 'data' => $this->request->getData(), 'entity' => 'Projects', 'action' => 'edit']);

        }
        return $this->redirect(['action' => 'index']);

    }

    /**
     * Delete method
     *
     * @param string|null $id Project id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete', 'ajax']);
        $project = $this->Projects->get($id);
        if ($this->Projects->delete($project)) {
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode(['message' => __('Projeto deletado com sucesso!')]));
            
        } else {

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode(['error' => __('The project could not be deleted. Please, try again.')]));
        }

        return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode(['error' => __('The project could not be deleted. Please, try again.')]));
    }

    public function getProject($id = null)
    {
        $this->request->allowMethod(['get', 'ajax']);

        if (!$id) {
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode(['error' => 'ID inválido.']));
        }
    
        $project = $this->Projects->find()
            ->where(['id' => $id])
            ->first();
    
        if (!$project) {
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode(['error' => 'Projeto não encontrado.']));
        }
    
        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($project));
    }

}
