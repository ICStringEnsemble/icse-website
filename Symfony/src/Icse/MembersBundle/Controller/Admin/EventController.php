<?php

namespace Icse\MembersBundle\Controller\Admin;

use Acts\SocialApiBundle\Exception\ApiException;
use Symfony\Component\HttpFoundation\Request;
use Icse\PublicBundle\Entity\Event;

class EventController extends EntityAdminController
{
    /**
     * @return \Icse\PublicBundle\Entity\EventRepository
     */
    protected function repository()
    {
        return $this->getDoctrine()->getRepository('IcsePublicBundle:Event');
    }

    protected function viewName()
    {
        return 'IcseMembersBundle:Admin:events.html.twig';
    }

    protected function newInstance()
    {
        return new Event();
    }

    protected function getTableContent()
    {
        $entities = $this->repository()->findAllEventsDescUnknownFirst();

        $columns = array(
            array('heading' => 'Name', 'cell' => function(Event $x){return $x->getName();}),
            array('heading' => 'Date', 'cell' => function(Event $x){return $x->getStartsAt()? $x->getStartsAt()->format('D jS F Y') : "?";}),
            array('heading' => 'Time', 'cell' => function(Event $x){return $x->getStartsAt()? $x->getStartsAt()->format('g:ia') : "?";}),
            array('heading' => 'Where', 'cell' => function(Event $x){return $x->getLocationName();}),
            array('heading' => '<i class="fa fa-facebook-square"></i>', 'cell' => function(Event $x){return $x->getFacebookStatusIcon();}),
//            array('heading' => '<i class="fa fa-google-plus"></i>', 'cell' => function(Event $x){return '<i class="fa fa-times"></i>';}),
            array('heading' => 'Last updated', 'cell' => function(Event $x){return $this->timeagoDate($x->getUpdatedAt()) . " by " .$x->getUpdatedBy()->getFirstName();}),
            );
        return array("columns" => $columns, "entities" => $entities);
    }

    protected function getForm($entity)
    {
        $form = $this->createFormBuilder($entity)
        ->add('name', 'text')
        ->add('starts_at', 'datetime12', array(
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'date_format' => 'dd/MM/yy',
        ))
        ->add('location', 'entity', array(
            'class' => 'IcsePublicBundle:Venue',
            'property' => 'name',
            'required' => false,
        ))
        ->getForm(); 
        return $form;
    }

    protected function instanceOperationAction($request, $id, $op)
    {
        if ($op === "socialnetsync")
        {
            /* @var $event Event */
            $event = $this->getEntityById($id);
            return $this->socialNetSync($request, $event);
        }
        return parent::instanceOperationAction($request, $id, $op);
    }

    private function socialNetSync(Request $request, Event $event)
    {
        $fb_page_token = $request->request->get('fb');
        $fb_api = $this->get('acts.social_api.apis.facebook');
        $fb_api->authenticateWithCredentials($fb_page_token);

        if ($event->getFacebookStatus() == Event::$SOCIAL_EVENT_NOT_CREATED)
        {
            $page_id = $this->container->getParameter('facebook_page_id');
            /** @noinspection PhpUndefinedMethodInspection */
            $fb_ret = $fb_api->doCreatePageEvent(
                $page_id,
                $event->getName(),
                "Some description",
                $event->getStartsAt()->format('c'),
                $event->getEndsAt()->format('c'),
                $event->getLocationName(),
                ''
            );
            $fb_event_id = $fb_ret['id'];
            if ($fb_event_id)
            {
                $event->setFacebookId($fb_event_id);
                $event->setFacebookStatus(Event::$SOCIAL_EVENT_SYNCED);
                $em = $this->getDoctrine()->getManager();
                $em->persist($event);
                $em->flush();
                return $this->get('ajax_response_gen')->returnSuccess();
            }
            return $this->get('ajax_response_gen')->returnFail(['fb_error' => true]);
        }
        return $this->get('ajax_response_gen')->returnSuccess();
    }

    public function deleteAction($id)
        /* @var $event Event */
    {
        $event = $this->getEntityById($id);
        if ($event->isSocialNetworkEnabled())
        {
            $request = $this->getRequest();
            $fb_page_token = $request->request->get('fb');
            $fb_api = $this->get('acts.social_api.apis.facebook');
            $fb_api->authenticateWithCredentials($fb_page_token);
            try {
                /** @noinspection PhpUndefinedMethodInspection */
                $fb_api->doDeleteEvent($event->getFacebookId());
            } catch (ApiException $e) {
                return $this->get('ajax_response_gen')->returnFail(['fb_error' => true]);
            }
        }
        return parent::deleteAction($id);
    }

    protected function putData($request, $entity)
        /* @var $entity Event */
    {
        $is_new_event = ($entity->getID() === null);

        $form = $this->getForm($entity);
        $form->submit($request);

        $entity->setUpdatedAt(new \DateTime());
        $entity->setUpdatedBy($this->get('security.context')->getToken()->getUser());

        if ($is_new_event)
        {
            $entity->setFacebookStatus(Event::$SOCIAL_EVENT_NOT_CREATED);
        }

        $em = $this->getDoctrine()->getManager();
        if ($form->isValid())
        {
            $em->persist($entity);
            $em->flush();
            return $this->get('ajax_response_gen')->returnSuccess();
        }
        else
        {
            // Cancel any changes
            if ($em->contains($entity))
            {
                $em->refresh($entity);
            }
            return $this->get('ajax_response_gen')->returnFail($form);
        }  
    }



}
