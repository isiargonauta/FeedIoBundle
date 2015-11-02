<?php

namespace Debril\FeedIoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Crud abstract controller.
 *
 */
abstract class CrudControllerAbstract extends Controller
{

    /**
     * You MUST specify which entity is managed by the controller
     * @string 
     */
    const ENTITY_NAME = '';

    /**
     * @return 
     */
    abstract protected function getEntity();
    
    /**
     * @return 
     */
    abstract protected function getFormType();

    /**
     * @return string 
     */
    public function getEntityName()
    {
        return static::ENTITY_NAME;
    }
    
    /**
     * @return string 
     */
    public function getRepositoryName()
    {
        $name = ucfirst($this->getEntityName());
        
        return "DebrilFeedIoBundle:{$name}";
    }

    /**
     * @return string 
     */
    public function getTemplateFullName($template)
    {
        $name = ucfirst($this->getEntityName());
        return "DebrilFeedIoBundle:Crud/{$name}:{$template}";
    }
    
    /**
     * @return string 
     */
    public function getEntityRoute($action = null)
    {
        $args = [
            'crud',
            $this->getEntityName(),
        ];
        
        if ( ! is_null($action) ) {
            $args[] = $action;
        }
        
        return implode('_', $args);
    }

    /**
     * Lists all entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository($this->getRepositoryName())->findAll();

        return $this->render($this->getTemplateFullName('index.html.twig'), array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = $this->getEntity();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl($this->getEntityRoute('show'), array('id' => $entity->getId())));
        }

        return $this->render($this->getTemplateFullName('new.html.twig'), array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a entity.
     *
     * @param $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
        $form = $this->createForm($this->getFormType(), $entity, array(
            'action' => $this->generateUrl($this->getEntityRoute('create')),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new entity.
     *
     */
    public function newAction()
    {
        $entity = $this->getEntity();
        $form   = $this->createCreateForm($entity);

        return $this->render($this->getTemplateFullName('new.html.twig'), array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->getRepositoryName())->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render($this->getTemplateFullName('show.html.twig'), array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->getRepositoryName())->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render($this->getTemplateFullName('edit.html.twig'), array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit an entity.
    *
    * @param $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
        $form = $this->createForm($this->getFormType(), $entity, array(
            'action' => $this->generateUrl($this->getEntityRoute('update'), array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->getRepositoryName())->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl($this->getEntityRoute('edit'), array('id' => $id)));
        }

        return $this->render($this->getTemplateFullName('edit.html.twig'), array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository($this->getRepositoryName())->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl($this->getEntityRoute()));
    }

    /**
     * Creates a form to delete an entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl($this->getEntityRoute('delete'), array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
