<?php

class SignTest extends PHPUnit_Framework_TestCase {

    public function testEncode()    {
        $signature = Sign::encode("", "KEY");
        $this->assertEquals("405b4e6c5e0370034caa8d9261be1819", $signature);

        $signature = Sign::encode("", "a");
        $this->assertEquals("025b69987847314c3445f5cdb5c23830", $signature);
    }

}
 