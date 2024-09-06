<?php

declare(strict_types=1);

namespace App\Application\Actions\SoapSIGNO;

use App\Application\Actions\Action;
use App\Application\Services\SOAPService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

class ObtenerEscriturasAction extends Action
{
    private SOAPService $soapService;

    public function __construct(LoggerInterface $logger)
    {
        parent::__construct($logger);

        // Configurar el servicio SOAP
        $wsdl = 'https://signo-bpm.avance.org.co/BPM/signo/webService/wsdl.php?WSDL';
        $login = "pruebasbpm62/8whkoDOrFk"; // Este es de tipo codigonotarial/usuarioquecreasteWS
        $password = "8whkoDOrFk$$4";

        // Crear una instancia del servicio SOAP
        $this->soapService = new SOAPService($wsdl, $login, $password);
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        // Obtener los parámetros de la consulta (query params) en lugar de usar resolveArg
    $queryParams = $this->request->getQueryParams();

    $numeroradicado = $queryParams['numeroradicado'] ?? '';
    $numeroescritura = $queryParams['numeroescritura'] ?? '';
    $annoescritura = $queryParams['annoescritura'] ?? null;
    $tipodocumentootorgante = $queryParams['tipodocumentootorgante'] ?? '';
    $identificacionotorgante = $queryParams['identificacionotorgante'] ?? '';
    $nombresotorgante = $queryParams['nombresotorgante'] ?? '';
    $apellidosotorgante = $queryParams['apellidosotorgante'] ?? '';
    $razonsocialotorgante = $queryParams['razonsocialotorgante'] ?? '';
    $tipofactura = $queryParams['tipofactura'] ?? '';
    $numerofactura = $queryParams['numerofactura'] ?? '';

        // Validar que al menos un campo esté presente
        if ( empty($numeroradicado)) {
            throw new HttpBadRequestException($this->request, 'Se requiere al menos un campo para realizar la búsqueda');
        }

        // Parámetros para la solicitud SOAP
        $params = [
            'numeroradicado' => $numeroradicado ?: '',
            'numeroescritura' => $numeroescritura ?: '',
            'annoescritura' => $annoescritura ?: '',
            'tipodocumentootorgante' => $tipodocumentootorgante ?: '',
            'identificacionotorgante' => $identificacionotorgante ?: '',
            'nombresotorgante' => $nombresotorgante ?: '',
            'apellidosotorgante' => $apellidosotorgante ?: '',
            'razonsocialotorgante' => $razonsocialotorgante ?: '',
            'tipofactura' => $tipofactura ?: '',
            'numerofactura' => $numerofactura ?: '',
        ];

        // Llamar al servicio SOAP para obtener escrituras
        try {
            $result = $this->soapService->obtenerEscrituras($params);
            // Devolver la respuesta
            return $this->respondWithData($result);
        } catch (\Exception $e) {
            return $this->respondWithData(array('estado' => 0, 'mensaje' => $e->getMessage()));
        }
    }
}
