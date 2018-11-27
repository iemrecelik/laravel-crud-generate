<?php
return function($params){
extract($params);

return '
<template>

<form-modal-component
  :ppmodalinfonames="{
    \'titleName\': $t(\'messages.uploadImage\'),
    \'saveBtnName\': $t(\'messages.update\'),
    \'loadingBtnName\': \'ing\',
    \'cancelBtnName\': $t(\'messages.cancel\'),
  }"
  @saveMethod="uploadForm"
>
  <form
    @submit.prevent
    :id="formIDName"
    v-if="isFormShow"
  >
    <error-msg-list-component></error-msg-list-component>
    <succeed-msg-component></succeed-msg-component>
    
    <images-form-component
      :ppitem="item"
      :ppfiltName="filtName"
      :ppcropsettings="cropSettings"
    >
    </images-form-component>
  </form>

  <div v-else class="ld-ext-over running">
    <div class="ld ld-ring ld-spin m-auto p-2"></div>
  </div>

</form-modal-component>

</template>

<script>
import formModalComponent from \'./FormModalComponent\';
import imagesFormComponent from \'./ImagesFormComponent\';

import { mapState, mapMutations, mapActions } from \'vuex\';

export default {
  name: \'imagesComponent\',
  data () {
    return {
      datas: this.ppdatas,
      filtName: \''.$modelVarName.'ImagesFilt\',
      item: {},
      formShow: false,
      formElement: document.getElementById(this.$parent.modalIDName),
      percentLoadedVal: 0,
      evt: null,
      cropSettings: {
        cropFrameClass: \'col-sm-12\',
        cropRender: true,
        collapseRender: \'all\',
      },
    };
  },
  props: {
    ppdatas: {
      type: Object,
      required: true,
    }
  },
  computed: {
    ...mapState([
      \'routes\',
    ]),
    isUploading: function(){
      return this.evt?true:false;
    },
    evtTotalUpload: function(){
      return `
        ${this.$t(\'messages.total_file_size\')}: 
        ${this.evt.loaded} / ${this.evt.total}
      `;
    },
    percentLoaded: function(){
      return this.percentLoadedVal + \'%\';
    },
    formIDName: function(){
      return this.uniqueDomID(_.toLower(this.datas.formTitleName));
    },
    uploadUrl: function(){
      return this.routes.index + `/${this.datas.id}/upload-images`;
    },
    editUrl: function(){
      return this.routes.index + `/${this.datas.id}/edit-images`;
    },
    isFormShow: function(){
      return this.formShow;
    }
  },
  methods: {
    ...mapMutations([
      \'setErrors\',
      \'setSucceed\',
    ]),

    uploadForm: function(event){
      
      let form = $(\'#\' + this.formIDName)[0];
      let data = new FormData(form);
      
      let btn = event.target;
      btn.classList.add("running");

      $.ajax({
        url: this.uploadUrl,
        enctype: \'multipart/form-data\',
        type: \'POST\',
        data: data,
        processData: false,
        contentType: false,
        cache: false,
      })
      .done((res) => {
        this.setErrors(\'\');
        this.setSucceed(res.succeed);
      })
      .fail((error) => {
        if(error.responseJSON){
          this.setSucceed(\'\');
          this.setErrors(error.responseJSON.errors);
        }
      })
      .then((res) => {
        this.$parent.dataTable.ajax.reload();
      })
      .always(() => {
        this.formElement.scrollTo(0, 0);
        btn.classList.remove("running");
      });
    },
  },
  created(){

    $.get(this.editUrl, (data) => {
      this.item = data;
      this.formShow = true;
    })
    .fail(function(error) {
      console.log(error);
    });
  },
  components: {
    \'form-modal-component\': formModalComponent,
    \'images-form-component\': imagesFormComponent,
  }
}
</script>
';
};