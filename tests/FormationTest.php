<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests;

use App\Entity\Formation;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Description of FormationTest
 *
 * @author bouyg
 */
class FormationTest extends TestCase {
    public function testgetPublishedAtString(){
        $formation= new Formation();
        $formation->setPublishedAt(new DateTime("2023-04-14"));
        $this->assertEquals("14/04/2023",$formation->getPublishedAtString());   
}
}
