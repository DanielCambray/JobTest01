<?php


namespace App\Controller\Admin;

use App\Events;
use App\Entity\Account;
use App\Entity\Country;
use App\Form\TestType;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Intl\Intl;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller used to manage accounts in the backend.
 *
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 **/
class AccountController extends AbstractController
{
    /**
     * Lists all Account entities.
     *
     * @Route(
     *     "/accounts/{page}",
     *     methods={"GET"},
     *     name="admin_account_index",
     *     defaults={"page": 1},
     *    requirements={"page" = "\d+"}
     * )
     *
     * @param AccountRepository $accountRepository
     * @param int $page
     * @param PaginatorInterface $paginator
     *
     * @return Response
     */
    public function index(AccountRepository $accountRepository, int $page, PaginatorInterface $paginator): Response
    {
        $accounts = $paginator->paginate(
            $accountRepository->getPaginatedAccountsByCountryQuery(),
            $page,
            $this->getParameter('max_display_accounts')
        );

        $countries = $accountRepository->countByCountry();

        return $this->render(
            'admin/account/index.html.twig',
            ['accounts' => $accounts, 'countries' => $countries]
        );
    }

    /**
     * Lists all Account entities by CountryCode.
     *
     * @Route(
     *     "/accounts/{code}/{page}",
     *     methods={"GET"},
     *     name="admin_account_by_country",
     *     defaults={"page": 1},
     *     requirements={"page" = "\d+", "code" = "[a-z]{2}"}
     * )
     *
     * @param AccountRepository $accountRepository
     * @param int $page
     * @param PaginatorInterface $paginator
     * @param string $code
     *
     * @return Response
     */
    public function indexByCountry(AccountRepository $accountRepository, int $page, PaginatorInterface $paginator, string $code): Response
    {
        // Check that the country code is valid
        $country = Intl::getRegionBundle()->getCountryName(strtoupper($code));
        if (!$country) {
            throw $this->createNotFoundException('The country code is invalid');
        }

        $accounts = $paginator->paginate(
            $accountRepository->getPaginatedAccountsByCountryQuery($code),
            $page,
            $this->getParameter('max_display_accounts')
        );

        $countries = $accountRepository->countByCountry();

        return $this->render('admin/account/index.html.twig', ['accounts' => $accounts, 'countryCode' => $code, 'countries' => $countries]);
    }

    /**
     * Creates a new Account entity.
     *
     * @Route("/account/new", methods={"GET", "POST"}, name="admin_account_new")
     *
     * @param Request $request
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @return Response
     */
    public function new(Request $request, EventDispatcherInterface $eventDispatcher): Response
    {
        $account = new Account();

        $form = $this->createForm(AccountType::class, $account)
            ->add('saveAndCreateNew', SubmitType::class)
            ->add('save', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($account);
            $em->flush();

            $event = new GenericEvent($account);
            $eventDispatcher->dispatch(Events::ACCOUNT_CREATED, $event);

            $this->addFlash('success', 'account.created_successfully');

            // User want to stay on the page
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('admin_account_new');
            } else {

                // Redirect to referer if available
                if ($request->request->get('_referer')) {
                    return $this->redirect($request->request->get('_referer'));
                } else {
                    return $this->redirectToRoute('admin_account_index');
                }
            }
        }

        return $this->render('admin/account/new.html.twig', [
            'account' => $account,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays an Account entity.
     *
     * @Route("/account/{id<\d+>}", methods={"GET"}, name="admin_account_show")
     *
     * @param Account $account
     *
     * @return Response
     */

    public function show(Account $account): Response
    {
        return $this->render('admin/account/show.html.twig', [
            'account' => $account,
        ]);
    }

    /**
     * Displays a form to edit an existing Account entity.
     *
     * @Route("/account/{id<\d+>}/edit",methods={"GET", "POST"}, name="admin_account_edit")
     *
     * @param Request $request
     * @param Account $account
     *
     * @return Response
     */
    public function edit(Request $request, Account $account): Response
    {
        $form = $this->createForm(AccountType::class, $account)
            ->add('edit', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'account.updated_successfully');

            // Redirect to referer if available
            if ($request->request->get('_referer')) {
                return $this->redirect($request->request->get('_referer'));
            } else {
                return $this->redirectToRoute('admin_account_index');
            }
        }

        return $this->render('admin/account/edit.html.twig', [
            'account' => $account,
            'form' => $form->createView(),
            'referer' => $request->headers->get('referer')
        ]);
    }

    /**
     * Deletes an Account entity.
     *
     * @Route("/account/{id}/delete", methods={"DELETE"}, name="admin_account_delete")
     *
     * @param Request $request
     * @param Account $account
     *
     * @return Response
     */
    public function delete(Request $request, Account $account): Response
    {
        if (!$this->isCsrfTokenValid('delete' . $account->getId(), $request->request->get('_token'))) {
            $this->addFlash('warning', 'account.deleted_error');
            return $this->redirect($request->headers->get('referer'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($account);
        $em->flush();

        $this->addFlash('success', 'account.deleted_successfully');

        return $this->redirect($request->headers->get('referer'));
    }
}
