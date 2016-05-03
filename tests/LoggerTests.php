<?php

class LoggerTests extends PHPUnit_Framework_TestCase {
    // Make sure core functionality is working as expected

    public function testLog() {
        // Arrange
        $l = new Logger();
        
        $l->setLevel(Logger::INFO);        
        $this->assertNotNull($l->info("test"));
        $this->assertNotNull("test<br />", $l->warning("test"));        
        $this->assertNotNull("test<br />", $l->critical("test"));
        
        $l->setLevel(Logger::CRITICAL);
        $this->assertNull(null, $l->info("test"));
        $this->assertNull(null, $l->warning("test"));   

        $l = new Logger("file.txt");
        $this->assertNotNull(file_exists("file.txt"));
    }

}
