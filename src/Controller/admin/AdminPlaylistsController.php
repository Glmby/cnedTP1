<?php

namespace App\Controller\admin;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminPlaylistsController
 *
 * @author bouyg
 */
class AdminPlaylistsController extends AbstractController {
    const PAGE_PLAYLIST="admin/admin.playlists.html.twig";
    /**
     * 
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
    /**
     * 
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * 
     * @var CategorieRepository
     * @var FormationRepository
     */
    private $categorieRepository;    
    
    function __construct(PlaylistRepository $playlistRepository, 
            CategorieRepository $categorieRepository,
            FormationRepository $formationRepository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
    }
    
    /**
     * @Route("/admin/playlist", name="admin.playlists")
     * @return Response
     */
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_PLAYLIST, [
            'playlists' => $playlists,
            'categories' => $categories,
        ]);
    }
        /**
     * @Route("/admin/playlist/suppr/{id}",name="admin.playlist.suppr")
     * @param playlist $playlist
     * @return Response
     */
    public function suppr(Playlist $playlist):Response{
        
    $formations = $playlist->getFormations();
    if (count($formations) > 0) {
        $this->addFlash('error', 'La playlist ne peut pas être supprimée car il y a des formations rattachées à elle.');
        return $this->redirectToRoute('admin.playlists');
    }

        $this->playlistRepository->remove($playlist,true);
        return $this->redirectToroute('admin.playlists');
    }
     /**
 * @Route("admin/playlists/tri/{champ}/{ordre}", name="admin.playlist.sort")
 * @param type $champ
 * @param type $ordre
 * @return Response
 */
    public function sort($champ, $ordre): Response{
        switch($champ){
        case "name":
        $playlists = $this->playlistRepository->findAllOrderByName($ordre);
    break;
        case "nbformations":
        $playlists = $this->playlistRepository->findAllOrderByNbFormations($ordre);
    break;
}
        $categories = $this->categorieRepository->findAll();
    return $this->render(self::PAGE_PLAYLIST, [
        'playlists' => $playlists,
        'categories' => $categories]);
 }   
     /**
     * @Route("admin/playlists/recherche/{champ}/{table}", name="admin.playlists.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table = ""): Response
    {
        $valeur = $request->get("recherche");
        if ($table == "categories") {
            $playlists = $this->playlistRepository->findByContainValueInCategories($champ, $valeur);
        }else {
            $playlists = $this->playlistRepository->findByContainValueInPlaylist($champ, $valeur);
    }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_PLAYLIST, [
        'playlists' => $playlists,
        'categories' => $categories,
        'valeur' => $valeur,
        'table' => $table
        ]);
    }
      /**
     * @Route("/admin/playlist/edit/{id}", name="admin.playlist.edit")
     * @param Playlist $playlist
     * @param Request $request
     * @return Response
     */
    public function edit(Playlist $playlist, Request $request):Response{
        $formPlaylist=$this->createForm(PlaylistType::class, $playlist);
        $formations =$this->formationRepository->findBy(['playlist' => $playlist]);
                $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
            $this->playlistRepository->add($playlist, true);
            return $this->redirectToRoute('admin.playlists');
        }
        
        return $this->render("admin/admin.playlist.edit.html.twig",[
            'playlist'=>$playlist,
            'formPlaylist'=>$formPlaylist->createView(),
            'formations' => $formations  
        ]);
    }
          /**
     * @Route("/admin/playlist/ajout", name="admin.playlist.ajout")
     * @param Playlist $playlist
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request):Response{
        $playlist= new Playlist();
        $formPlaylist=$this->createForm(PlaylistType::class, $playlist);
        
                $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
            $this->playlistRepository->add($playlist, true);
            return $this->redirectToRoute('admin.playlists');
        }
        
        return $this->render("admin/admin.playlist.ajout.html.twig",[
            'playlist'=>$playlist,
            'formPlaylist'=>$formPlaylist->createView()
        ]);
    }
}