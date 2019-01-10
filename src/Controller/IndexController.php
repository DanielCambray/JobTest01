<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AccountType;
use App\Entity\Account;
use App\Events;
use Symfony\Component\EventDispatcher\GenericEvent;

class IndexController extends AbstractController
{
    /**
     * Homepage
     *
     * @Route("/", name="home")
     *
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @return Response
     */
    public function index(EntityManagerInterface $em, Request $request, EventDispatcherInterface $eventDispatcher) : Response
    {
        $account = new Account();

        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($account);
            $em->flush();

            $event = new GenericEvent($account);
            $eventDispatcher->dispatch(Events::ACCOUNT_CREATED, $event);

            return $this->render('home/success.html.twig');
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
