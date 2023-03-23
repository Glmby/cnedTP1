<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Test\Repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\User\User;

/**
 * Description of UserRepositoryTest
 *
 * @author bouyg
 */
class UserRepositoryTest extends KernelTestCase {
     /**
     * @return UserRepository
     */
    public function recupRepository():UserRepository{
    self::bootKernel();
    $user=self::getContainer()->get(UserRepository::class);
    return $user;
    }
        public function testNbUser(){
        $repository=$this->recupRepository();
        $nbUsers=$repository->count([]);
        $this->assertEquals(0,$nbUsers);
    }
    public function newUser(): User{
        $user=(new User())
                ->setEmail("David@dom.com");
        return $user;
    }
        public function testAddUser(){
        $repository=$this->recupRepository();
        $user=$this->newUser();
        $nbUsers=$repository->count([]);
        $repository->add($user,true);
        $this->assertEquals($nbUsers+1,$repository->count([]),"erreur lors de l'ajout");
    }
    public function testRemoveUser(){
        $repository=$this->recupRepository();
        $user=$this->newUser();
        $repository->add($user,true);
        $nbUsers=$repository->count([]);
        $repository->remove($user, true);
        $this->assertEquals($nbUsers -1,$repository->count([]),"erreur lors de la suppression");
    }
}
