<?php


namespace App\Tests\Validations;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FormationValidationsTest extends KernelTestCase {
    public function getFormation(): Formation{
        return (new Formation())
                ->setTitle("C# POO");
                
    }
    public function testValidDateFormation(){
        $dateString = '2023-02-27';
        $date = \DateTime::createFromFormat('Y-m-d', $dateString);
        $formation=$this->getFormation()->setPublishedAt($date);
        self::bootKernel();
        $this->assertErrors($formation,0);
        
    }
    public function assertErrors(Formation $formation,int $nbErreursAttendues, string $message=""){
        self::bootKernel();
        $validator=self::getContainer()->get(ValidatorInterface::class);
        $error=$validator->validate($formation);
        return $this->assertCount($nbErreursAttendues, $error, $message);
}
    public function testNonValidDateFormation(){
        $dateString = date('2023-06-24');
        $date = \DateTime::createFromFormat('Y-m-d', $dateString);
        $formation=$this->getFormation()->setPublishedAt($date);
        self::bootKernel();
        $this->assertErrors($formation,1,"la date est posterieur donc devrait echouer");
    }
}
