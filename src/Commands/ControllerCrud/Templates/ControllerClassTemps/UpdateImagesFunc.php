<?php
return function($params){
extract($params);

$modelVar = '$'.$modelVarName;
$modelInstance = $modelName.' '.$modelVar;;

return '
    public function updateImages('.$imgReqRulesName.' $request, '.$modelInstance.')
    {
        $this->loadImages($request, '.$modelVar.');

        return [\'succeed\' => __(\'messages.edit_success\')];
    }
';
};