<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\FormationTests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of VisiteRepositoryTest
 *
 * @author bouyg
 */
class FormationRepositoryTest extends KernelTestCase {
     /**
     * @return FormationRepository
     */
    public function recupRepository():FormationRepository{
    self::bootKernel();
    $repository=self::getContainer()->get(FormationRepository::class);
    return $repository;
    }
        public function testNbFormations(){
        $repository=$this->recupRepository();
        $nbFormations=$repository->count([]);
        $this->assertEquals(227,$nbFormations);
    }
    public function newFormation(): Formation{
        $formation=(new Formation())
                ->setTitle("POO avancée");
        return $formation;
    }
        public function testAddFormation(){
        $repository=$this->recupRepository();
        $formation=$this->newFormation();
        $nbFormation=$repository->count([]);
        $repository->add($formation,true);
        $this->assertEquals($nbFormation+1,$repository->count([]),"erreur lors de l'ajout");
    }
    public function testRemoveFormation(){
        $repository=$this->recupRepository();
        $formation=$this->newFormation();
        $repository->add($formation,true);
        $nbFormation=$repository->count([]);
        $repository->remove($formation, true);
        $this->assertEquals($nbFormation -1,$repository->count([]),"erreur lors de la suppression");
    }

}
