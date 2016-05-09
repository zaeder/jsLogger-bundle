<?php

namespace Zaeder\JsLoggerBundle\Logger;

use Psr\Log\LoggerInterface;

class Logger
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $allowedLevels;

    /**
     * @var array
     */
    private $levelToMethod = array(
        'emergency' => 'emerg',
        'alert' => 'alert',
        'critical' => 'crit',
        'error' => 'err',
        'warning' => 'warn',
        'notice' => 'notice',
        'info' => 'info',
        'debug' => 'debug',
    );

    /**
     * @var array
     */
    private $ignoredMessages;

    /**
     * @var array
     */
    private $ignoredURLs;

    /**
     * Constructor
     *
     * @param LoggerInterface $logger
     * @param array           $allowedLevels
     * @param array           $ignoredMessages
     * @param array           $ignoredURLs
     */
    public function __construct(LoggerInterface $logger, array $allowedLevels, array $ignoredMessages = array(), array $ignoredURLs = array())
    {
        $this->logger = $logger;
        $this->allowedLevels = $allowedLevels;
        $this->ignoredMessages = $ignoredMessages;
        $this->ignoredURLs = $ignoredURLs;
    }

    public function write($level, $message, array $context = array())
    {
        if (!$message) {
            return false;
        }

        $level = strtolower($level);
        if (!in_array($level, $this->allowedLevels)) {
            return false;
        }

        if (!empty($context['file'])) {
            foreach ($this->ignoredURLs as $scriptToIgnore) {
                if (strpos($context['file'], $scriptToIgnore) === 0) {
                    return false;
                }
            }
        }

        foreach ($this->ignoredMessages as $errorToIgnore) {
            if (false !== strpos($message, $errorToIgnore)) {
                return false;
            }
        }

        $this->logger->{$this->levelToMethod[$level]}($message, $context);

        return true;
    }
    
    public function haveErrorLoggerAllowed()
    {
        return in_array('error', array_change_key_case($this->allowedLevels, CASE_LOWER));
    }
    
    public function getMethodsToAutolog()
    {
        $autologMethods = array('debug', 'info', 'warning', 'error');
        $methods =  array_intersect($autologMethods, $this->allowedLevels);
        $return = array();
        foreach ($methods as $method){
            $return[] = ($method === 'warning')? 'warn' : $method;
        }
        return $return;
    }
}
