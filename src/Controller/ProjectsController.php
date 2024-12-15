<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Projects Controller
 *
 * @property \App\Model\Table\ProjectsTable $Projects
 */
class ProjectsController extends AppController
{
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

    /**
     * View method
     *
     * @param string|null $id Project id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $project = $this->Projects->get($id, contain: ['Tasks']);
        $this->set(compact('project'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $project = $this->Projects->newEmptyEntity();
        $query = $this->Projects->find();
        $projects = $this->paginate($query);

        if ($this->request->is('post')) {
            $project = $this->Projects->patchEntity($project, $this->request->getData());
            $project->status = 'ativo';

            if (!$this->Projects->save($project)) {
                $errors = $project->getErrors();
                $this->Flash->error(__('The project could not be saved. Please, try again.'));
            } else {
                $this->Flash->success(__('The project has been saved.'));
            }
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
                $this->Flash->success(__('The project has been updated.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The project could not be saved. Please, try again.'));
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
