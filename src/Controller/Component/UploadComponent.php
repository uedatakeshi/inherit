<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Routing\Router;
use Cake\Utility\Text;

class UploadComponent extends Component
{
    protected $name = 'ueda';

    protected $options;

    protected $errorMessages = [
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk',
        8 => 'A PHP extension stopped the file upload',
        'post_max_size' => 'The uploaded file exceeds the post_max_size directive in php.ini',
        'max_file_size' => 'File is too big',
        'min_file_size' => 'File is too small',
        'accept_file_types' => 'Filetype not allowed',
        'max_number_of_files' => 'Maximum number of files exceeded',
        'max_width' => 'Image exceeds maximum width',
        'min_width' => 'Image requires a minimum width',
        'max_height' => 'Image exceeds maximum height',
        'min_height' => 'Image requires a minimum height',
        'abort' => 'File upload aborted',
        'image_resize' => 'Failed to resize image'
    ];

    protected $imageObjects = [];

    /**
     * initialize method
     *
     * @param array $config
     * @return void
     */
    public function initialize(array $config)
    {
        $this->options = [
            'script_url' => Router::url(null, true),
            'upload_dir' => WWW_ROOT . 'files/',
            'upload_url' => Router::url('/', true) . 'files/',
            'user_dirs' => false,
            'mkdir_mode' => 0755,
            'param_name' => 'files',
            // Set the following option to 'POST', if your server does not support
            // DELETE requests. This is a parameter sent to the client:
            'delete_type' => 'POST',
            'access_control_allow_origin' => '*',
            'access_control_allow_credentials' => false,
            'access_control_allow_methods' => [
                'OPTIONS',
                'HEAD',
                'GET',
                'POST',
                'PUT',
                'PATCH',
                'DELETE'
            ],
            'access_control_allow_headers' => [
                'Content-Type',
                'Content-Range',
                'Content-Disposition'
            ],
            // By default, allow redirects to the referer protocol+host:
            'redirect_allow_target' => '/^' . preg_quote(
                parse_url($this->request->env('HTTP_REFERER'), PHP_URL_SCHEME) .
                '://' .
                parse_url($this->request->env('HTTP_REFERER'), PHP_URL_HOST) .
                '/', // Trailing slash to not match subdomains by mistake
                '/' // preg_quote delimiter param
            ) . '/',
            // Enable to provide file downloads via GET requests to the PHP script:
            //     1. Set to 1 to download files via readfile method through PHP
            //     2. Set to 2 to send a X-Sendfile header for lighttpd/Apache
            //     3. Set to 3 to send a X-Accel-Redirect header for nginx
            // If set to 2 or 3, adjust the upload_url option to the base path of
            // the redirect parameter, e.g. '/files/'.
            'download_via_php' => false,
            // Read files in chunks to avoid memory limits when download_via_php
            // is enabled, set to 0 to disable chunked reading of files:
            'readfile_chunk_size' => 10 * 1024 * 1024, // 10 MiB
            // Defines which files can be displayed inline when downloaded:
            'inline_file_types' => '/\.(gif|jpe?g|png)$/i',
            // Defines which files (based on their names) are accepted for upload:
            'accept_file_types' => '/.+$/i',
            // The php.ini settings upload_max_filesize and post_max_size
            // take precedence over the following max_file_size setting:
            'max_file_size' => null,
            'min_file_size' => 1,
            // The maximum number of files for the upload directory:
            'max_number_of_files' => null,
            // Defines which files are handled as image files:
            'image_file_types' => '/\.(gif|jpe?g|png)$/i',
            // Use exif_imagetype on all files to correct file extensions:
            'correct_image_extensions' => false,
            // Image resolution restrictions:
            'max_width' => null,
            'max_height' => null,
            'min_width' => 1,
            'min_height' => 1,
            // Set the following option to false to enable resumable uploads:
            'discard_aborted_uploads' => true,
            // Set to 0 to use the GD library to scale and orient images,
            // set to 1 to use imagick (if installed, falls back to GD),
            // set to 2 to use the ImageMagick convert binary directly:
            'image_library' => 1,
            // Uncomment the following to define an array of resource limits
            // for imagick:
            /*
            'imagick_resource_limits' => array(
                imagick::RESOURCETYPE_MAP => 32,
                imagick::RESOURCETYPE_MEMORY => 32
            ),
            */
            // Command or path for to the ImageMagick convert binary:
            'convert_bin' => 'convert',
            // Uncomment the following to add parameters in front of each
            // ImageMagick convert call (the limit constraints seem only
            // to have an effect if put in front):
            /*
            'convert_params' => '-limit memory 32MiB -limit map 32MiB',
            */
            // Command or path for to the ImageMagick identify binary:
            'identify_bin' => 'identify',
            'image_versions' => [
                // The empty image version key defines options for the original image:
                '' => [
                    // Automatically rotate images based on EXIF meta data:
                    'auto_orient' => true
                ],
                // Uncomment the following to create medium sized images:
                /*
                'medium' => array(
                    'max_width' => 800,
                    'max_height' => 600
                ),
                */
                'thumbnail' => [
                    // Uncomment the following to use a defined directory for the thumbnails
                    // instead of a subdirectory based on the version identifier.
                    // Make sure that this directory doesn't allow execution of files if you
                    // don't pose any restrictions on the type of uploaded files, e.g. by
                    // copying the .htaccess file from the files directory for Apache:
                    //'upload_dir' => dirname($this->get_server_var('SCRIPT_FILENAME')).'/thumb/',
                    //'upload_url' => $this->getFullUrl().'/thumb/',
                    // Uncomment the following to force the max
                    // dimensions and e.g. create square thumbnails:
                    //'crop' => true,
                    'max_width' => 80,
                    'max_height' => 80
                ]
            ],
        ];
    }


