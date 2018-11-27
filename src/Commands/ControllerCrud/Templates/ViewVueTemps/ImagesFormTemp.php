<?php
return function(){

return '
<template>
<div>
  <form-form-component v-for="(filter, key, index) in imgFilters" 
    :key="index"
    :ppsettings="{
      type: \'uploadImage\', 
      fieldName: `images[${index}]`, 
      fieldLabelName: \'image\',
      value: getIndexItem(index),
      filtName,
      cropSettings,
    }"
  >
  </form-form-component>
</div>
</template>

<script>
export default {
  name: \'ImagesFormComponent\',
  data () {
    return {
      items: this.ppitem,
      filtName: this.ppfiltName,
      cropSettings: this.ppcropsettings,
    }
  },
  props: {
    ppitem: {
      type: Array,
      required: false,
      default: function(){
        return [];
      }
    },
    ppfiltName: {
      type: String,
      required: true,
    },
    ppcropsettings: {
      type: Object,
      required: false,
      default: \'\'
    },
  },
  computed: {
    imgFilters: function () {
      return this.$store.state.imgFilters[this.filtName];
    }
  },
  methods: {
    getIndexItem: function (index) {
      return this.items[index] || \'\';
    },
  },
}
</script>
';
};