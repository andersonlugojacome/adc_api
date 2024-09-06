<?php

declare(strict_types=1);

namespace App\Application\Actions\SoapSIGNO;

use App\Application\Actions\Action;
use App\Application\Services\SOAPService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

use function DI\string;

class ObtenerFirmasRegistradasAction extends Action
{
    private SOAPService $soapService;

    public function __construct(LoggerInterface $logger)
    {
        parent::__construct($logger);

        // Crear el servicio directamente en el constructor
        $wsdl = 'https://www.signo-cloud.co/signo/webService/wsdl.php?WSDL';
        $login = 'CS2rr1t4/8whkoDOrFk';
        $password = '';
        // $wsdl = 'https://signo-bpm.avance.org.co/BPM/signo/webService/wsdl.php?WSDL';
        // $login = "pruebasbpm62/8whkoDOrFk"; // Este es de tipo codigonotarial/usuarioquecreasteWS
        // $password = "8whkoDOrFk$$4";

        // Crear una nueva instancia de SOAPService
        $this->soapService = new SOAPService($wsdl, $login, $password);
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        // Obtener los parámetros de la consulta (query params) en lugar de usar resolveArg
        $queryParams = $this->request->getQueryParams();
        $estado = $queryParams['estado'] ?? '';
        $indice = $queryParams['indice'] ?? '';
        $tipodocumento = $queryParams['tipodocumento'] ?? '';
        $numerodocumento = $queryParams['numerodocumento'] ?? '';
        $nombre = $queryParams['nombre'] ?? '';
        $apellidos = $queryParams['apellidos'] ?? '';
        // Parámetros para la solicitud SOAP ObternerFirmasRegistradas
        $params = [
            'estado' => $estado ?: 'V',
            'indice' => $indice ?: (int)'',
            'tipodocumento' => $tipodocumento ?: (int)'0',
            'numerodocumento' => $numerodocumento ?: '',
            'nombre' => $nombre ?: '',
            'apellidos' => $apellidos ?: '',
        ];
        // Llamar al servicio SOAP para obtener firmas registradas
        try {
            $result = $this->soapService->obtenerFirmasRegistradas($params);

            // Devolver la respuesta SOAP
            return $this->respondWithData($result);
        } catch (\Exception $e) {
            return $this->respondWithData(array('estado' => 0, 'mensaje' => $e->getMessage()));
        }
    }
}
