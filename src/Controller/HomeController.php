<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\SearchType;
use Doctrine\ORM\Query\Expr;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PaginatorInterface $paginator, EntityManagerInterface $em, AccountRepository $account_repo, Request $request)
    {
        $form = $this->createForm(SearchType::class);


        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $research = $form->getData();

            dump($research['field'], $account_repo->findBySearch($research['field']));
            
            $pagination = $paginator->paginate(
                $account_repo->findBySearch($research['field']),
                $request->query->getInt('page', 1),
                12
            );

            $pagination->setCustomParameters([
                'align' => 'center'
            ]);

            // return $this->redirectToRoute('home'); //, ['id' => $guild->getId()]
            return $this->render('home/index.html.twig', [
                'form' => $form->createView(),
                'pagination' => $pagination,
            ]);
        }

        $pagination = $paginator->paginate(
            $account_repo->findAll(),
            $request->query->getInt('page', 1),
            12
        );

        $pagination->setCustomParameters([
            'align' => 'center'
        ]);
        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }
}
