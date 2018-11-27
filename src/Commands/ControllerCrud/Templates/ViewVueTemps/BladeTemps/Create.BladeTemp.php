<?php
return function($params){

$modelName = $params['modelName'];
$modelVarName = $params['modelVarName'];

$lwModelName = strtolower($modelName);

return '
@extends(\'admin.base.index\')
@section(\'contents\')

  <'.$lwModelName.'-create-advanced-component
    :pproutes="{ 
      index: \'{{ route(\'admin.'.$lwModelName.'.index\') }}\',
      advancedStore: \'{{ route(\'admin.'.$lwModelName.'.advancedStore\') }}\',
    }"
    :pperrors="{{ count($errors) > 0?$errors:\'{}\' }}"
    :ppsuccess="\'{{ session(\'succeed\') ?? \'\' }}\'"
    :ppimgfilters="{ 
      '.$modelVarName.'ImagesFilt: {{ 
        json_encode(config(\'imageFilters.filter.'.$modelVarName.'ImagesFilt\')) 
      }}
    }"
    :ppoldinput="\'{{ json_encode(session()->getOldInput()) }}\'"
  >
  </'.$lwModelName.'-create-advanced-component>
@endsection
';
};