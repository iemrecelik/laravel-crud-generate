<?php
return function($params){
extract($params);

$modelVar = '$'.$modelVarName;

return '
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\\'.$modelUsePath.'  '.$modelVar.'
     * @return \Illuminate\Http\Response
     */
    public function destroy('.$modelName.' '.$modelVar.')
    {
        $images = '.$modelVar.'->images;
        $filters = config(\'imageFilters.filter.'.$modelVarName.'ImagesFilt\');
        
        if($images->isNotEmpty())
            $this->deleteImageFromStorage($images, $filters);

        $res = '.$modelVar.'->delete();
        $msg = [];

        if ($res)
            $msg[\'succeed\'] = __(\'delete_success\');
        else
            $msg[\'error\'] = __(\'delete_error\');

        return $msg;
    }
';
};