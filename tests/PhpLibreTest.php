<?php

use CidiLabs\PhpLibre\PhpLibre;
use PHPUnit\Framework\TestCase;

class PhpLibreTest extends TestCase {
    public function testInit() {
        $libre = new PhpLibre();

        $this->assertEquals(true, is_object($libre));
    }

    public function testConversion() {
        $libre = new PhpLibre();
        $fileUrl = "https://cidilabs.instructure.com/files/295964/download?download_frd=1&verifier=RZwKCP3iVlNQIULZnTAXO0usUROMC9AuplKkDf2g";
        $fileData = array('fileType' => 'pdf', 'fileName' => 'Amazon.com+-+Order+114-0218739-7877857.pdf');

        $this->assertEquals(true, !is_null($libre->convertFile($fileUrl, $fileData, 'html')));
    }

    public function testCheckIsReadyTrue() {
        $libre = new PhpLibre();
        $fileUrl = "https://cidilabs.instructure.com/files/295964/download?download_frd=1&verifier=RZwKCP3iVlNQIULZnTAXO0usUROMC9AuplKkDf2g";
        $fileData = array('fileType' => 'pdf', 'fileName' => 'Amazon.com+-+Order+114-0218739-7877857.pdf');

        $taskId = $libre->convertFile($fileUrl, $fileData, 'html');
        print($libre->getFileUrl($taskId));

        $this->assertEquals(true, $libre->isReady($taskId));
    }

    public function testCheckIsReadyFalse() {
        $libre = new PhpLibre();
        $taskId = 'fakeTaskId';

        $this->assertEquals(false, $libre->isReady($taskId));
    }
}