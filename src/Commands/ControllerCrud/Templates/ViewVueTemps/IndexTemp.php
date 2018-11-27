<?php
return function($params){

extract($params);

$lwModelName = strtolower($modelName);

$fields = array_merge($addLangFields, $addFields);

$fieldsHtml = '';
$fieldsTbl = '';
foreach ($fields as $field) {

  $fieldsTbl .= '
        <th>{{ $t("messages.'.$field['name'].'") }}</th>
  ';
  $fieldsTbl = trim($fieldsTbl);

  switch ($field['type']) {
    case 'date':
      $fieldsHtml .= '
        { 
          "data": "'.$field['name'].'",
          "render": (data, type, row) => {
            return this.unixTimestamp(data);
          }
        },';
      break;
    
    default:
      $fieldsHtml .= "\n\t\t\t\t{ \"data\": \"{$field['name']}\" },";
      $fieldsHtml = ltrim($fieldsHtml);
      break;
  }
}

if ($crudType !== 'advanced') {
$editBtnRow = 'row += this.editBtnHtml(id);';
$editBtnHtml = '
    editBtnHtml: function(id){
      return  `
        <span 
          data-toggle="tooltip" data-placement="top" 
          title="${this.$t(\'messages.edit\')}"
        >
          <button type="button" class="btn btn-sm btn-success"
            data-toggle="modal" data-target="${this.modalSelector}"
            data-component="${this.formTitleName}-edit-component" 
            data-datas=\'{
              "id": ${id},
              "formTitleName": "${this.formTitleName}"
            }\'
          >
            <i class="icon ion-md-create"></i>
          </button>
        </span>`;
    },
';
}else{
  $editBtnRow = '';
  $editBtnHtml = '';
}

if ($imgModelName) {

$imageBtnRow = 'row += this.imageBtnHtml(id);';

$imagesComp = '
[formTitleName + \'-images-component\']: imagesComponent,
';

$importImagesComp = '
import imagesComponent from \'./ImagesComponent\';
';

$imageBtnHtml = '
    imageBtnHtml: function(id){
      return  `
        <span 
            data-toggle="tooltip" data-placement="top" 
            title="${this.$t(\'messages.image\')}"
          >
          <button type="button" class="btn btn-sm btn-info"
            data-toggle="modal" data-target="${this.modalSelector}"
            data-component="${this.formTitleName}-images-component" 
            data-datas=\'{
              "id": ${id},
              "formTitleName": "${this.formTitleName}"
            }\'
          >
            <i class="icon ion-md-camera"></i>
          </button>
        </span>`;
    },
';
}else{
  $importImagesComp = '';
  $imagesComp = '';
  $imageBtnRow = '';
  $imageBtnHtml = '';
}

return '
<template>
<div>
  <table class="res-dt-table table table-striped table-bordered" 
  style="width:100%">
    <thead>
      <tr>
        '.$fieldsTbl.'
        <th>{{ $t("messages.processes") }}</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th colspan="'.(count($fields) + 1).'">
          <button type="button" class="btn btn-primary"
            data-toggle="modal" 
            :data-target="modalSelector"
            :data-datas=\'`{"formTitleName": "\${formTitleName}"}`\'
            :data-component="`${formTitleName}-create-component`"
          >
            {{ $t(\'messages.add\') }}
          </button>
        </th>
      </tr>
    </tfoot>
  </table>

  <!-- Modal -->
  <div class="modal fade" tabindex="-1" role="dialog" 
    aria-labelledby="formModalLongTitle" aria-hidden="true"
    data-backdrop="static" :id="modalIDName"
  >
    <div class="modal-dialog" role="document">
      <component
        v-if="formModalBody.show"
        :is="formModalBody.component"
        :ppdatas="formModalBody.datas"
      >
      </component>
    </div>
  </div>
  
</div>
</template>

<script>
import createComponent from \'./CreateComponent\';
import editComponent from \'./EditComponent\';
import showComponent from \'./ShowComponent\';
import deleteComponent from \'./DeleteComponent\';
'.trim($importImagesComp).'

import { mapState, mapMutations } from \'vuex\';

let formTitleName = \''.$lwModelName.'\'

export default {
  name: this.componentTitleName,
  data () {
    return {
      modalIDName: \'formModalLong\',
      formTitleName,
      dataTable: null,
    };
  },
  props: {
    pproutes: {
      type: Object,
      required: true,
    },
    pperrors: {
      type: Object,
      required: true,
    },
    ppimgfilters: {
      type: Object,
      required: true,
    },
  },
  computed: {
    ...mapState([
      \'formModalBody\',
      \'routes\',
      \'errors\',
      \'token\',
      \'imgFilters\',
    ]),
    cformTitleName: function(){
      return _.capitalize(this.formTitleName);
    },
    componentTitleName: function(){
      return _.capitalize(this.formTitleName) + \'Component\';
    },
    modalSelector: function(){
      return \'#\' + this.modalIDName;
    },
  },
  methods: {
    ...mapMutations([
      \'setRoutes\',
      \'setErrors\',
      \'setEditItem\',
      \'setImgFilters\',
    ]),
    processesRow: function(id){
      let row = \'\';
      '.$editBtnRow.'
      row += this.deleteBtnHtml(id);
      '.$imageBtnRow.'
      return row;
    },
    '.$editBtnHtml.'
    deleteBtnHtml: function(id){
      return  `
        <span 
            data-toggle="tooltip" data-placement="top" 
            title="${this.$t(\'messages.delete\')}"
          >
          <button type="button" class="btn btn-sm btn-danger"
            data-toggle="modal" data-target="${this.modalSelector}"
            data-component="${this.formTitleName}-delete-component" 
            data-datas=\'{
              "id": ${id},
              "formTitleName": "${this.formTitleName}"
            }\'
          >
            <i class="icon ion-md-trash"></i>
          </button>
        </span>`;
    },
    '.$imageBtnHtml.'
  },
  created(){
    this.setRoutes(this.pproutes);
    this.setErrors(this.pperrors);
    this.setImgFilters(this.ppimgfilters);
  },
  mounted(){
    this.showModalBody(this.modalSelector);

    this.dataTable = this.dataTableRun({
      jQDomName: \'.res-dt-table\',
      url: this.routes.dataList,
      columns: [
        '.$fieldsHtml.'
        {
          "orderable": false,
          "searchable": false,
          "sortable": false,
          "data": "'.$fieldIDName.'",
          "render": ( data, type, row ) => {
              return this.processesRow(data);
          },
          "defaultContent": ""
        },
      ],
    });
  },
  components: {
    [formTitleName + \'-create-component\']: createComponent,
    [formTitleName + \'-edit-component\']: editComponent,
    [formTitleName + \'-show-component\']: showComponent,
    [formTitleName + \'-delete-component\']: deleteComponent,
    '.trim($imagesComp).'
  }
}
</script>
';
};