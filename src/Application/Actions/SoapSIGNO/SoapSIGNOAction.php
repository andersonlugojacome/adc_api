<?php

declare(strict_types=1);

namespace App\Application\Actions\SoapSIGNO;

use App\Application\Actions\Action;
use App\Application\Services\SOAPService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

abstract class SoapSIGNOAction extends Action
{
    protected SOAPService $soapService;

    public function __construct(LoggerInterface $logger, SOAPService $soapService)
    {
        parent::__construct($logger);
        $this->soapService = $soapService;
    }

    /**
     * llamadas SOAP
     * @param string $action
     * @param array $params
     * @return array
     */
    protected function callSOAPService(string $action, array $params): array
    {
        try {
            
            $response = $this->soapService->$action(...$params);
            return $response;
        } catch (\Exception $e) {
            $this->logger->error("Error in SOAP call: " . $e->getMessage());
            throw $e;
        }
    }
}
