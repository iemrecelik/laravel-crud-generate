<?php
return function($params){
extract($params);

$modelVar = '$'.$modelVarName;

return '
    public function getImages('.$modelName.' '.$modelVar.')
    {
        return '.$modelVar.'->images;
    }
';
};