<?php

namespace App\Controller;
use App\Entity\Organisation\Argonaute;
use App\Form\NouvelArgonauteType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function Accueil(
        EntityManagerInterface $em
    )
    {
        $argonaute = new Argonaute();
        $form = $this->CreateBaseForm($argonaute);


        return $this->render('home/accueil.html.twig',[
            'argonautes' => $em->getRepository(Argonaute::class)->findAll(),
            'argonaute' => $argonaute,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/create", name="create")
     * @Template("home/create.html.twig")
     */
    public function Create(
        Request $request,
        EntityManagerInterface $em
    )
    {
        $argonaute = new Argonaute();
        $form = $this->CreateBaseForm($argonaute);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($argonaute);
            $em->flush();

            $argonautes = $em->getRepository(Argonaute::class)->findAll();
            $fiersGuerriers = (count($argonautes)<=1) ? " fier(e) guerrier(e)." : " fier(e)s guerrier(e)s.";
            $this->addFlash("success", "L'argonaute " . $argonaute . " est enregistré. Votre équipe compte désormais " .count($argonautes) .$fiersGuerriers);

            return $this->redirectToRoute('home');
        }

        return [
            'argonaute' => $argonaute,
            'form' => $form->createView()
        ];
    }

    protected function CreateBaseForm(
        Argonaute $argonaute
    )
    {
        $form = $this->createForm(NouvelArgonauteType::class, $argonaute, [
            'action' => $this->generateUrl('create')
        ]);
        $form->add('ok', SubmitType::class);

        return $form;


    }
}