    public function send($options = null, $errorMessages = null)
    {
        if ($options) {
            $this->options = $options + $this->options;
        }
        if ($errorMessages) {
            $this->error_messages = $errorMessages + $this->error_messages;
        }

        switch ($this->request->env('REQUEST_METHOD')) {
            case 'GET':
            case 'POST':
                return $this->post();
            case 'DELETE':
                $this->delete();
                break;
            default:
                throw new MethodNotAllowedException();
        }
    }

    public function post()
    {
        if ($this->request->query('_method') === 'DELETE') {
            return $this->delete();
        }
        $upload = $this->request->data[$this->options['param_name']];
        // Parse the Content-Disposition header, if available:
        $contentDispositionHeader = $this->request->env('HTTP_CONTENT_DISPOSITION');
        $fileName = $contentDispositionHeader ?
            rawurldecode(preg_replace(
                '/(^[^"]+")|("$)/',
                '',
                $contentDispositionHeader
            )) : null;
        // Parse the Content-Range header, which has the following form:
        // Content-Range: bytes 0-524287/2000000
        $contentRangeHeader = $this->request->env('HTTP_CONTENT_RANGE');
        $contentRange = $contentRangeHeader ?
            preg_split('/[^0-9]+/', $contentRangeHeader) : null;
        $size = $contentRange ? $contentRange[3] : null;
        $files = [];
        if ($upload) {
            if (isset($upload[0]) && is_array($upload[0])) {
                // param_name is an array identifier like "files[]",
                // $upload is a multi-dimensional array:
                foreach ($upload as $index => $value) {
                    $files[] = $this->handleFileUpload(
                        $value['tmp_name'],
                        $fileName ? $fileName : $value['name'],
                        $size ? $size : $value['size'],
                        $value['type'],
                        $value['error'],
                        $index,
                        $contentRange
                    );
                }
            } else {
                // param_name is a single object identifier like "file",
                // $upload is a one-dimensional array:
                $files[] = $this->handleFileUpload(
                    isset($upload['tmp_name']) ? $upload['tmp_name'] : null,
                    $fileName ? $fileName : (isset($upload['name']) ? $upload['name'] : null),
                    $size ? $size : (isset($upload['size']) ? $upload['size'] : $this->request->env('CONTENT_LENGTH')),
                    isset($upload['type']) ? $upload['type'] : $this->request->env('CONTENT_TYPE'),
                    isset($upload['error']) ? $upload['error'] : null,
                    null,
                    $contentRange
                );
            }
        }
        $response = [$this->options['param_name'] => $files];

        return $response;
    }

    public function delete()
    {
        echo "delete";
    }

