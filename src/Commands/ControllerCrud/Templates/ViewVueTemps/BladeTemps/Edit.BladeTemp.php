<?php
return function($params){

$modelName = $params['modelName'];
$modelVarName = $params['modelVarName'];

$lwModelName = strtolower($modelName);

return '
@extends(\'admin.base.index\')
@section(\'contents\')

  <'.$lwModelName.'-edit-advanced-component
    :pproutes="{ 
      index: \'{{ route(\'admin.'.$lwModelName.'.index\') }}\', 
      dataList: \'{{ route(\'admin.'.$lwModelName.'.dataList\') }}\',
    }"
    :ppitem="{{ $item }}"
    :ppimgs="{{ $item->images }}"
    :pperrors="{{ count($errors) > 0?$errors:\'{}\' }}"
    :ppsuccess="\'{{ session(\'succeed\') ?? \'\' }}\'"
    :ppimgfilters="{ 
      '.$modelVarName.'ImagesFilt: {{ 
        json_encode(config(\'imageFilters.filter.'.$modelVarName.'ImagesFilt\')) 
      }}
    }"
    :ppoldinput="\'{{ json_encode(session()->getOldInput()) }}\'"
  >
  </'.$lwModelName.'-edit-advanced-component>
@endsection
';
};