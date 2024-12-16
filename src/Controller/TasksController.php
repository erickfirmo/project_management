<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Tasks Controller
 *
 * @property \App\Model\Table\TasksTable $Tasks
 */
class TasksController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Tasks->find()
            ->contain(['Projects']);
        $tasks = $this->paginate($query);

        $this->set(compact('tasks'));
    }

    /**
     * View method
     *
     * @param string|null $id Task id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $task = $this->Tasks->get($id, contain: ['Projects']);
        $this->set(compact('task'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $task = $this->Tasks->newEmptyEntity();
        if ($this->request->is('post')) {
            $task = $this->Tasks->patchEntity($task, $this->request->getData());
            if ($this->Tasks->save($task)) {
                $this->Flash->success(__('The task has been saved.'));

                return $this->redirect(['controller' => 'Projects', 'action' => 'tasks', $task->project_id]);
            }
            $this->Flash->error(__('The task could not be saved. Please, try again.'));
        }
        $projects = $this->Tasks->Projects->find('list', limit: 200)->all();
        #$this->set(compact('task', 'projects'));
        

        return $this->redirect(['controller' => 'Projects', 'action' => 'tasks', $task->project_id]);
    }

    /**
     * Edit method
     *
     * @param string|null $id Task id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $task = $this->Tasks->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $task = $this->Tasks->patchEntity($task, $this->request->getData());
            if ($this->Tasks->save($task)) {
                $this->Flash->success(__('The task has been saved.'));

                return $this->redirect(['controller' => 'Projects', 'action' => 'tasks', $task->project_id]);
            }
            $this->Flash->error(__('The task could not be saved. Please, try again.'));
        }
        $projects = $this->Tasks->Projects->find('list', limit: 200)->all();

        return $this->redirect(['controller' => 'Projects', 'action' => 'tasks', $task->project_id]);

    }

    /**
     * Delete method
     *
     * @param string|null $id Task id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $task = $this->Tasks->get($id);
        if ($this->Tasks->delete($task)) {
            $this->Flash->success(__('The task has been deleted.'));
        } else {
            $this->Flash->error(__('The task could not be deleted. Please, try again.'));
        }

        return $this->redirect(['controller' => 'Projects', 'action' => 'tasks', $task->project_id]);
    }

    public function getTask($id = null)
    {
        $this->request->allowMethod(['get', 'ajax']);

        if (!$id) {
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode(['error' => 'ID inválido.']));
        }
    
        $task = $this->Tasks->find()
            ->where(['id' => $id])
            ->first();
    
        if (!$task) {
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode(['error' => 'Projeto não encontrado.']));
        }
    
        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($task));
    }

    public function list($projectId = null)
    {
        $page = $this->request->getQuery('page') ? $this->request->getQuery('page') : 1;
        $perPage = $this->request->getQuery('per_page') ? $this->request->getQuery('per_page') : 5;

        $name = $this->request->getQuery('name');
        $status = $this->request->getQuery('status');
        $priority = $this->request->getQuery('priority');
        $deliveryStartDate = $this->request->getQuery('delivery_start_date');
        $deliveryEndDate = $this->request->getQuery('delivery_end_date');

        $orderId = $this->request->getQuery('order_id');
        $orderName = $this->request->getQuery('order_name');
        $orderStatus = $this->request->getQuery('order_status');
        $orderDeliveryDate = $this->request->getQuery('order_delivery_date');
        $orderProgress = $this->request->getQuery('order_progress');
        $orderPriority = $this->request->getQuery('order_priority');

        $filters = [];

        $filters['Tasks.project_id'] = $projectId;

        if ($name) {
            $filters['Tasks.name LIKE'] = "%$name%";
        }

        if ($status) {
            $filters['Tasks.status'] = $status;
        }

        if ($priority) {
            $filters['Tasks.priority'] = $priority;
        }

        if($deliveryStartDate) {
            $filters['Tasks.delivery_date >='] = $deliveryStartDate;
        }

        if($deliveryEndDate) {
            $filters['Tasks.delivery_date <='] = $deliveryEndDate;
        }

        $allTasks = $this->Tasks->find();

        $query = $this->Tasks->find()->select([
            'id',
            'name',
            'description',
            'delivery_date',
            'status',
            'priority',
            'progress' => $allTasks->func()->coalesce([
                $allTasks->newExpr('FLOOR((SUM(CASE WHEN Tasks.status = "concluída" THEN 1 ELSE 0 END) / COUNT(Tasks.id)) * 100)'),
                0
            ]),
        ])
        ->leftJoinWith('Projects')
        ->group('Tasks.id')
        ->where($filters);

        $orders = [];

        if ($orderId) {
            $orders['Tasks.id'] = $orderId;
        }

        if ($orderName) {
            $orders['Tasks.name'] = $orderName;
        }

        if ($orderStatus) {
            $orders["FIELD(Tasks.status, 'pendente', 'em andamento', 'concluída')"] = $orderStatus;
        }

        if ($orderDeliveryDate) {
            $orders['Tasks.delivery_date'] = $orderDeliveryDate;
        }

        if ($orderProgress) {
            $orders['progress'] = $orderProgress;
        }

        if ($orderPriority) {
            $orders['priority'] = $orderPriority;
        }

        if (empty($orders)) {
            $query->order(['Tasks.id' => 'DESC']);
        }

        $query->order($orders);

        $tasks = $query->limit($perPage)
            ->offset(($page - 1) * $perPage)
            ->all();

        $total = $allTasks->count();
        $totalFiltered = $tasks->count();

        if (!empty($filters) && $totalFiltered > 0) {
            $total = $totalFiltered;
        }

        $lastPage = $perPage ? ceil($total / $perPage) : null;

        $links = $perPage ? range(1, $lastPage) : null;

        $response = [
            'status' => 200,
            'message' => '',
            'data' => [
                'tasks' => $tasks,
                'pagination' => [
                    'total' => $total,
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'last_page' => $lastPage,
                    'links' => $links,
                    'previous' => $page > 1 ? $page - 1 : null,
                    'next' => $page != $lastPage ? $page + 1 : null
                ],
            ]
        ];

        return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode($response));
    }

}
