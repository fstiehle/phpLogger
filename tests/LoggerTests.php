<?php

class LoggerTests extends PHPUnit_Framework_TestCase {
    /**
     * Make sure core functionality is working as expected
     * this is not, by all means, meant to cover the entire code
     * Utilizes php output buffer to fetch echo output
     * see: http://php.net/manual/de/function.ob-get-clean.php
     */
    public function testLog() {
        $l = new Logger();

        $l->setLevel(Logger::INFO);  

        ob_start();
        $l->info("test");
        $this->assertNotFalse(ob_get_clean());

        ob_start();
        $l->warning("test");
        $this->assertNotFalse(ob_get_clean());

        ob_start();
        $l->critical("test");
        $this->assertNotFalse(ob_get_clean());

        $l = new Logger("file.txt");
        $l->info("test"); // log is created for first log entry
        $this->assertTrue(file_exists("file.txt"));
    }
    
    public function testOutput() {
        $l = new Logger();
        $l->critical("test");
        $this->expectOutputRegex("/(\[.{0,}\b\]\[.{0,}\b\]\w{0,}: test)/");
    }

    public function testLevel() {
        $l = new Logger();

        $l->setLevel(Logger::CRITICAL); 

        ob_start();
        $l->info("test");
        $this->assertEquals("", ob_get_clean());  

        ob_start();
        $l->warning("test");
        $this->assertEquals("", ob_get_clean());

        ob_start();
        $l->critical("test");
        $this->assertNotFalse(ob_get_clean());
    }

}
