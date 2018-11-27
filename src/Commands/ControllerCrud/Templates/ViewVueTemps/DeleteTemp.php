<?php
return function(){
  
return '
<template>

<form-modal-component
  :ppmodalinfonames="{
    \'titleName\': $t(\'messages.delete\'),
    \'saveBtnName\': $t(\'messages.delete\'),
    \'cancelBtnName\': $t(\'messages.cancel\'),
  }"
  @saveMethod="deleteData"
>
  {{ $t(\'messages.delete_info\') }}
</form-modal-component>

</template>

<script>
import formModalComponent from \'./FormModalComponent\';

import { mapState, mapMutations, mapActions } from \'vuex\';

export default {
  name: \'editComponent\',
  data () {
    return {
      datas: this.ppdatas,
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
    deleteUrl: function(){
      return this.routes.index + `/${this.datas.id}`;
    },
  },
  methods: {
    ...mapMutations([
      \'setErrors\',
      \'setSucceed\',
    ]),

    deleteData: function(){

      $.ajax({
        url: this.deleteUrl,
        type: \'DELETE\',
      })
      .fail((error) => {
        console.log(error);
      })
      .then((res) => {
        this.$parent.dataTable.ajax.reload();
        $(this.$parent.modalSelector).modal(\'hide\');
      });
    },
  },
  components: {
    \'form-modal-component\': formModalComponent,
  }
}
</script>
';
};