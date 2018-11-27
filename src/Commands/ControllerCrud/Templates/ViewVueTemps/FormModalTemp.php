<?php
return function(){

return '
<template>
<div class="modal-content">

  <div class="modal-header">
    <h5 class="modal-title" id="formModalLongTitle">
      {{titleName}}
    </h5>
    <button type="button" class="close" 
    data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>

  <div class="modal-body">
    <slot></slot>
  </div>

  <div class="modal-footer">
    <button 
      type="button" 
      class="btn btn-primary save-form ld-ext-right"
      @click="saveMethod"
    >
      {{saveBtnName}}
      <div class="ld ld-ring ld-spin p-2"></div>
    </button>
    <button type="button" class="btn btn-secondary" 
    data-dismiss="modal">
      {{cancelBtnName}}
    </button>
  </div>

</div><!-- div.modal-content -->
</template>

<script>
export default {
  name: \'FormModalComponent\',
  data () {
    return {
      modalInfoNames: this.ppmodalinfonames,
    }
  },
  props: {
    ppmodalinfonames: {
      type: Object,
      required: true,
    },
  },
  computed: {
    titleName: function () {
      return  this.modalInfoNames.titleName 
              || this.$t(\'messages.modal\');
    },
    saveBtnName: function () {
      return  this.modalInfoNames.saveBtnName 
              || this.$t(\'messages.save\');
    },
    cancelBtnName: function () {
      return  this.modalInfoNames.cancelBtnName 
              || this.$t(\'messages.cancel\');
    },
  },
  methods: {
    saveMethod: function (event) {
      this.$emit(\'saveMethod\', event);
    }
  }
}
</script>
';
};