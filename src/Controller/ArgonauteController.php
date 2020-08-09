<?php

namespace App\Controller;

use App\Entity\Organisation\Argonaute;
use App\Form\ConfirmType;
use App\Form\NouvelArgonauteType;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/argonaute")
 */
class ArgonauteController extends AbstractController
{
    /**
     * @Route("/{argonaute}/resume", name="argo_resume")
     */
    public function Accueil(
        Argonaute $argonaute,
        EntityManagerInterface $em
    )
    {

        return $this->render('argonaute/resume.html.twig', [
            'argonaute' => $argonaute
        ]);

    }

    /**
     * @Route("/{argonaute}/update", name="argo_update")
     * @Template("argonaute/update.html.twig")
     */
    public function Update(
        Argonaute $argonaute,
        Request $request,
        EntityManagerInterface $em
    )
    {
        $form = $this->createForm(NouvelArgonauteType::class, $argonaute);
        $form->add('ok', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            $this->addFlash("success", "Les informations de l'argonaute " . $argonaute . " ont été modifiées.");

            return $this->redirectToRoute('argo_resume', ['argonaute' => $argonaute->getId()]);
        }

        return [
            'argonaute' => $argonaute,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{argonaute}/delete", name="argo_delete")
     * @Template("argonaute/delete.html.twig")
     */
    public function delete(
        Argonaute $argonaute,
        Request $request,
        EntityManagerInterface $em
    )
    {
        $nom = $argonaute->getNom();

        $form = $this->createForm(ConfirmType::class);
        $form->add('ok', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->remove($argonaute);
            $em->flush();
            $this->addFlash("success", $nom . " a été supprimé(e) de la liste des argonautes !");

            return $this->redirectToRoute('home');
        }

        return [
            'form' => $form->createView(),
            'argonaute' => $argonaute
        ];

    }

}