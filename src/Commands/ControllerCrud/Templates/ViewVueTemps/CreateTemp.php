<?php
return function(){
  
return '
<template>
<form-modal-component
  :ppmodalinfonames="{
    \'titleName\': $t(\'messages.create\'),
    \'saveBtnName\': $t(\'messages.save\'),
    \'cancelBtnName\': $t(\'messages.cancel\'),
  }"
  @saveMethod="saveForm"
>
  <form
    @submit.prevent
    :id="formIDName"
  >
    <error-msg-list-component></error-msg-list-component>
    <succeed-msg-component></succeed-msg-component>
    
    <create-form-component>
    </create-form-component>
  </form>

</form-modal-component>

</template>

<script>
import formModalComponent from \'./FormModalComponent\';
import createFormComponent from \'./CreateFormComponent\';

import { mapState, mapMutations } from \'vuex\';

export default {
  name: \'createComponent\',
  data () {
    return {
      datas: this.ppdatas,
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
  },
  methods: {
    ...mapMutations([
      \'setErrors\',
      \'setSucceed\',
    ]),
    saveForm: function(){
      let form = $(\'#\' + this.formIDName);

      $.ajax({
        url: this.routes.index,
        type: \'POST\',
        dataType: \'JSON\',
        data: form.serialize(),
      })
      .done((res) => {
        this.setErrors(\'\');
        this.setSucceed(res.succeed);
        document.getElementById(this.formIDName).reset();
      })
      .fail((error) => {
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
  components: {
    \'form-modal-component\': formModalComponent,
    \'create-form-component\': createFormComponent,
  }
}
</script>
';
};