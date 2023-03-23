<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\CategorieTests\Repository;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of CategorieRepositoryTest
 *
 * @author bouyg
 */
class CategorieRepositoryTest extends KernelTestCase {
             /**
     * @return CategorieRepository
     */
    public function recupRepository():CategorieRepository{
    self::bootKernel();
    $categorie=self::getContainer()->get(CategorieRepository::class);
    return $categorie;
    }
        public function testNbCategories(){
        $repository=$this->recupRepository();
        $nbCategories=$repository->count([]);
        $this->assertEquals(9,$nbCategories);
    }
    public function newCategorie(): Categorie{
        $categorie=(new Categorie())
                ->setName("Prog orienté objet");
        return $categorie;
    }
        public function testAddCategorie(){
        $repository=$this->recupRepository();
        $categorie=$this->newCategorie();
        $nbCategories=$repository->count([]);
        $repository->add($categorie,true);
        $this->assertEquals($nbCategories+1,$repository->count([]),"erreur lors de l'ajout");
    }
    public function testRemoveCategorie(){
        $repository=$this->recupRepository();
        $categorie=$this->newCategorie();
        $repository->add($categorie,true);
        $nbCategories=$repository->count([]);
        $repository->remove($categorie, true);
        $this->assertEquals($nbCategories -1,$repository->count([]),"erreur lors de la suppression");
    }
}
