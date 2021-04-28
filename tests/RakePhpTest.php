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
            "rapid automatic keyword extraction" => 13.333333333333,
            "keyword extraction" => 5.3333333333333,
            "many libraries" => 4.0,
            "difficult" => 1.0,
            "help" => 1.0,
        ];
        
        $stop_words = new English();
        $text = "Keyword extraction is not that difficult after all. There are many libraries that can help you with keyword extraction. Rapid automatic keyword extraction is one of those";
        $keywords = RakePhp::create($stop_words)->extract($text)->sortByScore('desc')->keywords();
        dump($keywords);
        $this->assertEquals($keywords, $_keywords);
    }
}
