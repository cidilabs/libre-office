<?php

namespace CidiLabs\PhpLibre;

use DOMDocument;
use DOMXPath;
use Ramsey\Uuid\Uuid;

class PhpLibre
{

    private $bin;

    // extensions and filters for LibreOffice
    // https://help.libreoffice.org/latest/en-US/text/shared/guide/convertfilters.html
    private $extensionFilters = [
        'html' => "html:XHTML Writer File",
    ];

    public function __construct($bin = 'soffice')
    {
        $this->bin = $bin;
        // In case we decide not to make it configurable
        $this->outputDir = 'alternates';
    }

    public function convertFile($options)
    {
        $fileUrl = $options['fileUrl'];
        $extension = $options['fileType'];
        $fileName = $options['fileName'];
        $format = $options['format'];
        $directory = $options['dirname'];
        $taskId = Uuid::uuid4()->toString();
        $newFilename = $taskId . '.' . $format;
        $supportedExtensions = $this->getAllowedConverter($extension);

        //Check for valid input file extension
        if (!array_key_exists($extension, $this->getAllowedConverter())) {
            echo ('Input file extension not supported -- ' . $extension);
            return null;
        }

        if (!in_array($format, $supportedExtensions)) {
            echo ("Output extension({$format}) not supported for input file({$fileUrl})");
            return null;
        }

        if (!file_put_contents($fileName, file_get_contents($fileUrl))) {
            echo ("File downloading failed.");
            return null;
        }

        if (!is_dir($directory)) {
            mkdir($directory);
        }

        $shell = $this->exec($this->makeCommand($format, $fileName, $directory));
        if (0 != $shell['return']) {
            echo ('Conversion Failure! Contact Server Admin. Error: ' . $shell['return']);
            return null;
        }

        $outdir = $directory;
        $basename = pathinfo($fileName, PATHINFO_BASENAME);
        $this->prepOutput($basename, $extension, $outdir, $newFilename, $format);

        return $taskId;
    }

    public function isReady($options)
    {
        $result = glob($options['dirname'] . '/' . $options['taskId'] . '.*');

        return (!empty($result));
    }

    public function getFileUrl($options)
    {
        $result = glob($options['dirname'] . '/' . $options['taskId'] . '.*');

        if (!empty($result)) {
            return ($result[0]);
        }

        return null;
    }

    public function deleteFile($fileUrl)
    {
        if (file_exists($fileUrl)) {
            unlink($fileUrl);
            return true;
        } else {
            print("File not found");
            return false;
        }
    }

    /**
     * Helpers
     **/


    protected function makeCommand($outputExtension, $filename, $dirname)
    {
        $oriFile = escapeshellarg($filename);

        $outputExtension = !empty($this->extensionFilters[$outputExtension]) ? $this->extensionFilters[$outputExtension] : $outputExtension;

        return "{$this->bin} --headless --convert-to \"{$outputExtension}\" {$oriFile} --outdir {$dirname}";
    }


    protected function prepOutput($basename, $inputExtension, $outdir, $filename, $outputExtension)
    {
        $DS = DIRECTORY_SEPARATOR;
        $tmpName = ($inputExtension ? basename($basename, $inputExtension) : $basename . '.').$outputExtension;
        if (rename($outdir.$DS.$tmpName, $outdir.$DS.$filename)) {
            return $outdir.$DS.$filename;
        } elseif (is_file($outdir.$DS.$tmpName)) {
            return $outdir.$DS.$tmpName;
        }

        return null;
    }

    protected function open($filename)
    {
        if (!file_exists($filename) || false === realpath($filename)) {
            print('File does not exist --' . $filename);
            return false;
        }

        return true;
    }


    private function getAllowedConverter($extension = null)
    {
        $allowedConverter = [
            '' => ['pdf'],
            'pptx' => ['pdf'],
            'ppt' => ['pdf'],
            'pdf' => ['pdf', 'html'],
            'docx' => ['pdf', 'odt', 'html'],
            'doc' => ['pdf', 'odt', 'html'],
            'wps' => ['pdf', 'odt', 'html'],
            'dotx' => ['pdf', 'odt', 'html'],
            'docm' => ['pdf', 'odt', 'html'],
            'dotm' => ['pdf', 'odt', 'html'],
            'dot' => ['pdf', 'odt', 'html'],
            'odt' => ['pdf', 'html'],
            'xlsx' => ['pdf'],
            'xls' => ['pdf'],
            'png' => ['pdf'],
            'jpg' => ['pdf'],
            'jpeg' => ['pdf'],
            'jfif' => ['pdf'],
            'PPTX' => ['pdf'],
            'PPT' => ['pdf'],
            'PDF' => ['pdf', 'html'],
            'DOCX' => ['pdf', 'odt', 'html'],
            'DOC' => ['pdf', 'odt', 'html'],
            'WPS' => ['pdf', 'odt', 'html'],
            'DOTX' => ['pdf', 'odt', 'html'],
            'DOCM' => ['pdf', 'odt', 'html'],
            'DOTM' => ['pdf', 'odt', 'html'],
            'DOT' => ['pdf', 'odt', 'html'],
            'ODT' => ['pdf', 'html'],
            'XLSX' => ['pdf'],
            'XLS' => ['pdf'],
            'PNG' => ['pdf'],
            'JPG' => ['pdf'],
            'JPEG' => ['pdf'],
            'JFIF' => ['pdf'],
            'Pptx' => ['pdf'],
            'Ppt' => ['pdf'],
            'Pdf' => ['pdf'],
            'Docx' => ['pdf', 'odt', 'html'],
            'Doc' => ['pdf', 'odt', 'html'],
            'Wps' => ['pdf', 'odt', 'html'],
            'Dotx' => ['pdf', 'odt', 'html'],
            'Docm' => ['pdf', 'odt', 'html'],
            'Dotm' => ['pdf', 'odt', 'html'],
            'Dot' => ['pdf', 'odt', 'html'],
            'Ddt' => ['pdf', 'html'],
            'Xlsx' => ['pdf'],
            'Xls' => ['pdf'],
            'Png' => ['pdf'],
            'Jpg' => ['pdf'],
            'Jpeg' => ['pdf'],
            'Jfif' => ['pdf'],
            'rtf'  => ['docx', 'txt', 'pdf'],
            'txt'  => ['pdf', 'odt', 'doc', 'docx', 'html'],
        ];

        if (null !== $extension) {
            if (isset($allowedConverter[$extension])) {
                return $allowedConverter[$extension];
            }

            return [];
        }

        return $allowedConverter;
    }

    /**
     * More intelligent interface to system calls.
     *
     * @see http://php.net/manual/en/function.system.php
     *
     * @param string $cmd
     * @param string $input
     *
     * @return array
     */
    private function exec($cmd, $input = '')
    {
        $process = proc_open($cmd, [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);

        if (false === $process) {
            print('Cannot obtain ressource for process to convert file');
        }

        fwrite($pipes[0], $input);
        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        $rtn = proc_close($process);

        return [
            'stdout' => $stdout,
            'stderr' => $stderr,
            'return' => $rtn,
        ];
    }
}
