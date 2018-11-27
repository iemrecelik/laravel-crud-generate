<?php
return function($params){
extract($params);

$modelVar = '$'.$modelVarName;

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
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Http\Requests\\'.$reqRulesUsePath.'  $request
     * @param  \App\Models\\'.$modelUsePath.'  '.$modelVar.'
     * @return \Illuminate\Http\Response
     */
    public function update('.$reqRulesName.' $request, '.$modelName.' '.$modelVar.')
    {
        $params = $request->validated();

        '.$modelVar.'->fill($params)->save();
        '.$langHtml.'
        return [
            \'updatedItem\' => '.$modelVar.',
            \'succeed\' => __(\'messages.edit_success\')
        ];
    }
';
};