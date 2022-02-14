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
        $fileUrl = "test.pdf";
        $options = array('fileUrl' => $fileUrl, 'fileType' => 'pdf', 'format' => 'html', 'fileName' => 'test.pdf');

        $taskId = $libre->convertFile($options);

        $this->assertEquals(true, !is_null($taskId));

        while (!$libre->isReady($taskId)) {
            print("Waiting on file to finish converting");
        }

        $convertedUrl = $libre->getFileUrl($taskId);
        $this->assertEquals(true, $libre->deleteFile($convertedUrl));
    }

    // public function testConversionImageOnlyPdf() {
    //     $libre = new PhpLibre();
    //     $fileUrl = "image-based-pdf-sample.pdf";
    //     $options = array('fileUrl' => $fileUrl, 'fileType' => 'pdf', 'format' => 'html', 'fileName' => 'image-based-pdf-sample.pdf');

    //     $taskId = $libre->convertFile($options);
    //     $this->assertEquals(true, true);
    // }

    public function testCheckIsReadyTrue() {
        $libre = new PhpLibre();
        $fileUrl = "test.pdf";
        $options = array('fileUrl' => $fileUrl, 'fileType' => 'pdf', 'format' => 'html', 'fileName' => 'test.pdf');

        $taskId = $libre->convertFile($options);

        while (!$libre->isReady($taskId)) {
            print("Waiting on file to finish converting");
        }

        $this->assertEquals(true, $libre->isReady($taskId));

        $convertedUrl = $libre->getFileUrl($taskId);
        $this->assertEquals(true, $libre->deleteFile($convertedUrl));
    }

    public function testCheckIsReadyFalse() {
        $libre = new PhpLibre();
        $taskId = 'fakeTaskId';

        $this->assertEquals(false, $libre->isReady($taskId));
    }

    public function testCheckGetFileUrlFalse() {
        $libre = new PhpLibre();
        $taskId = 'fakeTaskId';

        $this->assertEquals(true, is_null($libre->getFileUrl($taskId)));
    }

    public function testDeleteFileFalse() {
        $libre = new PhpLibre();
        $fileUrl = 'fakeFileUrl';

        $this->assertEquals(false, $libre->deleteFile($fileUrl));
    }
}