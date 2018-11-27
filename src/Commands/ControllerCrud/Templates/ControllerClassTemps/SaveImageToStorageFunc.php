<?php
return function(){

return '
    private function saveImageToStorage($images, $crops = null, $filters)
    {
        $imgFileUpload = new ImgFileUpload();
        foreach ($images as $key => $imgVal) {
            
            if (isset($crops[$key])) {
                $crop = $this->convertToCrop($crops[$key]);
                $filt = array_merge_recursive($crop, $filters);
            }else
                $filt = $filters;

            $imgFileUpload->setConfig(
                $imgVal, 
                $filt
            );
            $imgFileUpload->saveImg();
        }

        $imagesArr = array_map(function($path){

            $path = str_replace(\'public\', \'storage\', $path);
            return new Images([\'img_path\' => $path]);

        }, $imgFileUpload->getSavePath());

        return $imagesArr;
    }
';
};