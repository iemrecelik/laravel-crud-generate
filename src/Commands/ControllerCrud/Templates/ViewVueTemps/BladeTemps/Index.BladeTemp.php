<?php
return function($params){

$modelName = $params['modelName'];
$modelVarName = $params['modelVarName'];

$lwModelName = strtolower($modelName);

return '
@extends(\'admin.base.index\')
@section(\'contents\')
  <'.$lwModelName.'-component
    :pproutes="{ 
      index: \'{{ route(\'admin.'.$lwModelName.'.index\') }}\', 
      dataList: \'{{ route(\'admin.'.$lwModelName.'.dataList\') }}\', 
    }"
    :pperrors="{{ count($errors) > 0?$errors:\'{}\' }}"
    :ppimgfilters="{
      '.$modelVarName.'ImagesFilt: {{ 
        json_encode(config(\'imageFilters.filter.'.$modelVarName.'ImagesFilt\')) 
      }}
    }"
  >
  </'.$lwModelName.'-component>
@endsection
';
};