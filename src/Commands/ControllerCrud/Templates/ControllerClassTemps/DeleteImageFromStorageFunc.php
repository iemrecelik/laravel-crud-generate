<?php
return function($params){

$imgModelName = $params['imgModelName'];

return '
    private function deleteImageFromStorage($oldImages, $filters)
    {
        $deleteImgs = [];
        foreach ($oldImages as $oldImage) {

            $path = $oldImage->img_path;
            $dltImgs = array_map(
                
                function($filt) use ($path){
                    return "/public/upload/images/{$filt}/{$path}";
                }, 
                array_keys($filters)
            );

            $deleteImgs = array_merge($deleteImgs, $dltImgs);
        }

        Storage::delete($deleteImgs);
        '.$imgModelName.'::destroy($oldImages->pluck(\'id\')->all());
    }
';
};