<?php
return function($params){

extract($params);

if(empty($prefix)){

$webGroup = '

Route::group(function(){
		'.trim($addRoutes).'
	});
';

}else{

$webGroup = '

Route::prefix(\''.$prefix.'\')
	->namespace(\''.$namespace.'\')
	// ->middleware(\'auth\')
	->name("'.$name.'")
	->group(function(){
		'.trim($addRoutes).'
	});
';
}

return $webGroup;
};