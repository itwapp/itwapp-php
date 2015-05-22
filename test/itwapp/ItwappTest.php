<?php

class ItwappTest extends PHPUnit_Framework_TestCase {

    private static $mail;
    private static $password;

    private static $itwappApiKey;
    private static $itwappApiSecret;

    static function setUpBeforeClass()  {
        ItwappTest::$mail = getenv("itwappMail");
        ItwappTest::$password = getenv("itwappPassword");

        ItwappTest::$itwappApiKey = getenv("itwappApiKey");
        ItwappTest::$itwappApiSecret = getenv("itwappApiSecret");
    }

    public function testAuth()  {
        $this->assertNotNull(ItwappTest::$mail);
        $this->assertNotNull(ItwappTest::$password);

        try {
            $accessToken = Itwapp::Authenticate(ItwappTest::$mail, ItwappTest::$password);

            $this->assertEquals(ItwappTest::$itwappApiKey, $accessToken->getApiKey());
            $this->assertEquals(ItwappTest::$itwappApiSecret, $accessToken->getSecretKey());
        }catch(UnauthorizedException $e)    {
            $this->fail($e);
        }catch(Exception $e)    {
            $this->fail($e);
        }
    }

}