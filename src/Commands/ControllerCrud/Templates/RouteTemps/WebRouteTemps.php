<?php
return function($params){

extract($params);
$modelName = strtolower($modelName);
$routes = '';

if($crudType !== 'modal'){
	$routes .= '
		Route::put(
			\''.$modelName.'/{'.$modelVarName.'}/advanced-update\', 
			\''.$controllerName.'@advancedUpdate\'
		)
		->name(\''.$modelName.'.advancedUpdate\')
		->where([\''.$modelVarName.'\' => \'[0-9]+\']);

		Route::post(
			\''.$modelName.'/advanced-store\', 
			\''.$controllerName.'@advancedStore\'
		)
		->name(\''.$modelName.'.advancedStore\');
	';
}

if($imgModelName || $crudType !== 'modal'){
	$routes .= '
		Route::get(
			\''.$modelName.'/{'.$modelVarName.'}/edit-images\', 
			\''.$controllerName.'@getImages\'
		)
		->name(\''.$modelName.'.editImages\')
		->where([\''.$modelVarName.'\' => \'[0-9]+\']);

		Route::post(
			\''.$modelName.'/{'.$modelVarName.'}/upload-images\', 
			\''.$controllerName.'@updateImages\'
		)
		->name(\''.$modelName.'.updateImages\')
		->where([\''.$modelVarName.'\' => \'[0-9]+\']);
	';
}

if(isset($langModelName)){
	$routes .= '
		Route::get(
			\'books/lang/list\', 
			\'BooksController@getLangs\'
		)
		->name(\'books.langList\');
	';
}

return '
		Route::resource(\''.$modelName.'\', \''.$controllerName.'\');
		Route::post(
			\''.$modelName.'/data-list\', 
			\''.$controllerName.'@getDataList\'
		)
		->name(\''.$modelName.'.dataList\');
		'.$routes.'
';
};