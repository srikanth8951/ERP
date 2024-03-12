<?php

namespace App\Libraries\Storage;

class DefaultStorage
{
    /**
     * Blob File Upload
     * @input : array $file
     * return : array
     */
    public function uploadBlobFile($file)
    {
        $nfilename = $file['newName'] . '.' . $file['extension'];
        $filename = $file['uploadPath'] . $nfilename;
        $im = imagecreatefromstring(file_get_contents($file['blob']));
        imagealphablending($im, false);
        imagesavealpha($im, true);
        if (imagepng($im, $filename)) {
            imagedestroy($im);
            return array(
                'status' => true,
                'name' => $nfilename
            ); 
        } else {
            return array(
                'status' => false,
                'message' => 'Image not uploaded!'
            );
        }

    }

    /**
     * File Upload
     * @input : array $data
     * return : array
     */
    public function uploadFile($data)
    {
        extract($data); // Extract datas
       
        $newname = $newName . '.' .$file->getClientExtension();
        if ($file->isValid() && !$file->hasMoved()) {
            $file->move($uploadPath, $newname);
            return array(
                'status' => true,
                'name' => $file->getName()
            );
        } else {
            return array(
                'status' => false,
                'message' => $file->getErrorString()
            );
        }
        
    }

    /**
     * Unlink file
     * @input : string $file
     */
    protected function unlinkFile($file)
    {
        if (is_readable($file)) {
            unlink($file);
        }
    }

    /**
     * Delete Files
     * @input : string $path
     * @input : array $files 
     */
    public function deleteFiles($path, $files)
    {
        if (is_array($files)) {
            foreach ($files as $file) {
                $this->unlinkFile($path . $file);
            }
        }
    }

    /**
     * Delete File
     * @input : string $path
     * @input : string $files 
     */
    public function deleteFile($path, $file)
    {
        $filePath = $path . $file;
        $this->unlinkFile($filePath);
    }

}