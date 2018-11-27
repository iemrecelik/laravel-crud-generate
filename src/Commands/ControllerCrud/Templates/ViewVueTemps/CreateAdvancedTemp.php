<?php
return function($params){
extract($params);

return '
<template>
<form 
    method="POST" 
    enctype="multipart/form-data"
    :action="advancedCreateUrl"
>
  
  <error-msg-list-component></error-msg-list-component>
  <succeed-msg-component></succeed-msg-component>
  
  <form-form-component
      :ppsettings="{type: \'hidden\', fieldName: \'_token\', value: token}"
  >
  </form-form-component>

  <create-form-component></create-form-component>
  <images-form-component
  :ppfiltName="filtName"
  :ppcropsettings="cropSettings"
  >
  </images-form-component>
  <button type="submit" class="btn btn-primary">
      {{ $t(\'messages.save\') }}
  </button>
</form>
</template>

<script>
import createFormComponent from \'./CreateFormComponent\';
import imagesFormComponent from \'./ImagesFormComponent\';

import { mapState, mapMutations } from \'vuex\';

export default {
  name: \'CreateAdvancedComponent\',
  data () {
    return {
      filtName: \''.$modelVarName.'ImagesFilt\',
      cropSettings: {
        cropFrameClass: \'col\',
        cropRender: true,
        collapseRender: \'multi\',
      },
    };
  },
  props: {
    ppimgfilters: {
      type: Object,
      required: true,
    },
    pproutes: {
      type: Object,
      required: true,
    },
    pperrors: {
      type: Object,
      required: true,
    },
    ppsuccess: {
      type: String,
      required: false,
      default: \'\'
    },
    ppoldinput: {
      type: String,
      required: true,
    },
  },
  computed: {
    ...mapState([
        \'routes\',
        \'token\',
      ]),
      advancedCreateUrl: function(){
        return `${this.routes.advancedStore}`; 
      },
  },
  methods: {
    ...mapMutations([
      \'setRoutes\',
      \'setErrors\',
      \'setSucceed\',
      \'setImgFilters\',
      \'setOld\',
    ]),
  },
  created(){
    this.setRoutes(this.pproutes);
    this.setErrors(this.pperrors);
    this.setSucceed(this.ppsuccess);
    this.setImgFilters(this.ppimgfilters);
    this.setOld(JSON.parse(this.ppoldinput));
  },
  components: {
    \'create-form-component\': createFormComponent,
    \'images-form-component\': imagesFormComponent,
  }
}
</script>
';
};