<?php
return function($params){
extract($params);

$modelVar = '$'.$modelVarName;
$lwMdPath = strtolower($modelPath);
$lwMdPath = str_replace('/', '.', $lwMdPath);


if(isset($langModelName)){

    $langHtml = '
        $langParams = array_map(function($lang){
            return new '.$langModelName.'($lang);
        }, $params[\'langs\']);

        '.$modelVar.'->'.lcfirst($langModelName).'()->saveMany($langParams);
    ';
}

return '
    public function advancedStore('.$advancedReqRulesName.' $request)
    {
        $params = $request->validated();
        unset($params[\'images\']);
        
        '.$modelVar.' = '.$modelName.'::create($params);
        '.$langHtml.'
        $this->loadImages($request, '.$modelVar.');

        $msg = [\'succeed\' => __(\'messages.edit_success\')];
        return redirect()->route(\''.$lwMdPath.'.create\')
                        ->with($msg);
    }
';
};