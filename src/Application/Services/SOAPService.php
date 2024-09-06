<?php

declare(strict_types=1);

namespace App\Application\Services;

use SoapClient;
use SoapHeader;
use Throwable;

class SOAPService
{
    private string $wsdl;
    private string $login;
    private string $password;
    private WSSoapClient $client;

    public function __construct(string $wsdl, string $login, string $password)
    {
        $this->wsdl = $wsdl;
        $this->login = $login;
        $this->password = $password;

        // Opciones para conexión SOAP
        $opts = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];

        $params = [
            'encoding' => 'UTF-8',
            'verifypeer' => false,
            'verifyhost' => false,
            'soap_version' => SOAP_1_2,
            'trace' => 1,
            'exceptions' => 1,
            'connection_timeout' => 180,
            'stream_context' => stream_context_create($opts),
        ];

        // Crear el cliente SOAP
        $this->client = new WSSoapClient($this->wsdl, $params);
        $this->client->__setUsernameToken($login, hash("sha256", $password), "PasswordDigest");
    }

    // Método para obtener firmas registradas
    public function obtenerFirmasRegistradas(array $params)
    {
        try {
            // Llamada SOAP al método ObtenerFirmasRegistradas
            $response = $this->client->__soapCall('ObtenerFirmasRegistradas', [$params]);

            // Verifica si respuesta es una cadena JSON y la decodifica a un array o objeto
            if (is_string($response->respuesta)) {
                $responseObject = json_decode($response->respuesta);
            } else {
                $responseObject = $response->respuesta;
            }
            return $responseObject;
        } catch (Throwable $e) {
            throw new \Exception('Error al ejecutar SOAP 1: ' . $e->getMessage());
        }
    }

    public function obtenerEscrituras(array $params)
    {
        try {
            // Llamada SOAP al método ObtenerEscrituras
            $response = $this->client->__soapCall('ObtenerEscrituras', [$params]);
            // Verifica si respuesta es una cadena JSON y la decodifica a un array o objeto
            if (is_string($response->respuesta)) {
                $responseObject = json_decode($response->respuesta);
            } else {
                $responseObject = $response->respuesta;
            }
            return $responseObject;
        } catch (\Throwable $e) {
            throw new \Exception('Error al ejecutar SOAP: ' . $e->getMessage());
        }
    }
}
