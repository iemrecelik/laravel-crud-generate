<?php
return function($params){

$modelPath = $params['modelPath'];

return '
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view(\''.strtolower($modelPath).'/index\');
    }
';
};