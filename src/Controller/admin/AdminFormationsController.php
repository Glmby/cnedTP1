<?php

namespace App\Controller\admin;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminFormationsController
 *
 * @author bouyg
 */
 class AdminFormationsController extends AbstractController {
    const PAGE_FORMATION="admin/admin.formations.html.twig";
    /**
     * 
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
    }
    
    /**
     * @Route("/admin", name="admin.formations")
     * @return Response
     */
    public function index(): Response{
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_FORMATION, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    } 
    /**
     * @Route("/admin/suppr/{id}",name="admin.formation.suppr")
     * @param formation $formation
     * @return Response
     */
    public function suppr(formation $formation):Response{
        $this->formationRepository->remove($formation,true);
        return $this->redirectToroute('admin.formations');
    }
    /**
     * @Route("/admin/edit/{id}", name="admin.formation.edit")
     * @param Formation $formation
     * @param Request $request
     * @return Response
     */
    public function edit(Formation $formation,Request $request):Response{
        $formFormation=$this->createForm(FormationType::class,$formation);
        
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->formationRepository->add($formation, true);
            return $this->redirectToRoute('admin.formations');
        }
        return $this->render("admin/admin.formation.edit.html.twig",[
            'formation'=>$formation,
            'formFormation'=>$formFormation->createView()
        ]);
    }
}
