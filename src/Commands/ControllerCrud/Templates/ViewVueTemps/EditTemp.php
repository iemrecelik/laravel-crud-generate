<?php
return function(){

return '
<template>
<form-modal-component
  :ppmodalinfonames="{
    \'titleName\': $t(\'messages.edit\'),
    \'saveBtnName\': $t(\'messages.update\'),
    \'cancelBtnName\': $t(\'messages.cancel\'),
  }"
  @saveMethod="updateForm"
>
  <form
    v-if="isFormShow"
    @submit.prevent
    :id="formIDName"
  >
    <error-msg-list-component></error-msg-list-component>
    <succeed-msg-component></succeed-msg-component>
    
    <edit-form-component
      :ppitem="item"
    >
    </edit-form-component>
  </form>

  <div v-else class="ld-ext-over running">
    <div class="ld ld-ring ld-spin m-auto p-2"></div>
  </div>

</form-modal-component>
</template>

<script>
import formModalComponent from \'./FormModalComponent\';
import editFormComponent from \'./EditFormComponent\';

import { mapState, mapMutations, mapActions } from \'vuex\';

export default {
  name: \'editComponent\',
  data () {
    return {
      datas: this.ppdatas,
      item: {},
      formShow: false,
      formElement: document.getElementById(this.$parent.modalIDName),
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
    formIDName: function(){
      return this.uniqueDomID(_.toLower(this.datas.formTitleName));
    },
    updateUrl: function(){
      return this.routes.index + \'/\' + this.datas.id;
    },
    editUrl: function(){
      return this.routes.index + `/${this.datas.id}/edit`;
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

    updateForm: function(){
      let form = $(\'#\' + this.formIDName);

      $.ajax({
        url: this.updateUrl,
        type: \'PUT\',
        dataType: \'JSON\',
        data: form.serialize(),
      })
      .done((res) => {
        this.setErrors(\'\');
        this.item = res.updatedItem;
        this.setSucceed(res.succeed);
      })
      .fail((error) => {
        if(error.responseJSON)
          this.setSucceed(\'\');
          this.setErrors(error.responseJSON.errors);
      })
      .then((res) => {
        this.$parent.dataTable.ajax.reload();
      })
      .always(() => {
        this.formElement.scrollTo(0, 0);
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
    \'edit-form-component\': editFormComponent,
  }
}
</script>
';
};