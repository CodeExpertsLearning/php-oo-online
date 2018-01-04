<?php
namespace CodeExperts\Traits;

trait Upload
{
    private $file;

    public function setFolder($folder)
    {
        $this->folder = $folder;
        return $this;
    }

    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    public function upload()
    {
        $images = [];

        for($i = 0; $i < count($this->file['name']); $i++)
        {
            $ext = strrchr($this->file['name'][$i], ".");
            $newName = md5($this->file['name'][$i] . microtime()) . $ext;

            if(move_uploaded_file($this->file['tmp_name'][$i], $this->folder . $newName)) {
                $images[] = $newName;
            }
        }

        return $images;
    }
}