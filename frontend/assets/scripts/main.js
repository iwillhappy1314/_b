import '../../node_modules/cxselect/js/jquery.cxselect.js';

jQuery(document).ready(function($){
  $('.wp-mlcs-selects').cxSelect({
    selects: ['level1', 'level2', 'level3'],
    jsonName: 'name',
    jsonValue: 'id',
  });
});