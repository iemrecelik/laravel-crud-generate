<?php
return function($params){

$modelPath = $params['modelPath'];

return '
	/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view(\''.strtolower($modelPath).'/create\');
    }
';
};