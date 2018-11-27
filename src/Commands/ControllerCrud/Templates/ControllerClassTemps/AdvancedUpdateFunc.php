<?php
return function($params){
extract($params);

$modelVar = '$'.$modelVarName;
$modelInstance = $modelName.' '.$modelVar;
$lwMdPath = strtolower($modelPath);
$lwMdPath = str_replace('/', '.', $lwMdPath);

if(isset($langModelName)){

    $langHtml = '
       '.$modelVar.'->updateMany([
            \'childDatas\' => $params[\'langs\'],
            \'childName\' => \''.lcfirst($langModelName).'\',
            \'childInstance\' => new '.$langModelName.'(),
        ]);
    ';
}


return '
    public function advancedUpdate('.$advancedReqRulesName.' $request, '.$modelInstance.')
    {
        $params = $request->validated();
        unset($params[\'images\']);
        
        '.$modelVar.'->fill($params)->save();
        '.$langHtml.'
        $this->loadImages($request, '.$modelVar.');

        $msg = [\'succeed\' => __(\'messages.edit_success\')];
        return redirect()->route(\''.$lwMdPath.'.edit\', '.$modelVar.'->'.$fieldIDName.')
                        ->with($msg);
    }
';
};