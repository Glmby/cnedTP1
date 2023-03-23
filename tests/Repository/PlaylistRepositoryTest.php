<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\PlaylistTests\Repository;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;


/**
 * Description of PlaylistRepositoryTest
 *
 * @author bouyg
 */
class PlaylistRepositoryTest extends \Symfony\Bundle\FrameworkBundle\Test\KernelTestCase {
         /**
     * @return PlaylistRepository
     */
    public function recupRepository():PlaylistRepository{
    self::bootKernel();
    $playlist=self::getContainer()->get(PlaylistRepository::class);
    return $playlist;
    }
        public function testNbPlaylists(){
        $repository=$this->recupRepository();
        $nbPlaylists=$repository->count([]);
        $this->assertEquals(26,$nbPlaylists);
    }
    public function newPlaylist(): Playlist{
        $playlist=(new Playlist())
                ->setName("Prog orienté objet");
        return $playlist;
    }
        public function testAddPlaylist(){
        $repository=$this->recupRepository();
        $playlist=$this->newPlaylist();
        $nbPlaylists=$repository->count([]);
        $repository->add($playlist,true);
        $this->assertEquals($nbPlaylists+1,$repository->count([]),"erreur lors de l'ajout");
    }
    public function testRemovePlaylist(){
        $repository=$this->recupRepository();
        $playlist=$this->newPlaylist();
        $repository->add($playlist,true);
        $nbPlaylists=$repository->count([]);
        $repository->remove($playlist, true);
        $this->assertEquals($nbPlaylists -1,$repository->count([]),"erreur lors de la suppression");
    }
}
