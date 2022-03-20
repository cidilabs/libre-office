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
        $options = array('fileUrl' => $fileUrl, 'fileType' => 'pdf', 'format' => 'html', 'fileName' => 'test.pdf', 'dirname' => 'alternates');

        $taskId = $libre->convertFile($options);

        $this->assertEquals(true, !is_null($taskId));

        $options = ['taskId' => $taskId, 'dirname' => 'alternates'];

        while (!$libre->isReady($options)) {
            print("Waiting on file to finish converting");
        }

        $convertedUrl = $libre->getFileUrl($options);

        $this->assertEquals(true, $libre->deleteFile($convertedUrl));
    }

    public function testCheckIsReadyTrue() {
        $libre = new PhpLibre();
        $fileUrl = "test.pdf";
        $options = array('fileUrl' => $fileUrl, 'fileType' => 'pdf', 'format' => 'html', 'fileName' => 'test.pdf', 'dirname' => 'alternates');

        $taskId = $libre->convertFile($options);

        $this->assertEquals(true, !is_null($taskId));

        $options = ['taskId' => $taskId, 'dirname' => 'alternates'];

        while (!$libre->isReady($options)) {
            print("Waiting on file to finish converting");
        }

        $this->assertEquals(true, $libre->isReady($options));

        $convertedUrl = $libre->getFileUrl($options);

        $this->assertEquals(true, $libre->deleteFile($convertedUrl));
    }

    public function testCheckIsReadyFalse() {
        $libre = new PhpLibre();
        $taskId = 'fakeTaskId';
        $dirname = 'test';

        $options = ['taskId' => $taskId, 'dirname' => 'alternates'];

        $this->assertEquals(false, $libre->isReady($options));
    }

    public function testCheckGetFileUrlFalse() {
        $libre = new PhpLibre();
        $taskId = 'fakeTaskId';
        $dirname = 'test';

        $options = ['taskId' => $taskId, 'dirname' => 'alternates'];

        $this->assertEquals(true, is_null($libre->getFileUrl($options)));
    }

    public function testDeleteFileFalse() {
        $libre = new PhpLibre();
        $convertedUrl = 'fakeUrl';

        $this->assertEquals(false, $libre->deleteFile($convertedUrl));
    }
}