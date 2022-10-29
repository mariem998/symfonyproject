<?php

namespace App\Controller;

use App\Entity\Editor;
use App\Form\EditorType;
use Doctrine\Persistence\ManagerRegistry;
//use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


#[Route("editor")]
class EditorController extends AbstractController
{
    #[Route('/', name:'editor.list')]
    public function index(ManagerRegistry $doctrine):Response{
        $repository = $doctrine->getRepository(Editor::class);
        $editors= $repository->findAll();
        return $this->render('editors/index.html.twig',['editors'=> $editors]);
    }

    #[Route('/alls/{page?1}/{nbre?12}', name:'editor.list.alls')]
    public function indexAlls(ManagerRegistry $doctrine, $page, $nbre):Response{
        $repository = $doctrine->getRepository(Editor::class);
        $nbEditor= $repository->count([]);
        $nbrePage=ceil($nbEditor / $nbre);

        $editors= $repository->findBy([],[], $nbre, ($page -1)* $nbre);

        return $this->render('editors/index.html.twig',[
            'editors'=> $editors,
            'isPaginated'=> true,
            'nbrePage'=>$nbrePage,
            'page'=>$page,
            'nbre'=>$nbre]);
    }


    #[Route('/{id<\d+>}', name: 'editor.detail')]
    public function detail( Editor $editor= null):Response{

        if(!$editor){
            $this->addFlash('error', "La personne d'id n'existe pas ");
            return $this->redirectToRoute('editor.detail');
        }
        return $this->render('editors/detail.html.twig',['editors'=> $editor]);

    }
//********************************************
    #[Route('/add', name: 'editor.add')]
    public function addEditor(ManagerRegistry $doctirine,Request $request):Response{

        $editor =new Editor();
        $form=$this->createForm(EditorType::class, $editor);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager = $doctirine->getManager();
            $manager->persist($editor);
            $manager->flush();
            $this->addFlash($editor->getFirstname(),"Ajouté avec succes");
            return $this->redirectToRoute('editor.list.alls');

        }else{
        return $this->render('editors/add-editor.html.twig',[
            'form' => $form->createView()
        ]);
    }
    }

    #[Route('/edit/{id?0}', name: 'editor.edit')]
    public function EditEditor(Editor $editor =null, ManagerRegistry $doctirine, Request $request, $id): Response
    {
        if(!$editor)
        {
            $new=true;
            $editor = new Editor();
        }
        $form=$this->createForm(EditorType::class, $editor);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager = $doctirine->getManager();
            $manager->persist($editor);
            $manager->flush();
            $new=false;
            if($new){
                $message ="a ete ajouté avec succés";
            }else{
                $message ="a ete mis a jour avec succés";
            }

            $this->addFlash($editor->getFirstname(), $message);
            return $this->redirectToRoute('editor.list.alls');

        }else{
            return $this->render('editors/add-editor.html.twig',[
                'form' => $form->createView()
            ]);
        }
    }
//********************************************



#[Route('/delete/{id}', name: 'editor.delete')]
    public function deleteEditor(Editor $editor = null, ManagerRegistry $doctrine): RedirectResponse{
        //Récupérer la personne
        if ($editor) {
        // si existe=> supprime et retourner notification success
            $manager = $doctrine->getManager();
            $manager->remove($editor);
            $manager->flush();
            $this->addFlash('success', "L'Editor' a été supprimé avec succès");
            //sinn retourner notif d'erreur

    }else{
            //sinn retourner notif d'erreur
            $this->addFlash('error', "Editor innexistant");
        }
        return $this->redirectToRoute('editor.list.alls');
    }

    #[Route('/update/{id}/{firstname}/{email}', name: 'editor.update')]
    public function updateEditor(Editor $editor = null, ManagerRegistry $doctrine, $firstname, $email) {
        //Vérifier que l'editeur' à mettre à jour existe
        if ($editor) {
            // Si l'editors  existe => mettre a jour notre editors + message de succes
            $editor->setFirstname($firstname);
            $editor->setEmail($email);

            $manager = $doctrine->getManager();
            $manager->persist($editor);

            $manager->flush();
            $this->addFlash('success', "L'editors' a été mis à jour avec succès");
        }  else {
            //Sinon  retourner un flashMessage d'erreur
            $this->addFlash('error', "Editor innexistante");
        }
        return $this->redirectToRoute('editor.list.alls');
    }
}
