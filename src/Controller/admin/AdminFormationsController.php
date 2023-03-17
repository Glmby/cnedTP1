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
    const PAGE_ADMINFORMATION="admin/admin.formations.html.twig";
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
        return $this->render(self::PAGE_ADMINFORMATION, [
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
        /**
     * @Route("/admin/tri/{champ}/{ordre}/{table}", name="Admin.formations.sort")
     * @param type $champ
     * @param type $ordre
     * @param type $table
     * @return Response
     */
    public function sort($champ, $ordre, $table=""): Response{
        if($table==""){
            $formations = $this->formationRepository->findAllOrderByInFormation($champ, $ordre);
        } else {
            $formations = $this->formationRepository->findAllOrderByInRelatedTable($champ, $ordre, $table);
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_ADMINFORMATION, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    } 
     /**
     * @Route("/admin/recherche/{champ}/{table}", name="Admin.formations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        if ($table == "") {
            $formations = $this->formationRepository->findByContainValueInFormation($champ, $valeur);
        } else {
            $formations = $this->formationRepository->findByContainValueInRelatedTable($champ, $valeur, $table);
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_ADMINFORMATION, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  
        /**
     * @Route("/admin/ajout", name="admin.formation.ajout")
     * @param Formation $formation
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request):Response{
        $formation= new formation();
        $formFormation=$this->createForm(FormationType::class,$formation);
        
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->formationRepository->add($formation, true);
            return $this->redirectToRoute('admin.formations');
        }
        return $this->render("admin/admin.formation.ajout.html.twig",[
            'formation'=>$formation,
            'formFormation'=>$formFormation->createView()
        ]);
    }
}
