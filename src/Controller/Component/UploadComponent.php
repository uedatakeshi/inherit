<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Utility\Text;

class UploadComponent extends Component
{
    protected $name = 'ueda';

    protected $options;

    protected $error_messages = [
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

    protected $image_objects = [];

    /**
     * initialize method
     *
     * @param array $config
     * @return void
     */
    public function initialize(array $config)
    {
        echo $this->name;
        $this->options = [
        //    'script_url' => $this->get_full_url().'/'.basename($this->get_server_var('SCRIPT_NAME')),
         //   'upload_dir' => dirname($this->get_server_var('SCRIPT_FILENAME')).'/files/',
         //   'upload_url' => $this->get_full_url().'/files/',
            'user_dirs' => false,
            'mkdir_mode' => 0755,
            'param_name' => 'files',
            // Set the following option to 'POST', if your server does not support
            // DELETE requests. This is a parameter sent to the client:
            'delete_type' => 'DELETE',
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
   //         'redirect_allow_target' => '/^'.preg_quote(
    //          parse_url($this->get_server_var('HTTP_REFERER'), PHP_URL_SCHEME)
     //           .'://'
      //          .parse_url($this->get_server_var('HTTP_REFERER'), PHP_URL_HOST)
       //         .'/', // Trailing slash to not match subdomains by mistake
       //       '/' // preg_quote delimiter param
       //     ).'/',
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
                    //'upload_url' => $this->get_full_url().'/thumb/',
                    // Uncomment the following to force the max
                    // dimensions and e.g. create square thumbnails:
                    //'crop' => true,
                    'max_width' => 80,
                    'max_height' => 80
                ]
            ],
            'print_response' => true
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

        echo "here";

        switch ($this->request->env('REQUEST_METHOD')) {
            case 'GET':
            case 'POST':
                $this->post($this->options['print_response']);
                break;
            case 'DELETE':
                $this->delete($this->options['print_response']);
                break;
            default:
                throw new MethodNotAllowedException();
        }
    }

    public function post($printResponse = true)
    {
        if ($this->request->query('_method') === 'DELETE') {
            return $this->delete($printResponse);
        }
        $upload = $this->request->data[$this->options['param_name']];
        print_r($_FILES['files']);
        var_dump($upload);
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
            print_r($files);
        }
    }

    public function delete($printResponse = true)
    {
        echo "delete";
    }

    protected function handleFileUpload($uploadedFile, $name, $size, $type, $error, $index = null, $contentRange = null)
    {
        $file = new \stdClass();
        $file->name = $this->getFileName($uploadedFile, $name, $size, $type, $error, $index, $contentRange);
        var_dump($file->name);
        /*
        $file->size = $this->fixIntegerOverflow((int)$size);
        $file->type = $type;
        if ($this->validate($uploaded_file, $file, $error, $index)) {
            $this->handle_form_data($file, $index);
            $upload_dir = $this->getUploadPath();
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, $this->options['mkdir_mode'], true);
            }
            $filePath = $this->getUploadPath($file->name);
            $append_file = $contentRange && is_file($filePath) && $file->size > $this->get_file_size($filePath);
            if ($uploaded_file && is_uploaded_file($uploaded_file)) {
                // multipart/formdata uploads (POST method uploads)
                if ($append_file) {
                    file_put_contents(
                        $filePath,
                        fopen($uploaded_file, 'r'),
                        FILE_APPEND
                    );
                } else {
                    move_uploaded_file($uploaded_file, $filePath);
                }
            } else {
                // Non-multipart uploads (PUT method support)
                file_put_contents(
                    $filePath,
                    fopen('php://input', 'r'),
                    $append_file ? FILE_APPEND : 0
                );
            }
            $file_size = $this->get_file_size($filePath, $append_file);
            if ($file_size === $file->size) {
                $file->url = $this->get_download_url($file->name);
                if ($this->is_valid_image_file($filePath)) {
                    $this->handle_image_file($filePath, $file);
                }
            } else {
                $file->size = $file_size;
                if (!$contentRange && $this->options['discard_aborted_uploads']) {
                    unlink($filePath);
                    $file->error = $this->get_error_message('abort');
                }
            }
            $this->set_additional_file_properties($file);
        }
        return $file;
         */
    }

    protected function getUserPath()
    {
        if ($this->options['user_dirs']) {
            return $this->get_user_id() . '/';
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

    protected function getUniqueFilename($filePath, $name, $size, $type, $error, $index, $contentRange)
    {
        while (is_dir($this->getUploadPath($name))) {
            $name = $this->upcountName($name);
        }
        // Keep an existing filename if this is part of a chunked upload:
        $uploadedBytes = $this->fixIntegerOverflow((int)$contentRange[1]);
        while (is_file($this->getUploadPath($name))) {
            if ($uploadedBytes === $this->get_file_size($this->getUploadPath($name))) {
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
}
