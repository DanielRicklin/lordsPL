<?php

namespace App\Controller;

use App\Entity\Guild;
use App\Form\GuildType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GuildController extends AbstractController
{
    /**
     * @Route("/guild/new", name="guild_new")
     * @Route("/guild/{id}/modify", name="guild_modify")
     */
    public function index(Guild $guild = null, EntityManagerInterface $manager, Request $request)
    {
        if(!$guild){
            $guild = new Guild();
        }
        $form = $this->createForm(GuildType::class, $guild);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $guild = $form->getData();

            $manager->persist($guild);
            $manager->flush();

            return $this->redirectToRoute('guild_modify', ['id' => $guild->getId()]);
        }

        return $this->render('guild/index.html.twig', [
            'form' => $form->createView(),
            'editMode' => $guild->getId() !== null
        ]);
    }
}
