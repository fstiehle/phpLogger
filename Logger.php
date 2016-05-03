<?php
/**
 * Simple Logger class with timestamps
 * tested with PHP 5.4
 */
class Logger {

    private $fileName;

    /**
     * Loggers sensitivity
     * Logs entries on same level and above
     */
    const INFO = 10;
    const WARNING = 20;
    const CRITICAL= 30;

    private $level;

    /**
     * Holds inital timestamp to subtract from
     */
    private $timeStamp;
    
    /**
     * @param $fileName Optional String
     * If nothing is given, logging will output directly
     */
    function __construct($fileName = "") {
        $this->fileName = $fileName;
        $this->timeStamp = microtime(true);
        $this->level = self::INFO;
    }

    /**
     * Writes to log, creates logfile if not existent
     * @param content String Content to log
     */
    private function writeLog($content) {
        if (!$this->fileName) {
            // No filename is given, logging will output directly
            echo $content . "<br />";
            return;
        }
        try {
            // Check if file needs to be created first
            if (!file_exists($this->fileName)) {
                if (!$logFile = fopen($this->fileName, "w")) {
                    throw new Exception('File creation failed.');
                }
                fclose($logFile);
            }
            // open as "a": append (sets the file pointer correctly) 
            if (!$logFile = fopen($this->fileName, "a")) {
                throw new Exception('File open failed.');
            }
            if (!fwrite($logFile, $content)) {
                throw new Exception('File write failed.');
            }

        } catch (Exception $e) {
            var_dump($e->getMessage());
        } finally {
            if ($logFile) {
                fclose($logFile);
            }
        }
    }

    /**
     * Formats log output:
     * ["Current Date"]["Execution time in sec"]$content
     * @param $content String
     */
    private function formatOutput($content) {
        $timeStamp = microtime(true) -  $this->timeStamp;
        return '[' . $this->getDateISO8601() . ']' . '[' . $timeStamp . 's]' .
            $content . PHP_EOL;
    }

    private function getDateISO8601() {
        $datetime = new DateTime(date('Y-m-d H:i:s'));
        return $datetime->format(DateTime::ISO8601);
    }

    /**
     * Adds a log entry
     * @param $content String Content to write to log 
     */ 
    public function info($content) {
        if ($this->level > self::INFO) {
            return;
        }
        $string = $this->formatOutput('INFO: ' . $content);
        $this->writeLog($string);
    }

    public function warning($content) {
        if ($this->level > self::WARNING) {
            return;
        }
        $string = $this->formatOutput('WARNING: ' . $content);
        $this->writeLog($string);
    }

    public function critical($content) {
        if ($this->level > self::CRITICAL) {
            return;
        }
        $string = $this->formatOutput('CRITICAL: ' . $content);
        $this->writeLog($string);
    }

    /**
     * Sets loggers sensitivity
     * @param $level to set, must be on of the predefined constants
     * INFO(10), WARNING(20), CRITICAL(30): always logs entries on same level and above
     */ 
    public function setLevel($level) {
        switch($level) {
            case self::INFO:
            case self::WARNING:
            case self::CRITICAL:
                $this->level = $level;
                break;
            default:
                $this->level = self::WARNING;
                $this->warning('Unsupported logging level.');
                break;
        }
    }
}
