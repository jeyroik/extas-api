<?php
namespace extas\components\routes\descriptions;

use extas\components\routes\descriptions\THasApiDescription;
use Psr\Http\Message\ResponseInterface;

/**
 * @method array formatResponseData(array $in, array $out)
 */
trait TRouteHelp
{
    use THasApiDescription;

    public function help(): ResponseInterface
    {
        $in = $this->getInputDescription(true);
        $out = $this->getOutputDescription(true);

        $this->setResponseData($this->formatResponseData($in, $out));

        return $this->response;
    }
}
