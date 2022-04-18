# PhpLibre

PhpLibre is an integration library for the file conversion functionality of LibreOffice. 

## Setup

In order to use this library, you'll need LibreOffice installed. You can find it at the [Libre Office Website](https://www.libreoffice.org/download/download/) or you can install it through Homebrew with ```brew install --cask libreoffice```

PhpLibre can be installed to your project via Composer by adding the following line to your composer.json file: 

```"cidilabs/phplibre": "dev-master"```

Once LibreOffice and the PhpLibre library are installed, you'll need to let UDOIT know which file conversion library you'll be using.

This can be done:

- In the .env: ```###> file formats ###
AVAILABLE_FILE_FORMATS="html,pdf"
HTML_FILE_FORMAT_CLASS="\\CidiLabs\\PhpLibre\\PhpLibre"
PDF_FILE_FORMAT_CLASS="\\CidiLabs\\PhpLibre\\PhpLibre"```

Where ```AVAILABLE_FILE_FORMATS``` is the list of formats that files can be converted to, and for each of those formats there is a ```{FORMAT}_FILE_FORMAT_CLASS``` field that points to PhpLibre. 


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
