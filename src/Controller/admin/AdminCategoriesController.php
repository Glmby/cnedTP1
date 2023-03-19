<?php

namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Form\FormationType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Formation;
/**
 * Description of AdminCategoriesController
 *
 * @author bouyg
 */
class AdminCategoriesController extends AbstractController {
        const PAGE_ADMINCATEGORIE="admin/admin.categories.html.twig";
    /**
     * 
     * @var CategorieRepository
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
     * @Route("/admin/categorie", name="admin.categories")
     * @return Response
     */
    public function index(): Response{
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_ADMINCATEGORIE, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    } 
    
    public function categories(CategorieRepository $categorieRepository)
    {
        $categories=$categorieRepository->findAll();
        return $this->render(self::PAGE_ADMINCATEGORIE, [
            'categories'=>$categories
        ]);
    }
        /**
     * @Route("/admin/categorie/suppr/{id}",name="admin.categorie.suppr")
     * @param categorie $categorie
     * @return Response
     */
    public function suppr(categorie $categorie):Response{
    if ($this->hasAssociatedFormations($categorie)) {
        $this->addFlash('error', "La catégorie ne peut pas être supprimée car elle est liée à des formations.");
        return $this->redirectToRoute('admin.categories');
    }
        $this->categorieRepository->remove($categorie,true);
        return $this->redirectToroute('admin.categories');
    }
            /**
     * @Route("/admin/categorie/ajout", name="admin.categorie.ajout")
     * @param categorie $categorie
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request):Response{
        $nomCategorie = $request->request->get('name');
         if ($this->categoryNameExists($nomCategorie)) {
        $this->addFlash('error', "Une catégorie avec ce nom existe déjà.");
        return $this->redirectToRoute('admin.categories');
         }
        $categorie = new Categorie();
        $categorie->setName($nomCategorie);
        $this->categorieRepository->add($categorie, true);
    return $this->redirectToRoute('admin.categories');
}
private function hasAssociatedFormations(Categorie $categorie)
{
    $formations = $categorie->getFormations();
    return count($formations) > 0;
}
private function categoryNameExists($name)
{
    $existingCategory = $this->categorieRepository->findOneBy(['name' => $name]);
    return $existingCategory !== null;
}



}
