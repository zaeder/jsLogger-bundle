<?php

namespace Zaeder\JsLoggerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class JsController extends Controller {
    public function errorListenerScriptAction()
    {
        $url = $this->get('router')->generate('zaeder_js_logger_log');
        $params = array(
            'url' => $url
        );
        $rendered = $this->renderView( 'ZaederJsLoggerBundle:Js:error.js.twig', $params );
        return $this->getJsResponse($rendered);
    }
    
    public function consoleScriptAction()
    {
        $url = $this->get('router')->generate('zaeder_js_logger_log');
        $autologMethods = json_encode($this->get('zaeder_js_logger.logger')->getMethodsToAutolog());
        $params = array(
            'url' => $url,
            'autologMethods' => $autologMethods
        );
        $rendered = $this->renderView( 'ZaederJsLoggerBundle:Js:console.js.twig', $params );
        return $this->getJsResponse($rendered);
    }
    
    public function loggerScriptAction()
    {
        $url = $this->get('router')->generate('zaeder_js_logger_log');
        $params = array(
            'url' => $url
        );
        $rendered = $this->renderView( 'ZaederJsLoggerBundle:Js:logger.js.twig', $params );
        return $this->getJsResponse($rendered);
    }
    
    private function getJsResponse($content)
    {
        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/javascript');
        return $response;
    }
}
