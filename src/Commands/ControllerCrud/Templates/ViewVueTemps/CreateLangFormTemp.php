<?php
return function($params){
extract($params);

$formInputs = '';

foreach ($addLangFields as $field) {
$formInputs .= '
          <form-form-component
            :ppsettings="{
              type: \''.$field['type'].'\', 
              fieldLabelName: \''.$field['name'].'\',
              fieldName: langFieldName(\''.$field['name'].'\', lang),
              value: oldValue(
                \''.$field['name'].'\', lang.lang_short_name
              )
            }"
          >
          </form-form-component>
';
}

$formInputs .= '
          <form-form-component
            :ppsettings="{
              type: \'hidden\',
              fieldName: langFieldName(\''.$fieldDependsOnLang.'\', lang), 
              value: lang.lang_short_name
            }"
          >
          </form-form-component>
';

return '
<template>
<div>
  <div class="accordion" :id="accordionIDName">  

    <div v-for="lang in langs" 
      class="card"
      :key="loopKey(lang.lang_short_name)"
    >
      <div class="card-header" id="headingOne">
        <h5 class="mb-0">
          <button 
              class="btn btn-link" type="button" 
              data-toggle="collapse" 
              :data-target="getCollapseIDName(lang, true)" 
              aria-expanded="true" 
              :aria-controls="getCollapseIDName(lang)"
            >
            {{ lang.lang_name + ` (${lang.lang_short_name})` }}
          </button>
        </h5>
      </div>

      <div 
        :id="getCollapseIDName(lang)" 
        class="collapse show" aria-labelledby="headingOne" 
        :data-parent="`#${accordionIDName}`"
      >
        <div class="card-body">
        '.$formInputs.'
        </div>
        
      </div>
    </div><!--edn div.card -->
  </div><!--end div.accordion -->
  <form-form-component
    :ppsettings="{
      type: \'selectLangBox\', 
      fieldName: \'languages\',
    }"
  >
  </form-form-component>
</div>
</template>

<script>
import { mapState } from \'vuex\';

export default {
  name: \'CreateLangFormComponent\',
  data () {
    return {
      langs: [],
      uniqID: this.uniqueID(),
    }
  },
  props: {
    pplang: {
      type: String,
      required: false,
      default: \'\'
    },
  },
  computed: {
    ...mapState({
      langsInStore: \'langs\',
      old: \'old\',
      langInStore: \'lang\',
    }),
    lang: function () {
      return this.pplang || this.langInStore;
    },
    accordionIDName: function () {
      return \'accordion\' + this.uniqID;
    },
  },
  methods: {
    loopKey: function(langShortName){
      return langShortName + this.uniqID;
    },
    oldValue: function(field, langShort){
      
      let oldVal = null;
      let isNestedItem = _.has(
        this.old, 
        [\'langs\', langShort, field]
      );

      if(isNestedItem)
        oldVal = this.old[\'langs\'][langShort][field];
      
      return oldVal;  
    },
    getCollapseIDName: function(lang, raw = false){
      let idName = \'collapse\'
        + lang.lang_short_name
        + \'LangCreate\'
        + this.uniqID;

      return raw?\'#\'+idName:idName;
    },
    setFieldLangs: function(){
      let lsNames;
      if (_.isObject(this.old[\'langs\']))
        lsNames = Object.keys(this.old[\'langs\']);
      else
        lsNames = [this.langInStore];

      this.langsInStore.forEach((lang) => {
        if(lsNames.indexOf(lang.lang_short_name) > -1)
          this.langs.push(lang);
      });
    },
    langFieldName: function(fieldName, lang){
      return `langs[${lang.lang_short_name}][${fieldName}]`;
    },
    addSelectedLang: function(lang){
      if(this.isLang(lang) < 0 && lang)
        this.langs.push(lang);
    },
    removeSelectedLang: function(lang){
      let index = this.isLang(lang);
      if(index > -1 && this.lang !== lang.lang_short_name)
        this.langs.splice(index, 1);
    },
    isLang: function (lang) {
      return this.langs.findIndex(
        thislang => thislang.lang_short_name === lang.lang_short_name
      );
    },
  },
  mounted(){
    this.$root.$on(\'setFieldLangs\', this.setFieldLangs);
    this.$root.$on(\'addSelectedLang\', this.addSelectedLang);
    this.$root.$on(\'removeSelectedLang\', this.removeSelectedLang);
  }
}
</script>
';
};