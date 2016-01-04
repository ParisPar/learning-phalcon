<?php
namespace App\Backoffice\Controllers;

class CategoryController extends BaseController
{
    public function indexAction()
    {
        return $this->dispatcher->forward(['action' => 'list']);
    }

    /**
     * List
     */
    public function listAction()
    {
        $page = $this->request->getQuery('p', 'int', 1);

        try {
            $categories = $this->apiGet('categories?p='.$page);

            $this->view->categories = $categories;
        } catch (\Exception $e) {
            $this->flash->error($e->getMessage());
        }
    }

    /**
     * Renders the view to create a new record
     */
    public function addAction()
    {
        $manager = $this->getDI()->get('core_category_manager');
        $this->view->form = $manager->getForm();
    }

    /**
     * Renders the view to edit an existing record
     */
    public function editAction($id)
    {
        $manager = $this->getDI()->get('core_category_manager');
        $object  = $manager->findFirstById($id);

        if (!$object) {
            $this->flashSession->error('Object not found');

            return $this->response->redirect('category/list');
        }

        $this->persistent->set('id', $id);

        $this->view->form = $manager->getForm($object,['edit' => true]);
    }

    /**
     * Creates a new record
     * @return \Phalcon\Http\ResponseInterface
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->response->redirect('category/list');
        }

        $manager = $this->getDI()->get('core_category_manager');
        $form    = $manager->getForm();

        if ($form->isValid($this->request->getPost())) {
            try {
                $manager   = $this->getDI()->get('core_category_manager');

                //$post_data contains an array with locales as indices.
                //Ex: $post_data['translation']['en'], $post_data['translation']['gr'] etc...
                $post_data = $this->request->getPost();
                

                //Add category_is_active flag to the post data. 
                //Along with the translations we have all the data we need to create a category
                $data = array_merge($post_data, ['category_is_active' => 1]);

                /*
                Example final $data array:
                    $data = array(3) {
                  ["translations"]=>
                  array(2) {
                    ["en"]=>
                    array(3) {
                      ["category_translation_name"]=>
                      string(15) "Category name 3"
                      ["category_translation_slug"]=>
                      string(0) ""
                      ["category_translation_lang"]=>
                      string(2) "en"
                    }
                    ["gr"]=>
                    array(3) {
                      ["category_translation_name"]=>
                      string(18) "Onoma Kathgorias 3"
                      ["category_translation_slug"]=>
                      string(0) ""
                      ["category_translation_lang"]=>
                      string(2) "gr"
                    }
                  }
                  ["csrf"]=>
                  string(15) "W0jJtkHlOEwqnDm"
                  ["category_is_active"]=>
                  int(1)
                }
                */
                $manager->create($data);
                $this->flashSession->success('Object was created successfully');

                return $this->response->redirect('category/list');
            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());

                return $this->dispatcher->forward(['action' => 'add']);
            }
        } else {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message->getMessage());
            }

            return $this->dispatcher->forward(['action' => 'add', 'controller' => 'category']);
        }
    }

    /**
     * Updates a record
     * @return \Phalcon\Http\ResponseInterface
     */
    public function updateAction()
    {
        if (!$this->request->isPost()) {
            return $this->response->redirect('category/list');
        }

        $manager    = $this->getDI()->get('core_category_manager');
        $object_id  = $this->persistent->get('id');
        $object     = $manager->findFirstById($object_id);
        $form       = $manager->getForm($object);

        if ($form->isValid($this->request->getPost())) {
            try {
                $manager = $this->getDI()->get('core_category_manager');

                //Add the id of the category in the post data. The manager will read the category id from here.
                $allCategoryData = array_merge($this->request->getPost(), ['id' => $object_id]);

                $manager->update($allCategoryData);
                $this->flashSession->success('Object was updated successfully');

                return $this->response->redirect('category/list');
            } catch (\Exception $e) {
                $this->flash->error($e->getMessage());

                return $this->dispatcher->forward(['action' => 'edit']);
            }
        } else {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message->getMessage());
            }

            return $this->dispatcher->forward(['action' => 'edit', 'controller' => 'category']);
        }
    }

    /**
     * Delete an existing record
     * @param  number                          $id
     * @return \Phalcon\Http\ResponseInterface
     */
    public function deleteAction($id)
    {
        if ($this->request->isPost()) {
            try {
                $manager = $this->getDI()->get('core_category_manager');
                $manager->delete($id);
                $this->flashSession->success('Object has been deleted successfully');
            } catch (\Exception $e) {
                $this->flashSession->error($e->getMessage());
            }

            return $this->response->redirect('category/list');
        }

        $this->view->id = $id;
    }
}
