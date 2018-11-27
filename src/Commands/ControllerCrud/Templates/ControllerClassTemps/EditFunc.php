<?php
return function($params){
extract($params);

$modelVar = '$'.$modelVarName;

$lwMdPath = strtolower($modelPath);


$foreignKey = strtolower($modelName).'.'.$fieldIDName;


if(empty($langModelName)){

$funcHtml = '
	/**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\\'.$modelUsePath.'  '.$modelVar.'
     * @return \Illuminate\Http\Response
     */
    public function edit('.$modelName.' '.$modelVar.')
    {
        return new isAjaxResponse(
            '.$modelVar.', 
            \''.$lwMdPath.'/edit\', 
            [\'item\' => '.$modelVar.']
        );
    }
';

}else{

$funcHtml = '
    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        '.$modelVar.' = '.$modelName.'::with(\''.lcfirst($langModelName).'\')
        ->where(\''.$foreignKey.'\', $id)
        ->first();

        return new isAjaxResponse(
            '.$modelVar.', 
            \''.$lwMdPath.'/edit\', 
            [\'item\' => '.$modelVar.']
        );
    }
';    
}

return $funcHtml;
};