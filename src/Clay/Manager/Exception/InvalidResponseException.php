<?php

namespace Clay\Manager\Exception;

use Symfony\Component\HttpFoundation\Response;

class InvalidResponseException extends \Exception
{
    /**
     * @var Response
     */
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
        parent::__construct('Invalid response', 422);
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

}
