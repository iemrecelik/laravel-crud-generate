<?php
return function($params){

extract($params);

// $fields = array_merge($addLangFields, $addFields);

if ($addLangFields){
  $formInputs = '
  <edit-lang-form-component
    :ppitem="item"
  >
  </edit-lang-form-component>
  ';

  $import = "import editLangFormComponent from "
            ."'./EditLangFormComponent';\n";

  $component = '
  components: {
    \'edit-lang-form-component\': editLangFormComponent,
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
      value: value(\''.$field['name'].'\')
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
  name: \'EditFormComponent\',
  props: {
    ppitem: {
      type: Object,
      required: true,
    }
  },
  computed: {
    item: function(){
      return this.ppitem;
    },
  },
  methods: {
    value: function(fieldName){
      return this.$store.state.old[fieldName] || this.item[fieldName];
    },
    langFieldName: function(fieldName){
      return `langs[${this.$store.state.lang}][${fieldName}]`;
    },
  },
  '.$component.'
}
</script>
';
};