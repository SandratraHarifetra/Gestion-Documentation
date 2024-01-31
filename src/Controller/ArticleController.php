<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_article")
     */
    public function index(ArticleRepository $ArticleRepository): Response
    {
        $liste = $ArticleRepository->findAll();
        //dd($liste);
        return $this->render('article/index.html.twig', [
            'article' => $liste,
            'controller_name' => 'ArticleController'
        ]);
    }

    /**
     * @Route("/new", name="app_article_new", methods={"GET", "POST"})
     */

     public function new(Request $request,EntityManagerInterface $manager ,ArticleRepository $ArticleRepository): Response
     {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
            //dd($form);
            //$filename=$article->getPhoto();
            //dd($file);
            // // $file=$article->getJacket()->getClientOriginalName();
            // //dd($file);
            // $extension = $file->guessExtension();
            // //dd($extension);
            // //$article=$this->getId();
            // //dd($nomphoto);
            // $name=$article->getJacket()->getClientOriginalName();
            // $filename = $name.'.'.$extension;
            // //dd($filename);
            
            //$filename->copy($this->getParameter('depot_image'),$filename);
            //dd('ok');
            // $test = $file->getClientOriginalName();
            // //dd($test);
            // $article->setPhoto();
            //$article->setPhoto($filename);
            $article->setDatepublication(new \Datetime());
            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('app_article', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig',[
            'form' => $form
        ]);
     }


    /**
     * @Route("/{id}", name="app_article_show", methods={"GET"})
     */
    public function show(ArticleRepository $ArticleRepository,$id): Response
    {
        return $this->render('article/show.html.twig',[
            'article' => $ArticleRepository->findBy(['id' => $id])
            
        ]);
    }

    /**
     * @Route("/search", name="app_article_search", methods={"POST"})
     */
    public function search(Request $request, ArticleRepository $ArticleRepository)
    {
        if($request->isMethod('POST')){
            $mots_cles=$request->get('mots-cles');
            //dd($mots_cles);
            $article = $ArticleRepository->Searchd($mots_cles);
            //dd($article);
            $chiffres=count($article);

            //dd($chiffres);
        }
        return $this->renderForm('article/search.html.twig',[
            'article' => $article,
            'chiffres' => $chiffres
        ]);
    }
    /**
     * @Route("{id}/edit", name="app_article_edit", methods={"GET","POST"})
     */
    public function edit(Article $article, Request $request, ArticleRepository $ArticleRepository,$id): Response
    {
            $form = $this->createForm(ArticleType::class,$article);
            $form -> handleRequest($request);
            
            if($form->isSubmitted() && $form->isValid()){
                $ArticleRepository->add($article);
                return $this->redirectToRoute('app_article', [], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('article/edit.html.twig',[
                'article' => $article,
                'form' => $form,
            ]);
    }
}
