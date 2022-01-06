# PhpLibre

PhpLibre is an integration library for the file conversion functionality of LibreOffice. 

## Setup

In order to use this library, you'll need LibreOffice installed. You can find it at the [Libre Office Website](https://www.libreoffice.org/download/download/) or you can install it through Homebrew with ```brew install --cask libreoffice```

## Basic Usage

- **fileName**: The name of the file
- **fileUrl**: The download URL for the file
- **fileType**: The file type of the original file
- **format**: The file type that we want to convert to
```
$libre =  new  PhpLibre();

$fileUrl =  "https://cidilabs.instructure.com/files/295964/download?download_frd=1&verifier=RZwKCP3iVlNQIULZnTAXO0usUROMC9AuplKkDf2g";

$options =  array('fileUrl'  => $fileUrl,  'fileType'  =>  'pdf',  'format'  =>  'html',  'fileName'  =>  'Test1.pdf');

$libre->convertFile($options);
```

## Class Methods

### convertFile
#### Parameters
- ***options***: (array) 
-- **fileName**: (string) The name of the file
-- **fileUrl**: (string) The download URL for the file
-- **fileType**: (string) The file type of the original file
-- **format**: (string) The file type that we want to convert to
#### Returns
- ***taskId***: (string) The UUID representing the file conversion task
- ***null***

### isReady
#### Parameters
- ***taskId***: (string) The UUID representing the file conversion task
#### Returns
- ***True/False*** (boolean) True if the file has been converted and is ready, false otherwise
### getFileUrl
#### Parameters
- ***taskId***: (string) The UUID representing the file conversion task
#### Returns
- ***fileUrl***: (string) The url of the converted file
- ***null***
### downloadFile
#### Parameters
- ***fileUrl***: (string) The url of the converted file
#### Returns
(To Do)
### deleteFile
#### Parameters
- ***fileUrl***: (string) The url of the converted file
#### Returns
- ***True/False*** (boolean) True if successfully deleted, false otherwise