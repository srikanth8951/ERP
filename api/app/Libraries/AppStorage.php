<?php

namespace App\Libraries;

class AppStorage
{
    private static $storageType = 'default';
    private static $fileStorage;
    private static $uploadPath = ROOTPATH . 'writable/uploads/';

    /**
     * Init
     * @input : array $config
     */
    public static function init($config)
    {
        // Set storage type
        $storagetype = $config['storageType'] ?? '';
        if (self::$storageType) {
            switch (self::$storageType) {
                case 'aws':
                    self::$fileStorage = new \App\Libraries\Storage\DefaultStorage();
                    break;
                default:
                    self::$fileStorage = new \App\Libraries\Storage\DefaultStorage();
            }
        }
        // Set upload path
        $uploadpath = $config['uploadPath'] ?? '';
        if ($uploadpath) {
            self::setUploadPath($uploadpath);
        }
    }

    /**
     * Set upload path
     * @input : string $path
     */
    public static function setUploadPath($path)
    {
        self::$uploadPath = $path;
        if (!file_exists(self::$uploadPath)) {
            mkdir(self::$uploadPath);
        }
    }

    /**
     * Save file
     * @input : FileObject/Blob  $uploadFile, string $uploadType
     * return: array
     */
	public static function saveFile($uploadFile, $uploadType = 'formdata')
	{
		$json = array();
		$json['status'] = false;
		$file_upload = false;

		if (!empty($uploadFile)) {
			if ($uploadType == 'blob') {

			} else {
				$randomFilename = $uploadFile->getRandomName();
				$newFilename = $randomFilename ? pathinfo($randomFilename, PATHINFO_FILENAME) : '';
				$file_data = array(
					'file' => $uploadFile,
					'newName' => $newFilename,
					'uploadPath' => self::$uploadPath
				);
				$file_upload = self::$fileStorage->uploadFile($file_data);
			}

			$file_upload_status = isset($file_upload['status']) ? $file_upload['status'] : false;
			if ($file_upload_status) {
				$json['status'] = 'success';
				$json['message'] = 'File uploaded';
				$json['filename'] = $file_upload['name'];
			} else {
				$json['status'] = 'error';
				$json['message'] = $file_upload['message'];
			}
		} else {
			$json['status'] = 'error';
			$json['message'] = 'Please upload valid file!';
		}

		return $json;
	}

    /**
     * Delete file
     * @input : string $file 
     */
	public static function deleteFile($file)
	{
		if ($file) {
			self::$fileStorage->deleteFile(self::$uploadPath, $file);
		}
	}

    /**
     * Delete files
     * @input : array $file 
     */
	public static function deleteFiles($file)
	{
		if ($file) {
			if (is_array($file)) {
				self::$fileStorage->deleteFiles(self::$uploadPath, $file);
			}
		}
	}
}
