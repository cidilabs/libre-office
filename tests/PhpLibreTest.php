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
        $fileUrl = "file-sample_1MB.docx";
        $options = array('fileUrl' => $fileUrl, 'fileType' => 'docx', 'format' => 'html', 'fileName' => 'file-sample_1MB.docx');

        $return = $libre->convertFile($options);
        $taskId = $return['data']['taskId'];

        while (!$libre->isReady($taskId)) {
            print("Waiting on file to finish converting");
        }

        $convertedUrl = $libre->getFileUrl($taskId)['data']['filePath'];

        $this->assertEquals(true, empty($libre->deleteFile($convertedUrl)['errors']));
    }

    public function testConversion2() {
        $libre = new PhpLibre();
        $fileUrl = "test.pdf";
        $options = array('fileUrl' => $fileUrl, 'fileType' => 'pdf', 'format' => 'html', 'fileName' => 'test.pdf');

        $return = $libre->convertFile($options);
        $taskId = $return['data']['taskId'];

        while (!$libre->isReady($taskId)) {
            print("Waiting on file to finish converting");
        }

        $convertedUrl = $libre->getFileUrl($taskId)['data']['filePath'];

        $this->assertEquals(true, empty($libre->deleteFile($convertedUrl)['errors']));
    }
    public function testConversion3() {
        $libre = new PhpLibre();
        $fileUrl = "test.pdf";
        $options = array('fileUrl' => $fileUrl, 'fileType' => 'pdf', 'format' => 'html', 'fileName' => 'test.pdf');

        $return = $libre->convertFile($options);
        $taskId = $return['data']['taskId'];

        while (!$libre->isReady($taskId)) {
            print("Waiting on file to finish converting");
        }

        $convertedUrl = $libre->getFileUrl($taskId)['data']['filePath'];
        print($convertedUrl);
        $this->assertEquals(true, file_exists($convertedUrl));
    }

    public function testCheckIsReadyTrue() {
        $libre = new PhpLibre();
        $fileUrl = "test.pdf";
        $options = array('fileUrl' => $fileUrl, 'fileType' => 'pdf', 'format' => 'html', 'fileName' => 'test.pdf');

        $taskId = $libre->convertFile($options)['data']['taskId'];

        while (!$libre->isReady($taskId)) {
            print("Waiting on file to finish converting");
        }

        $convertedUrl = $libre->getFileUrl($taskId)['data']['filePath'];

        $this->assertEquals(true, empty($libre->deleteFile($convertedUrl)['errors']));
    }

    public function testCheckIsReadyFalse() {
        $libre = new PhpLibre();
        $taskId = 'fakeTaskId';

        $this->assertEquals(false, $libre->isReady($taskId));
    }

    public function testCheckGetFileUrlFalse() {
        $libre = new PhpLibre();
        $taskId = 'fakeTaskId';

        $this->assertEquals(false, empty($libre->getFileUrl($taskId)['errors']));
    }

    public function testDeleteFileFalse() {
        $libre = new PhpLibre();
        $convertedUrl = 'fakeUrl';

        $this->assertEquals(false, empty($libre->deleteFile($convertedUrl)['errors']));
    }
}