<?php
return function($params){
extract($params);
  
return '
<template>
<form 
  method="POST" 
  enctype="multipart/form-data"
  :action="advancedUpdateUrl"
>
  <error-msg-list-component></error-msg-list-component>
  <succeed-msg-component></succeed-msg-component>
  
  <form-form-component
    :ppsettings="{type: \'hidden\', fieldName: \'_method\', value: \'PUT\'}"
  >
  </form-form-component>

  <form-form-component
    :ppsettings="{type: \'hidden\', fieldName: \'_token\', value: token}"
  >
  </form-form-component>

  <edit-form-component
    :ppitem="item"
  >
  </edit-form-component>
  <images-form-component
    :ppitem="imgs"
    :ppfiltName="filtName"
    :ppcropsettings="cropSettings"
  >
  </images-form-component>
  <button type="submit" class="btn btn-primary">
    {{ $t(\'messages.update\') }}
  </button>
  
</form>
</template>

<script>
import editFormComponent from \'./EditFormComponent\';
import imagesFormComponent from \'./ImagesFormComponent\';

import { mapState, mapMutations } from \'vuex\';

export default {
  name: \'EditAdvancedComponent\',
  data () {
    return {
      item: this.ppitem,
      imgs: this.ppimgs,
      filtName: \''.$modelVarName.'ImagesFilt\',
      cropSettings: {
        cropFrameClass: \'col\',
        cropRender: true,
        collapseRender: \'multi\',
      },
    };
  },
  props: {
    ppitem: {
      type: Object,
      required: true,
    },
    ppimgs: {
      type: Array,
      required: true,
    },
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
    advancedUpdateUrl: function(){
      return `${this.routes.index}/${this.item.id}/advanced-update`; 
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
    \'edit-form-component\': editFormComponent,
    \'images-form-component\': imagesFormComponent,
  }
}
</script>
';
};