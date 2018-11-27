<?php
return function($params){

extract($params);

$modelVar = '$'.strtolower($modelName);

if(isset($langModelName)){

    $langHtml = '
        $langParams = array_map(function($lang){
            return new '.$langModelName.'($lang);
        }, $params[\'langs\']);

        $'.lcfirst($modelName).'->'.lcfirst($langModelName).'()->saveMany($langParams);
    ';
}


return '
	/**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\\'.$reqRulesUsePath.'  $request
     * @return \Illuminate\Http\Response
     */
    public function store('.$reqRulesName.' $request)
    {
        $params = $request->all();

        '.$modelVar.' = '.$modelName.'::create($params);
        '.$langHtml.'
        return [\'succeed\' => __(\'messages.add_success\')];
    }
';
};