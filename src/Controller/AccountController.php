<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Account;
use App\Form\AccountType;
use App\Service\FileUploader;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    /**
     * @Route("/account/{id}", name="account_show", requirements={"id":"\d+"})
     */
    public function index(Account $account)
    {
        return $this->render('account/index.html.twig', [
            'account' => $account
        ]);
    }

    /**
     * @Route("/account/new", name="account_new")
     * @Route("/account/{id}/modify", name="account_modify", requirements={"id":"\d+"})
     */
    public function form(Account $account = null, EntityManagerInterface $manager, FileUploader $fileUploader, Request $request, ImageRepository $image_repo)
    {
        if(!$account){
            $account = new Account();
        }

        $form = $this->createForm(AccountType::class, $account);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $images = $form->get('images')->getData();

            if ($images) {
                foreach($images as $img){
                    $filename = $fileUploader->upload($img);
                    $image = new Image();
                    $image->setAccount($account);
                    $image->setLink('/images/'.$filename);
                    $manager->persist($image);
                }
            }
            $account->setDate(new \DateTime);

            $manager->persist($account);
            $manager->flush();

            // return $this->redirectToRoute('account_show', ['id' => $account->getId()]);
            return $this->redirectToRoute('home');
        }

        return $this->render('account/create.html.twig', [
            'formAccount' => $form->createView(),
            'images' => $image_repo->findby(['account'=>$account]),
            'editMode' => $account->getId() !== null
        ]);
    }

    /**
     * @Route("/image/{id}/delete", name="account_image_delete")
     */
    public function delete_delete(EntityManagerInterface $manager, Image $image)
    {

        $manager->remove($image);
        $manager->flush();

        dump(getcwd());

        return $this->redirectToRoute('account_modify', ['id' => $image->getAccount()->getId()]);
    }
}
