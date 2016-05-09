<?php

namespace Zaeder\JsLoggerBundle\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Zaeder\JsLoggerBundle\Logger\Logger;

class TwigExtension extends \Twig_Extension
{
    /**
     * Router
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    private $router;
    
    /**
     * Logger
     * @var \Zaeder\JsLoggerBundle\Logger\Logger
     */
    private $logger;
    
    /**
     * Logger is initialized
     * @param boolean
     */
    private $loggerInit;

    public function __construct(UrlGeneratorInterface $router, Logger $logger)
    {
        $this->router = $router;
        $this->logger = $logger;
        $this->loggerInit = false;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('zaeder_js_auto_logger', array($this, 'initAutoLogger'), array('is_safe' => array('html', 'js'))),
            new \Twig_SimpleFunction('zaeder_js_logger', array($this, 'initLogger'), array('is_safe' => array('html', 'js'))),
        );
    }

    public function initAutoLogger($function = 'log')
    {
        $urlOnError = addslashes($this->router->generate('zaeder_js_logger_script_listeners_error', array('_format' => 'js')));
        if(count($this->logger->getMethodsToAutolog()) > 0){
            $urlConsole = addslashes($this->router->generate('zaeder_js_logger_script_console', array('_format' => 'js')));
        }

        $newLine = "\r\n";
        $jsFiles = $this->initLogger($function);
        if($this->logger->haveErrorLoggerAllowed()){
            $jsFiles .= $newLine . '<script src="' . $urlOnError .'"></script>';
        }
        if(isset($urlConsole)){
            $jsFiles .=$newLine . '<script src="' . $urlConsole .'"></script>';
        }

        return $jsFiles;
    }

    public function initLogger($function = 'log')
    {
        if($this->loggerInit){
            return '';
        }
        
        $url = addslashes($this->router->generate('zaeder_js_logger_script_logger', array('_format' => 'js')));

        if($function === 'log'){
            $js = '<script src="' . $url .'"></script>';
        } else {
            $js = '<script>' . $function .'</script>';
        }
        
        $this->loggerInit = true;
        
        return $js;
    }

    public function getName()
    {
        return 'zaeder_js_logger';
    }
}
