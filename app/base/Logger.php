<?php

class Logger
{

    /**
     * Singleton instance
     *
     * Marked only as protected to allow extension of the class. To extend,
     * simply override {@link getInstance()}.
     *
     * @var Calculator
     */
    protected static $_instance = null;
    
    /** 
     * Making protected forces instatiation via getInstance()
     */
    protected function __contruct()
    {
    }

    /**
     * Singleton instance
     *
     * @return Calculator
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Log a message.
     * 
     * @param string  $message
     * @param integer $priority
     */
    public function log($message, $priority = PEAR_LOG_DEBUG)
    {
        $conf = array('mode' => 0755, 'timeFormat' => '%Y-%m-%d %a %H:%M:%S');
        $logger = &Log::singleton('file', app_base_ApplicationRegistry::getLogDirectory(). 'Logger.log', '', $conf);
        $logger->log($message, $priority);
    }

}
