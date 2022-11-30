<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * This controller display all category
     *
     * @param CategoryRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/category', name: 'category.index', methods: ['GET']),isGranted("ROLE_ADMIN")]
    public function index(
        CategoryRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $categorys = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/category/index.html.twig', [
            'categorys' => $categorys
        ]);
    }

    /**
     * This controller show a form which create a category
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/category/creation', 'category.new'),isGranted("ROLE_ADMIN")]
    public function new(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $manager->persist($category);
            $manager->flush();

            $this->addFlash(
                'success',
                'Category is created successfully'
            );

            return $this->redirectToRoute('category.index');
        }

        return $this->render('pages/category/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * This controller allow us to edit a category
     *
     * @param Category $category
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/category/edition/{id}', 'category.edit', methods: ['GET', 'POST']), isGranted("ROLE_ADMIN")]
    public function edit(
        Category $category,
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $manager->persist($category);
            $manager->flush();

            $this->addFlash(
                'success',
                'Category is successfully modified !'
            );

            return $this->redirectToRoute('category.index');
        }

        return $this->render('pages/category/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * This controller allows us to delete a category
     *
     * @param EntityManagerInterface $manager
     * @param Category $category
     * @return Response
     */
    #[Route('/category/suppression/{id}', 'category.delete', methods: ['GET']), isGranted("ROLE_ADMIN")]

    public function delete(
        EntityManagerInterface $manager,
        Category $category
    ): Response {
        $manager->remove($category);
        $manager->flush();

        $this->addFlash(
            'success',
            'Category is successfully deleted !'
        );

        return $this->redirectToRoute('category.index');
    }
}