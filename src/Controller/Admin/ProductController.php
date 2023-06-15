<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class ProductController extends AbstractController
{
    /**
     * This controller display all products
     *
     * @param ProductRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/product', name: 'product.index', methods: ['GET']), isGranted("ROLE_ADMIN")]
    public function index(
        ProductRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $products = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/product/index.html.twig', [
            'products' => $products
        ]);
    }


    /**
     * This controller show a form which create a product
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/product/creation', 'product.new'), isGranted("ROLE_ADMIN")]
    public function new(
        SluggerInterface $slugger,
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $product = $form->getData();
            // $manager = $doctrine->getManager();
            if($request->files->get('product')['image']){
                $image = $request->files->get('product')['image'];
                $image_name =time().'_'.$image->getClientOriginalName();
                $image->move($this->getParameter('image_directory'),$image_name);
                $product->setImage($image_name);
            }
            $manager->persist($product);
            $manager->flush();

            $this->addFlash(
                'success',
                'Product is created successfully'
            );

            return $this->redirectToRoute('product.index');
        }

        return $this->render('pages/product/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * This controller allow us to edit a product
     *
     * @param Product $product
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/product/edition/{id}', 'product.edit', methods: ['GET', 'POST']), isGranted("ROLE_ADMIN")]
    public function edit(
        Product $product,
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            if($request->files->get('product')['image']){
                $image = $request->files->get('product')['image'];
                $image_name =time().'_'.$image->getClientOriginalName();
                $image->move($this->getParameter('image_directory'),$image_name);
                $product->setImage($image_name);
            }
            $manager->persist($product);
            $manager->flush();

            $this->addFlash(
                'success',
                'Your product is successfully modified !'
            );

            return $this->redirectToRoute('product.index');
        }

        return $this->render('pages/product/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * This controller allows us to delete a product
     *
     * @param EntityManagerInterface $manager
     * @param Product $product
     * @return Response
     */
    #[Route('/product/suppression/{id}', 'product.delete', methods: ['GET']), isGranted("ROLE_ADMIN")]

    public function delete(
        EntityManagerInterface $manager,
        Product $product
    ): Response {
        $manager->remove($product);
        $manager->flush();

        $this->addFlash(
            'success',
            'Product is successfully deleted !'
        );

        return $this->redirectToRoute('product.index');
    }


    /*=== Search Bar ===*/
    public function searchForm (){
        $form= $this->createFormBuilder()
            ->setAction($this->generateUrl('handleSearch'))
            ->setMethod('POST')
            ->add('text',TextType::class,[

                'attr'=>[
                    'placeholder'=>'Search for products',
                    'required'=>false,
                    'class' => 'form-control'
                ]
            ])
            ->getForm();
        return $this->render('products/searchBar.html.twig',[
            'searchForm'=>$form->createView()
        ]);

    }
    /**
     * @Route("/handleSearch", name="handleSearch")
     * @param Request $request
     */
    public function handleSearch(Request $request, ProductRepository $repo)
    {

        $formValues = $request->get('form');
        $query= $formValues['text'];

        if($query) {
            $Products= $repo->findProductByName(trim($query));

            return $this->render('products/index.html.twig', [
                'products' => $Products
            ]);
        }
    }

}