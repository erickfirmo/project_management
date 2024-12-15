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

        return $this->redirect(['action' => 'index']);
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
}