    protected function handleFileUpload($uploadedFile, $name, $size, $type, $error, $index = null, $contentRange = null)
    {
        $file = new \stdClass();
        $file->nameOrg = $name;
        $file->name = $this->getFileNameMd5($uploadedFile, $name, $size, $type, $error, $index, $contentRange);

        $file->size = $this->fixIntegerOverflow((int)$size);
        $file->type = $type;
        if ($this->validate($uploadedFile, $file, $error, $index)) {
            $this->handleFormData($file, $index);
            $uploadDir = $this->getUploadPath();
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, $this->options['mkdir_mode'], true);
            }
            $filePath = $this->getUploadPath($file->name);
            $appendFile = $contentRange && is_file($filePath) && $file->size > $this->getFileSize($filePath);
            if ($uploadedFile && is_uploaded_file($uploadedFile)) {
                // multipart/formdata uploads (POST method uploads)
                if ($appendFile) {
                    file_put_contents(
                        $filePath,
                        fopen($uploadedFile, 'r'),
                        FILE_APPEND
                    );
                } else {
                    move_uploaded_file($uploadedFile, $filePath);
                }
            } else {
                // Non-multipart uploads (PUT method support)
                file_put_contents(
                    $filePath,
                    fopen('php://input', 'r'),
                    $appendFile ? FILE_APPEND : 0
                );
            }
            $fileSize = $this->getFileSize($filePath, $appendFile);
            if ($fileSize === $file->size) {
                $file->url = $this->getDownloadUrl($file->name);
                if ($this->isValidImageFile($filePath)) {
                    $this->handleImageFile($filePath, $file);
                }
            } else {
                $file->size = $fileSize;
                if (!$contentRange && $this->options['discard_aborted_uploads']) {
                    unlink($filePath);
                    $file->error = $this->getErrorMessage('abort');
                }
            }
            $this->setAdditionalFileProperties($file);
        }
        return $file;
    }

    protected function getUniqueFilename($filePath, $name, $size, $type, $error, $index, $contentRange)
    {
        while (is_dir($this->getUploadPath($name))) {
            $name = $this->upcountName($name);
        }
        // Keep an existing filename if this is part of a chunked upload:
        $uploadedBytes = $this->fixIntegerOverflow((int)$contentRange[1]);
        while (is_file($this->getUploadPath($name))) {
            if ($uploadedBytes === $this->getFileSize($this->getUploadPath($name))) {
                break;
            }
            $name = $this->upcountName($name);
        }
        return $name;
    }

    protected function trimFileName($filePath, $name, $size, $type, $error, $index, $contentRange)
    {
        // Remove path information and dots around the filename, to prevent uploading
        // into different directories or replacing hidden system files.
        // Also remove control characters and spaces (\x00..\x20) around the filename:
        $name = trim(basename(stripslashes($name)), ".\x00..\x20");
        // Use a timestamp for empty filenames:
        if (!$name) {
            $name = str_replace('.', '-', microtime(true));
        }
        return $name;
    }

    protected function getFileName($filePath, $name, $size, $type, $error, $index, $contentRange)
    {
        $name = $this->trimFileName($filePath, $name, $size, $type, $error, $index, $contentRange);
        return $this->getUniqueFilename(
            $filePath,
            $this->fixFileExtension($filePath, $name, $size, $type, $error, $index, $contentRange),
            $size,
            $type,
            $error,
            $index,
            $contentRange
        );
    }

    protected function getFileNameMd5($filePath, $name, $size, $type, $error, $index, $contentRange)
    {
        // add pdf extension
        $ext = '';
        if (preg_match('/\.pdf$/i', $name)) {
            $ext = ".pdf";
        }
        $name = md5($filePath) . $ext;
        return $this->getUniqueFilename(
            $filePath,
            $this->fixFileExtension($filePath, $name, $size, $type, $error, $index, $contentRange),
            $size,
            $type,
            $error,
            $index,
            $contentRange
        );
    }

    protected function fixFileExtension($filePath, $name, $size, $type, $error, $index, $contentRange)
    {
        // Add missing file extension for known image types:
        if (strpos($name, '.') === false && preg_match('/^image\/(gif|jpe?g|png)/', $type, $matches)) {
            $name .= '.' . $matches[1];
        }
        if ($this->options['correct_image_extensions'] && function_exists('exif_imagetype')) {
            switch (@exif_imagetype($filePath)) {
                case IMAGETYPE_JPEG:
                    $extensions = ['jpg', 'jpeg'];
                    break;
                case IMAGETYPE_PNG:
                    $extensions = ['png'];
                    break;
                case IMAGETYPE_GIF:
                    $extensions = ['gif'];
                    break;
            }
            // Adjust incorrect image file extensions:
            if (!empty($extensions)) {
                $parts = explode('.', $name);
                $extIndex = count($parts) - 1;
                $ext = strtolower(@$parts[$extIndex]);
                if (!in_array($ext, $extensions)) {
                    $parts[$extIndex] = $extensions[0];
                    $name = implode('.', $parts);
                }
            }
        }
        return $name;
    }

    protected function setAdditionalFileProperties($file)
    {
        $file->deleteUrl = $this->options['script_url'] .
            $this->getQuerySeparator($this->options['script_url']) .
            $this->getSingularParamName() .
            '=' .
            rawurlencode($file->name);
        $file->deleteType = $this->options['delete_type'];
        if ($file->deleteType !== 'DELETE') {
            $file->deleteUrl .= '&_method=DELETE';
        }
        if ($this->options['access_control_allow_credentials']) {
            $file->deleteWithCredentials = true;
        }
    }

    protected function handleImageFile($filePath, $file)
    {
        $failedVersions = [];
        foreach ($this->options['image_versions'] as $version => $options) {
            if ($this->createScaledImage($file->name, $version, $options)) {
                if (!empty($version)) {
                    $file->{$version . 'Url'} = $this->getDownloadUrl(
                        $file->name,
                        $version
                    );
                } else {
                    $file->size = $this->getFileSize($filePath, true);
                }
            } else {
                $failedVersions[] = $version ? $version : 'original';
            }
        }
        if (count($failedVersions)) {
            $file->error = $this->getErrorMessage('image_resize') .
                ' (' . implode($failedVersions, ', ') . ')';
        }
        // Free memory:
        $this->destroyImageObject($filePath);
    }

    protected function createScaledImage($fileName, $version, $options)
    {
        if ($this->options['image_library'] === 2) {
            return $this->imagemagickCreateScaledImage($fileName, $version, $options);
        }
        if ($this->options['image_library'] && extension_loaded('imagick')) {
            return $this->imagickCreateScaledImage($fileName, $version, $options);
        }
        return $this->gdCreateScaledImage($fileName, $version, $options);
    }

    protected function gdCreateScaledImage($fileName, $version, $options)
    {
        if (!function_exists('imagecreatetruecolor')) {
            error_log('Function not found: imagecreatetruecolor');
            return false;
        }
        list($filePath, $newFilePath) =
            $this->getScaledImageFilePaths($fileName, $version);
        $type = strtolower(substr(strrchr($fileName, '.'), 1));
        switch ($type) {
            case 'jpg':
            case 'jpeg':
                $srcFunc = 'imagecreatefromjpeg';
                $writeFunc = 'imagejpeg';
                $imageQuality = isset($options['jpeg_quality']) ?
                    $options['jpeg_quality'] : 75;
                break;
            case 'gif':
                $srcFunc = 'imagecreatefromgif';
                $writeFunc = 'imagegif';
                $imageQuality = null;
                break;
            case 'png':
                $srcFunc = 'imagecreatefrompng';
                $writeFunc = 'imagepng';
                $imageQuality = isset($options['png_quality']) ?
                    $options['png_quality'] : 9;
                break;
            default:
                return false;
        }
        $srcImg = $this->gdGetImageObject(
            $filePath,
            $srcFunc,
            !empty($options['no_cache'])
        );
        $imageOriented = false;
        if (!empty($options['auto_orient']) && $this->gdOrientImage($filePath, $srcImg)) {
            $imageOriented = true;
            $srcImg = $this->gd_get_image_object($filePath, $srcFunc);
        }
        $maxWidth = $imgWidth = imagesx($srcImg);
        $maxHeight = $imgHeight = imagesy($srcImg);
        if (!empty($options['max_width'])) {
            $maxWidth = $options['max_width'];
        }
        if (!empty($options['max_height'])) {
            $maxHeight = $options['max_height'];
        }
        $scale = min(
            $maxWidth / $imgWidth,
            $maxHeight / $imgHeight
        );
        if ($scale >= 1) {
            if ($imageOriented) {
                return $writeFunc($srcImg, $newFilePath, $imageQuality);
            }
            if ($filePath !== $newFilePath) {
                return copy($filePath, $newFilePath);
            }
            return true;
        }
        if (empty($options['crop'])) {
            $newWidth = $imgWidth * $scale;
            $newHeight = $imgHeight * $scale;
            $dstX = 0;
            $dstY = 0;
            $newImg = imagecreatetruecolor($newWidth, $newHeight);
        } else {
            if (($imgWidth / $imgHeight) >= ($maxWidth / $maxHeight)) {
                $newWidth = $imgWidth / ($imgHeight / $maxHeight);
                $newHeight = $maxHeight;
            } else {
                $newWidth = $maxWidth;
                $newHeight = $imgHeight / ($imgWidth / $maxWidth);
            }
            $dstX = 0 - ($newWidth - $maxWidth) / 2;
            $dstY = 0 - ($newHeight - $maxHeight) / 2;
            $newImg = imagecreatetruecolor($maxWidth, $maxHeight);
        }
        // Handle transparency in GIF and PNG images:
        switch ($type) {
            case 'gif':
            case 'png':
                imagecolortransparent($newImg, imagecolorallocate($newImg, 0, 0, 0));// this is gif
            case 'png':
                imagealphablending($newImg, false);
                imagesavealpha($newImg, true);
                break;
        }
        $success = imagecopyresampled(
            $newImg,
            $srcImg,
            $dstX,
            $dstY,
            0,
            0,
            $newWidth,
            $newHeight,
            $imgWidth,
            $imgHeight
        ) && $writeFunc($newImg, $newFilePath, $imageQuality);
        $this->gdSetImageObject($filePath, $newImg);
        return $success;
    }
    
    protected function destroyImageObject($filePath)
    {
        if ($this->options['image_library'] && extension_loaded('imagick')) {
            return $this->imagickDestroyImageObject($filePath);
        }
    }

    protected function imagickDestroyImageObject($filePath)
    {
        $image = (isset($this->image_objects[$filePath])) ? $this->image_objects[$filePath] : null;
        return $image && $image->destroy();
    }

    protected function getScaledImageFilePaths($fileName, $version)
    {
        $filePath = $this->getUploadPath($fileName);
        if (!empty($version)) {
            $versionDir = $this->getUploadPath(null, $version);
            if (!is_dir($versionDir)) {
                mkdir($versionDir, $this->options['mkdir_mode'], true);
            }
            $newFilePath = $versionDir . '/' . $fileName;
        } else {
            $newFilePath = $filePath;
        }
        return [$filePath, $newFilePath];
    }

    protected function gdGetImageObject($filePath, $func, $noCache = false)
    {
        if (empty($this->imageObjects[$filePath]) || $noCache) {
            $this->gdDestroyImageObject($filePath);
            $this->imageObjects[$filePath] = $func($filePath);
        }
        return $this->imageObjects[$filePath];
    }

    protected function gdDestroyImageObject($filePath)
    {
        $image = (isset($this->imageObjects[$filePath])) ? $this->imageObjects[$filePath] : null;
        return $image && imagedestroy($image);
    }

    protected function imagickCreateScaledImage($fileName, $version, $options)
    {
        list($filePath, $newFilePath) = $this->getScaledImageFilePaths($fileName, $version);
        $image = $this->imagickGetImageObject(
            $filePath,
            !empty($options['crop']) || !empty($options['no_cache'])
        );
        if ($image->getImageFormat() === 'GIF') {
            // Handle animated GIFs:
            $images = $image->coalesceImages();
            foreach ($images as $frame) {
                $image = $frame;
                $this->imagickSetImageObject($fileName, $image);
                break;
            }
        }
        $imageOriented = false;
        if (!empty($options['auto_orient'])) {
            $imageOriented = $this->imagickOrientImage($image);
        }
        $newWidth = $maxWidth = $imgWidth = $image->getImageWidth();
        $newHeight = $maxHeight = $imgHeight = $image->getImageHeight();
        if (!empty($options['max_width'])) {
            $newWidth = $maxWidth = $options['max_width'];
        }
        if (!empty($options['max_height'])) {
            $newHeight = $maxHeight = $options['max_height'];
        }
        if (!($imageOriented || $maxWidth < $imgWidth || $maxHeight < $imgHeight)) {
            if ($filePath !== $newFilePath) {
                return copy($filePath, $newFilePath);
            }
            return true;
        }
        $crop = !empty($options['crop']);
        if ($crop) {
            $x = 0;
            $y = 0;
            if (($imgWidth / $imgHeight) >= ($maxWidth / $maxHeight)) {
                $newWidth = 0; // Enables proportional scaling based on max_height
                $x = ($imgWidth / ($imgHeight / $maxHeight) - $maxWidth) / 2;
            } else {
                $newHeight = 0; // Enables proportional scaling based on max_width
                $y = ($imgHeight / ($imgWidth / $maxWidth) - $maxHeight) / 2;
            }
        }
        $success = $image->resizeImage(
            $newWidth,
            $newHeight,
            isset($options['filter']) ? $options['filter'] : \imagick::FILTER_LANCZOS,
            isset($options['blur']) ? $options['blur'] : 1,
            $newWidth && $newHeight // fit image into constraints if not to be cropped
        );
        if ($success && $crop) {
            $success = $image->cropImage(
                $maxWidth,
                $maxHeight,
                $x,
                $y
            );
            if ($success) {
                $success = $image->setImagePage($maxWidth, $maxHeight, 0, 0);
            }
        }
        $type = strtolower(substr(strrchr($fileName, '.'), 1));
        switch ($type) {
            case 'jpg':
            case 'jpeg':
                if (!empty($options['jpeg_quality'])) {
                    $image->setImageCompression(\imagick::COMPRESSION_JPEG);
                    $image->setImageCompressionQuality($options['jpeg_quality']);
                }
                break;
        }
        if (!empty($options['strip'])) {
            $image->stripImage();
        }
        return $success && $image->writeImage($newFilePath);
    }

    protected function imagickOrientImage($image)
    {
        $orientation = $image->getImageOrientation();
        $background = new \ImagickPixel('none');
        switch ($orientation) {
            case \imagick::ORIENTATION_TOPRIGHT: // 2
                $image->flopImage(); // horizontal flop around y-axis
                break;
            case \imagick::ORIENTATION_BOTTOMRIGHT: // 3
                $image->rotateImage($background, 180);
                break;
            case \imagick::ORIENTATION_BOTTOMLEFT: // 4
                $image->flipImage(); // vertical flip around x-axis
                break;
            case \imagick::ORIENTATION_LEFTTOP: // 5
                $image->flopImage(); // horizontal flop around y-axis
                $image->rotateImage($background, 270);
                break;
            case \imagick::ORIENTATION_RIGHTTOP: // 6
                $image->rotateImage($background, 90);
                break;
            case \imagick::ORIENTATION_RIGHTBOTTOM: // 7
                $image->flipImage(); // vertical flip around x-axis
                $image->rotateImage($background, 270);
                break;
            case \imagick::ORIENTATION_LEFTBOTTOM: // 8
                $image->rotateImage($background, 270);
                break;
            default:
                return false;
        }
        $image->setImageOrientation(\imagick::ORIENTATION_TOPLEFT); // 1
        return true;
    }

    protected function imagickGetImageObject($filePath, $noCache = false)
    {
        if (empty($this->imageObjects[$filePath]) || $noCache) {
            $this->imagickDestroyImageObject($filePath);
            $image = new \Imagick();
            if (!empty($this->options['imagick_resource_limits'])) {
                foreach ($this->options['imagick_resource_limits'] as $type => $limit) {
                    $image->setResourceLimit($type, $limit);
                }
            }
            $image->readImage($filePath);
            $this->imageObjects[$filePath] = $image;
        }
        return $this->imageObjects[$filePath];
    }

    protected function imagickSetImageObject($filePath, $image)
    {
        $this->imagickDestroyImageObject($filePath);
        $this->imageObjects[$filePath] = $image;
    }

    protected function gdOrientImage($filePath, $srcImg)
    {
        if (!function_exists('exif_read_data')) {
            return false;
        }
        $exif = @exif_read_data($filePath);
        if ($exif === false) {
            return false;
        }
        $orientation = (int)@$exif['Orientation'];
        if ($orientation < 2 || $orientation > 8) {
            return false;
        }
        switch ($orientation) {
            case 2:
                $newImg = $this->gdImageflip(
                    $srcImg,
                    defined('IMG_FLIP_VERTICAL') ? IMG_FLIP_VERTICAL : 2
                );
                break;
            case 3:
                $newImg = imagerotate($srcImg, 180, 0);
                break;
            case 4:
                $newImg = $this->gdImageflip(
                    $srcImg,
                    defined('IMG_FLIP_HORIZONTAL') ? IMG_FLIP_HORIZONTAL : 1
                );
                break;
            case 5:
                $tmpImg = $this->gdImageflip(
                    $srcImg,
                    defined('IMG_FLIP_HORIZONTAL') ? IMG_FLIP_HORIZONTAL : 1
                );
                $newImg = imagerotate($tmpImg, 270, 0);
                imagedestroy($tmpImg);
                break;
            case 6:
                $newImg = imagerotate($srcImg, 270, 0);
                break;
            case 7:
                $tmpImg = $this->gdImageflip(
                    $srcImg,
                    defined('IMG_FLIP_VERTICAL') ? IMG_FLIP_VERTICAL : 2
                );
                $newImg = imagerotate($tmpImg, 270, 0);
                imagedestroy($tmpImg);
                break;
            case 8:
                $newImg = imagerotate($srcImg, 90, 0);
                break;
            default:
                return false;
        }
        $this->gdSetImageObject($filePath, $newImg);
        return true;
    }
    
    protected function gdSetImageObject($filePath, $image)
    {
        $this->gdDestroyImageObject($filePath);
        $this->imageObjects[$filePath] = $image;
    }

    protected function imagemagickCreateScaledImage($fileName, $version, $options)
    {
        list($filePath, $newFilePath) = $this->getScaledImageFilePaths($fileName, $version);
        $resize = @$options['max_width'] . (empty($options['max_height']) ? '' : 'X' . $options['max_height']);
        if (!$resize && empty($options['auto_orient'])) {
            if ($filePath !== $newFilePath) {
                return copy($filePath, $newFilePath);
            }
            return true;
        }
        $cmd = $this->options['convert_bin'];
        if (!empty($this->options['convert_params'])) {
            $cmd .= ' ' . $this->options['convert_params'];
        }
        $cmd .= ' ' . escapeshellarg($filePath);
        if (!empty($options['auto_orient'])) {
            $cmd .= ' -auto-orient';
        }
        if ($resize) {
            // Handle animated GIFs:
            $cmd .= ' -coalesce';
            if (empty($options['crop'])) {
                $cmd .= ' -resize ' . escapeshellarg($resize . '>');
            } else {
                $cmd .= ' -resize ' . escapeshellarg($resize . '^');
                $cmd .= ' -gravity center';
                $cmd .= ' -crop ' . escapeshellarg($resize . '+0+0');
            }
            // Make sure the page dimensions are correct (fixes offsets of animated GIFs):
            $cmd .= ' +repage';
        }
        if (!empty($options['convert_params'])) {
            $cmd .= ' ' . $options['convert_params'];
        }
        $cmd .= ' ' . escapeshellarg($newFilePath);
        exec($cmd, $output, $error);
        if ($error) {
            error_log(implode('\n', $output));
            return false;
        }
        return true;
    }

    protected function getUserPath()
    {
        if ($this->options['user_dirs']) {
            return $this->getUserId() . '/';
        }
        return '';
    }

    protected function getUploadPath($fileName = null, $version = null)
    {
        $fileName = $fileName ? $fileName : '';
        if (empty($version)) {
            $versionPath = '';
        } else {
            $versionDir = @$this->options['image_versions'][$version]['upload_dir'];
            if ($versionDir) {
                return $versionDir . $this->getUserPath() . $fileName;
            }
            $versionPath = $version . '/';
        }
        return $this->options['upload_dir'] . $this->getUserPath() . $versionPath . $fileName;
    }

    protected function upcountNameCallback($matches)
    {
        $index = isset($matches[1]) ? ((int)$matches[1]) + 1 : 1;
        $ext = isset($matches[2]) ? $matches[2] : '';
        return ' (' . $index . ')' . $ext;
    }

    protected function upcountName($name)
    {
        return preg_replace_callback(
            '/(?:(?: \(([\d]+)\))?(\.[^.]+))?$/',
            [$this, 'upcountNameCallback'],
            $name,
            1
        );
    }

    /**
     * Fix for overflowing signed 32 bit integers,
     *  works for sizes up to 2^32-1 bytes (4 GiB - 1):
     */
    protected function fixIntegerOverflow($size)
    {
        if ($size < 0) {
            $size += 2.0 * (PHP_INT_MAX + 1);
        }
        return $size;
    }


    protected function validate($uploadedFile, $file, $error, $index)
    {
        if ($error) {
            $file->error = $this->getErrorMessage($error);
            return false;
        }
        $contentLength = $this->fixIntegerOverflow(
            (int)$this->request->env('CONTENT_LENGTH')
        );
        $postMaxSize = $this->getConfigBytes(ini_get('post_max_size'));
        if ($postMaxSize && ($contentLength > $postMaxSize)) {
            $file->error = $this->getErrorMessage('post_max_size');
            return false;
        }
        if (!preg_match($this->options['accept_file_types'], $file->name)) {
            $file->error = $this->getErrorMessage('accept_file_types');
            return false;
        }
        if ($uploadedFile && is_uploaded_file($uploadedFile)) {
            $fileSize = $this->getFileSize($uploadedFile);
        } else {
            $fileSize = $contentLength;
        }
        if ($this->options['max_file_size'] && (
                $fileSize > $this->options['max_file_size'] ||
                $file->size > $this->options['max_file_size'])
            ) {
            $file->error = $this->getErrorMessage('max_file_size');
            return false;
        }
        if ($this->options['min_file_size'] &&
            $fileSize < $this->options['min_file_size']) {
            $file->error = $this->getErrorMessage('min_file_size');
            return false;
        }
        if (is_int($this->options['max_number_of_files']) &&
                ($this->countFileObjects() >= $this->options['max_number_of_files']) &&
                // Ignore additional chunks of existing files:
                !is_file($this->getUploadPath($file->name))) {
            $file->error = $this->getErrorMessage('max_number_of_files');
            return false;
        }
        $maxWidth = @$this->options['max_width'];
        $maxHeight = @$this->options['max_height'];
        $minWidth = @$this->options['min_width'];
        $minHeight = @$this->options['min_height'];
        if (($maxWidth || $maxHeight || $minWidth || $minHeight)
           && preg_match($this->options['image_file_types'], $file->name)) {
            list($imgWidth, $imgHeight) = $this->getImageSize($uploadedFile);

            // If we are auto rotating the image by default, do the checks on
            // the correct orientation
            if (@$this->options['image_versions']['']['auto_orient'] &&
                function_exists('exif_read_data') &&
                ($exif = @exif_read_data($uploadedFile)) &&
                (((int)@$exif['Orientation']) >= 5)
            ) {
                $tmp = $imgWidth;
                $imgWidth = $imgHeight;
                $imgHeight = $tmp;
                unset($tmp);
            }

        }
        if (!empty($imgWidth)) {
            if ($maxWidth && $imgWidth > $maxWidth) {
                $file->error = $this->getErrorMessage('max_width');
                return false;
            }
            if ($maxHeight && $imgHeight > $maxHeight) {
                $file->error = $this->getErrorMessage('max_height');
                return false;
            }
            if ($minWidth && $imgWidth < $minWidth) {
                $file->error = $this->getErrorMessage('min_width');
                return false;
            }
            if ($minHeight && $imgHeight < $minHeight) {
                $file->error = $this->getErrorMessage('min_height');
                return false;
            }
        }
        return true;
    }

    protected function getErrorMessage($error)
    {
        return isset($this->error_messages[$error]) ?
            $this->error_messages[$error] : $error;
    }

    protected function getConfigBytes($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val) - 1]);
        switch ($last) {
            case 'g':
                $val *= 1024;//
            case 'm':
                $val *= 1024;//
            case 'k':
                $val *= 1024;
        }
        return $this->fixIntegerOverflow($val);
    }

    protected function getFileSize($filePath, $clearStatCache = false)
    {
        if ($clearStatCache) {
            if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
                clearstatcache(true, $filePath);
            } else {
                clearstatcache();
            }
        }
        return $this->fixIntegerOverflow(filesize($filePath));
    }

    protected function getFileObjects($iterationMethod = 'get_file_object')
    {
        $uploadDir = $this->getUploadPath();
        if (!is_dir($uploadDir)) {
            return [];
        }
        return array_values(array_filter(array_map(
            [$this, $iterationMethod],
            scandir($uploadDir)
        )));
    }

    protected function countFileObjects()
    {
        return count($this->getFileObjects('is_valid_file_object'));
    }

    protected function getImageSize($filePath)
    {
        if ($this->options['image_library']) {
            if (extension_loaded('imagick')) {
                $image = new \Imagick();
                try {
                    if (@$image->pingImage($filePath)) {
                        $dimensions = [$image->getImageWidth(), $image->getImageHeight()];
                        $image->destroy();
                        return $dimensions;
                    }
                    return false;
                } catch (\Exception $e) {
                    error_log($e->getMessage());
                }
            }
            if ($this->options['image_library'] === 2) {
                $cmd = $this->options['identify_bin'];
                $cmd .= ' -ping ' . escapeshellarg($filePath);
                exec($cmd, $output, $error);
                if (!$error && !empty($output)) {
                    // image.jpg JPEG 1920x1080 1920x1080+0+0 8-bit sRGB 465KB 0.000u 0:00.000
                    $infos = preg_split('/\s+/', substr($output[0], strlen($filePath)));
                    $dimensions = preg_split('/x/', $infos[2]);
                    return $dimensions;
                }
                return false;
            }
        }
        if (!function_exists('getimagesize')) {
            error_log('Function not found: getimagesize');
            return false;
        }
        return @getimagesize($filePath);
    }

    protected function handleFormData($file, $index)
    {
        // Handle form data, e.g. $_POST['description'][$index]
    }

    protected function getDownloadUrl($fileName, $version = null, $direct = false)
    {
        if (!$direct && $this->options['download_via_php']) {
            $url = $this->options['script_url'] .
                $this->getQuerySeparator($this->options['script_url']) .
                $this->getSingularParamName() .
                '=' .
                rawurlencode($fileName);
            if ($version) {
                $url .= '&version=' . rawurlencode($version);
            }
            return $url . '&download=1';
        }
        if (empty($version)) {
            $versionPath = '';
        } else {
            $versionUrl = @$this->options['image_versions'][$version]['upload_url'];
            if ($versionUrl) {
                return $versionUrl . $this->getUserPath() . rawurlencode($fileName);
            }
            $versionPath = rawurlencode($version) . '/';
        }
        return $this->options['upload_url'] . $this->getUserPath() .
            $versionPath . rawurlencode($fileName);
    }

    protected function getQuerySeparator($url)
    {
        return strpos($url, '?') === false ? '?' : '&';
    }

    protected function getSingularParamName()
    {
        return substr($this->options['param_name'], 0, -1);
    }

    protected function isValidImageFile($filePath)
    {
        if (!preg_match($this->options['image_file_types'], $filePath)) {
            return false;
        }
        if (function_exists('exif_imagetype')) {
            return @exif_imagetype($filePath);
        }
        $imageInfo = $this->getImageSize($filePath);
        return $imageInfo && $imageInfo[0] && $imageInfo[1];
    }
}
