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

            $object = $this->findAccessTokenInArrayWithCompanyId($accessToken, "5400a3484700004700fe93c2");
            $this->assertNotNull($object);

            $this->assertEquals(ItwappTest::$itwappApiKey, $object->getApiKey());
            $this->assertEquals(ItwappTest::$itwappApiSecret, $object->getSecretKey());
        }catch(UnauthorizedException $e)    {
            $this->fail($e);
        }catch(Exception $e)    {
            $this->fail($e);
        }
    }

    /**
     * @param $theArray AccessToken[]
     * @param $value string
     * @return null|AccessToken
     */
    private function findAccessTokenInArrayWithCompanyId($theArray, $value){
        foreach($theArray as $arrayItem) {
            if($arrayItem->getCompany() == $value) {
                return $arrayItem;
            }
        }
        return null;
    }

}