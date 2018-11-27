<?php
return function($params){

extract($params);

$modelVar = '$'.$modelVarName;

return '
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\\'.$modelUsePath.'  '.$modelVar.'
     * @return \Illuminate\Http\Response
     */
    public function show('.$modelName.' $'.$modelVarName.')
    {
        //
    }
';
};