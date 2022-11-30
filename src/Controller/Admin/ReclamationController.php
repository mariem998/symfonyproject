<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReclamationController extends AbstractController
{
    /**
     * This controller display all contacts
     *
     * @param ContactRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/reclamation', name: 'reclamation.index', methods: ['GET'])]
    public function index(
        ContactRepository  $repository,
        PaginatorInterface $paginator,
        Request            $request
    ): Response
    {
        $contacts = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('reclamation/index.html.twig', [
            'contacts' => $contacts
        ]);
    }
    /**
     * This controller allows us to delete a contact
     *
     * @param EntityManagerInterface $manager
     * @param Contact $contact
     * @return Response
     */
    #[Route('/reclamation/suppression/{id}', 'reclamation.delete', methods: ['GET']), isGranted("ROLE_ADMIN")]

    public function delete(
        EntityManagerInterface $manager,
        Contact $contact
    ): Response {
        $manager->remove($contact);
        $manager->flush();

        $this->addFlash(
            'success',
            'Contact is successfully deleted !'
        );

        return $this->redirectToRoute('reclamation.index');
    }
}