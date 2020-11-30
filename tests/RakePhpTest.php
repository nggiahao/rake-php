<?php

namespace Nggiahao\RakePhp\Tests;

use Nggiahao\RakePhp\RakePhp;
use Nggiahao\RakePhp\StopWords\English;
use PHPUnit\Framework\TestCase;

class RakePhpTest extends TestCase
{
    /** @test */
    public function true_is_true()
    {
        $_keywords = [
            "linear diophantine equations" => 9.0,
            "minimal generating sets" => 8.5,
            "minimal set" => 4.5,
            "strict inequations" => 4.0,
            "nonstrict inequations" => 4.0,
            "upper bounds" => 4.0,
            "criteria" => 1.0,
            "compatibility" => 1.0,
            "considered" => 1.0,
            "components" => 1.0,
            "solutions" => 1.0,
            "algorithms" => 1.0,
            "construction" => 1.0,
            "types" => 1.0,
            "systems" => 1.0,
            "given" => 1.0,
        ];
        
        $stop_words = new English();
        $text = "The Criteria of compatibility of a system of linear Diophantine equations, strict inequations, and nonstrict inequations are considered the.
         Upper bounds for components of a minimal set of solutions and algorithms of construction of minimal generating sets of solutions for all types of systems are given.";
        $keywords = RakePhp::create($stop_words)->extract($text)->sortByScore('desc')->keywords();
        
        $this->assertEquals($keywords, $_keywords);
    }
}
