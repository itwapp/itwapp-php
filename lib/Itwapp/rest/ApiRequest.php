<?php

/**
 * Class ApiRequest, it use to make request to the Itwapp Api.
 */
abstract class ApiRequest {


    /**
     * @param $action
     * @throws InvalidRequestError
     * @throws ResourceNotFoundException
     * @throws ServiceException
     * @throws UnauthorizedException
     * @return mixed
     */
    public static function get($action) {
        $signedRequest = ApiRequest::sign_request("GET", $action);

        $client = new GuzzleHttp\Client();
        $res = $client->request('GET', Itwapp::$apiBase.$signedRequest, [
            'exceptions' => false
        ]);
        return ApiRequest::parse_result($res);
    }

    /**
     * @param $action String the action to call
     * @param $body array, some param to send in http body
     * @throws InvalidRequestError
     * @throws ResourceNotFoundException
     * @throws ServiceException
     * @throws UnauthorizedException
     * @return mixed
     */
    public static function post($action, $body) {
        $signedRequest = ApiRequest::sign_request("POST", $action);

        $client = new GuzzleHttp\Client();
        $res = $client->request('POST', Itwapp::$apiBase.$signedRequest, [
            'json' => $body,
            'exceptions' => false
        ]);

        return ApiRequest::parse_result($res);
    }

    /**
     * @param $action String the action to call
     * @param $body array, some param to send in http body
     * @throws InvalidRequestError
     * @throws ResourceNotFoundException
     * @throws ServiceException
     * @throws UnauthorizedException
     * @return mixed
     */
    public static function put($action, $body) {
        $signedRequest = ApiRequest::sign_request("PUT", $action);

        $client = new GuzzleHttp\Client();
        $res = $client->request('PUT', Itwapp::$apiBase.$signedRequest, [
            'json' => $body,
            'exceptions' => false
        ]);

        return ApiRequest::parse_result($res);
    }

    /**
     * @param $action String the action to call
     * @throws InvalidRequestError
     * @throws ResourceNotFoundException
     * @throws ServiceException
     * @throws UnauthorizedException
     * @return mixed
     */
    public static function delete($action) {
        $signedRequest = ApiRequest::sign_request("DELETE", $action);

        $client = new GuzzleHttp\Client();
        $res = $client->request('DELETE', Itwapp::$apiBase.$signedRequest, [
            'exceptions' => false
        ]);

        return ApiRequest::parse_result($res);
    }

    /**
     * @param $res Psr\Http\Message\ResponseInterface
     * @throws InvalidRequestError
     * @throws ResourceNotFoundException
     * @throws ServiceException
     * @throws UnauthorizedException
     * @return mixed
     */
    private static function parse_result($res)  {
        if($res->getStatusCode() == 200)    {
            return json_decode($res->getBody(), true);
        }else{
            switch($res->getStatusCode())   {
                case 401 :
                    throw new UnauthorizedException( $res->getBody() );
                    break;
                case 400 :
                    throw new InvalidRequestError( $res->getBody() );
                    break;
                case 404 :
                    throw new ResourceNotFoundException( $res->getBody() );
                    break;
                case 503 :
                case 500 :
                default :
                    throw new ServiceException( $res->getBody() );

            }
        }
    }

    /**
     * @param $mode
     * @param $action
     * @return string
     */
    private static function sign_request($mode, $action)   {
        $url = $action;
        if (strpos($url,'?') !== false) {
            $url .= "&apiKey=".\Itwapp::$apiKey;
        }else{
            $url .= "?apiKey=".\Itwapp::$apiKey;
        }
        $milliseconds = round(microtime(true) * 1000);
        $url .= "&timestamp=".strval( $milliseconds );
        $signature = Sign::encode($mode.":".$url, Itwapp::$secretKey);
        return $url . "&signature=".$signature;
    }


} 
