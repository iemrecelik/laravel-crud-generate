<?php
return function($params){
extract($params);

$modelVar = '$'.$modelVarName;
$modelInstance = $modelName.' '.$modelVar;
$filtName = $modelVarName.'ImagesFilt';

return '
    public function loadImages($request, '.$modelInstance.')
    {
        $oldImgIDs = $request->input(\'altImages\');

        $filters = config(\'imageFilters.filter.'.$filtName.'\');

        /* New images will be saved to storage */
        $imgs = $request->file(\'images.*.file\');
        $crops = $request->input(\'images.*.crops\');

        if($imgs)
            $imgsArr = $this->saveImageToStorage($imgs, $crops, $filters);
        else
            $imgsArr = null;

        /* Images will be deleted */
        $oldImages = '.$modelVar.'->images
                            ->whereNotIn(\'id\', $oldImgIDs);

        if($oldImages->isNotEmpty())
            $this->deleteImageFromStorage($oldImages, $filters);

        /* New images will be saved to databse */
        if($imgsArr)
            '.$modelVar.'->images()->saveMany($imgsArr);
    }
';
};