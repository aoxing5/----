<?php
class Logger {
    private static $logFile = '../logs/app.log';
    
    const ERROR = 'ERROR';
    const WARNING = 'WARNING';
    const INFO = 'INFO';
    
    public static function log($level, $message, $context = []) {
        $date = date('Y-m-d H:i:s');
        $logMessage = "[$date] [$level] $message";
        
        if (!empty($context)) {
            $logMessage .= ' ' . json_encode($context, JSON_UNESCAPED_UNICODE);
        }
        
        $logMessage .= PHP_EOL;
        
        error_log($logMessage, 3, self::$logFile);
    }
    
    public static function error($message, $context = []) {
        self::log(self::ERROR, $message, $context);
    }
    
    public static function warning($message, $context = []) {
        self::log(self::WARNING, $message, $context);
    }
    
    public static function info($message, $context = []) {
        self::log(self::INFO, $message, $context);
    }
} 