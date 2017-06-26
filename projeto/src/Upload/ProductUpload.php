<?php
namespace CodeExperts\Upload;

use CodeExperts\Traits\Upload;

class ProductUpload
{
    use Upload;

    protected $folder = UPLOAD_FOLDER . "products/";

}