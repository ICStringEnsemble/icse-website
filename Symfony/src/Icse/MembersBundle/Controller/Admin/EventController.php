<?php

namespace Icse\MembersBundle\Controller\Admin;

use Acts\SocialApiBundle\Exception\ApiException;
use Doctrine\Common\Collections\ArrayCollection;
use Icse\MembersBundle\Form\Type\PerformanceOfAPieceType;
use Icse\PublicBundle\Entity\PerformanceOfAPiece;
use Symfony\Component\HttpFoundation\Request;
use Icse\PublicBundle\Entity\Event;
use Icse\PublicBundle\Entity\PieceOfMusic;

class EventController extends EntityAdminController
{
    /**
     * @return \Icse\PublicBundle\Entity\EventRepository
     */
    protected function repository()
    {
        return $this->getDoctrine()->getRepository('IcsePublicBundle:Event');
    }

    protected function getViewName()
    {
        return 'IcseMembersBundle:Admin:events.html.twig';
    }

    protected function newInstance()
    {
        return new Event();
    }

    protected function newInstancePrototype()
    {
        $event = $this->newInstance();
        $performance = new PerformanceOfAPiece();
        $event->addPrototypePerformance($performance);
        return $event;
    }

    private function facebookStatusIcon(Event $x)
    {
        if (!$x->isFacebookEnabled()) return '-';
        else if (!$x->isFacebookSynced()) return '<i class="fa fa-unlink"></i>';
        else return '<i class="fa fa-check"></i>';
    }

    protected function getListContent()
    {
        $entities = $this->repository()->findAllEventsDescUnknownFirst();

        $fields = [
            'Name' => function(Event $x){return $x->getName();},
            'Date' => function(Event $x){return $x->getStartsAt()? $x->getStartsAt()->format('D jS F Y') : "?";},
            'Time' => function(Event $x){return $x->isStartTimeKnown()? $x->getStartsAt()->format('g:ia') : "?";},
            'Where' => function(Event $x){return $x->getLocationName();},
            'Performances' => function(Event $x){return count($x->getPerformances());},
//            '<i class="fa fa-facebook-square"></i>' => function(Event $x){return $this->facebookStatusIcon($x);},
            'Last updated' => function(Event $x){return $this->timeagoDate($x->getUpdatedAt()) . " by " .$x->getUpdatedBy()->getFirstName();},
        ];
        return ["fields" => $fields, "entities" => $entities];
    }

    protected function getFormCollectionNames()
    {
        return ['performances'];
    }

    protected function getForm($entity)
    {
        $form = $this->createFormBuilder($entity)
        ->add('name', 'text')
        ->add('starts_at', 'datetime12', array(
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'date_format' => 'dd/MM/yy',
            'required' => false,
        ))
        ->add('location', 'entity', array(
            'class' => 'IcsePublicBundle:Venue',
            'property' => 'name',
            'required' => false,
            'attr' => ['class' => 'entity-select']
        ))
        ->add('poster', 'entity', [
            'class' => 'IcsePublicBundle:Image',
            'property' => 'name',
            'required' => false,
            'attr' => ['class' => 'entity-select']
        ])
        ->add('performances', 'collection', [
            'type' => new PerformanceOfAPieceType(),
            'allow_add' => true,
            'allow_delete' => true,
        ])
        ->add('performance_adder', 'entity', [
            'class' => 'IcsePublicBundle:PieceOfMusic',
            'property' => 'full_name',
            'required' => false,
            'mapped' => false,
        ])
        ->add('description', 'textarea', [
            'required' => false,
            'label' => 'Information',
            'attr' => ['class' => 'doceditor'],
        ])
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

        if (!$event->isFacebookEnabled()) // Create new
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
                $event->setFacebookSyncedAt(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($event);
                $em->flush();
                return $this->get('ajax_response_gen')->returnSuccess();
            }
            return $this->get('ajax_response_gen')->returnFail(['fb_error' => true]);
        }
        else // Update existing
        {
//            $fb_ret = $fb_api->doUpdateEvent(
//                $event->getFacebookId(),
//                $event->getName(),
//                "Some description",
//                $event->getStartsAt()->format('c'),
//                $event->getEndsAt()->format('c'),
//                $event->getLocationName(),
//                ''
//            );
//            $event->setFacebookSyncedAt(new \DateTime());

            return $this->get('ajax_response_gen')->returnSuccess();
        }
    }

    public function deleteAction($id)
        /* @var $event Event */
    {
        $event = $this->getEntityById($id);
        if ($event->isFacebookEnabled())
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
        $performances_before = new ArrayCollection;
        foreach ($entity->getPerformances() as $p) $performances_before->add($p);

        $is_new_event = ($entity->getID() === null);

        $form = $this->getForm($entity);
        $form->submit($request->request->get($form->getName()));

        $entity->setUpdatedAt(new \DateTime());
        $entity->setUpdatedBy($this->get('security.context')->getToken()->getUser());

        $em = $this->getDoctrine()->getManager();
        if ($form->isValid())
        {
            $em->persist($entity);

            foreach ($performances_before as $old_p)
            {
                if ($entity->getPerformances()->contains($old_p) === false) $em->remove($old_p);
            }

            foreach($entity->getPerformances() as $p)
            {
                $p->setEvent($entity);
                $em->persist($p);
            }

            $em->flush();
            return $this->get('ajax_response_gen')->returnSuccess(['entity' => $entity]);
        }
        else
        {
            // Cancel any changes
            if ($em->contains($entity))
            {
                $em->refresh($entity);
                foreach($entity->getPerformances() as $p) if ($em->contains($p)) $em->refresh($p);
            }
            return $this->get('ajax_response_gen')->returnFail($form);
        }  
    }



}
