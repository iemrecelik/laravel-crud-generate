<?php
return function($params){

extract($params);

// $fields = array_merge($addLangFields, $addFields);

if ($addLangFields){
  $formInputs = "\n\t<create-lang-form-component>"
                ."</create-lang-form-component>\n";

  $import = "import createLangFormComponent from "
            ."'./CreateLangFormComponent';\n";

  $component = '
  components: {
    \'create-lang-form-component\': createLangFormComponent,
  }
  ';
}else{
  $import = '';
  $component = '';
  $formInputs = '';
}

foreach ($addFields as $field) {
$formInputs .= '
  <form-form-component
    :ppsettings="{
      type: \''.$field['type'].'\', 
      fieldName: \''.$field['name'].'\', 
      value: oldValue(\''.$field['name'].'\')
    }"
  >
  </form-form-component>
';
}

return '
<template>
<div>'.$formInputs.'</div>
</template>

<script>
'.$import.'

export default {
  name: \'CreateFormComponent\',
  methods: {
    oldValue: function(fieldName){
      return this.$store.state.old[fieldName];
    }
  },
  '.$component.'
}
</script>
';
};