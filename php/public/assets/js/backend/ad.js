define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

  var Controller = {
	index: function () {
	  // 初始化表格参数配置
	  Table.api.init({
		extend: {
		  index_url: 'ad/index',
		  add_url: 'ad/add',
		  edit_url: 'ad/edit',
		  del_url: 'ad/del',
		  multi_url: 'ad/multi',
		  table: 'ad',
		}
	  });

	  var table = $("#table");

	  // 初始化表格
	  table.bootstrapTable({
		url: $.fn.bootstrapTable.defaults.extend.index_url,
		pk: 'id',
		sortName: 'id',
		search: false,
		showToggle: false,
		columns: [
		  [
			{checkbox: true},
			{field: 'title', title: __('Title')},
			{field: 'weight', operate: false, title: __('Weight')},
			{field: 'image', operate: false, title: __('Image'), formatter: Table.api.formatter.image},
			{field: 'url', operate: false, title: __('Url'), formatter: Table.api.formatter.url},
			{field: 'video', operate: false, title: __('Video'), formatter: Table.api.formatter.url},
			{field: 'remark', operate: false, title: __('Remark')},
			{
			  field: 'createtime',
			  title: __('Createtime'),
			  visible: false,
			  operate: 'RANGE',
			  addclass: 'datetimerange',
			  formatter: Table.api.formatter.datetime
			},
			{
			  field: 'updatetime',
			  title: __('Updatetime'),
			  visible: false,
			  operate: 'RANGE',
			  addclass: 'datetimerange',
			  formatter: Table.api.formatter.datetime
			},
			{
			  field: 'operate',
			  title: __('Operate'),
			  table: table,
			  events: Table.api.events.operate,
			  formatter: Table.api.formatter.operate
			}
		  ]
		]
	  });


	  // 为表格绑定事件
	  Table.api.bindevent(table);
	},
	add: function () {
	  Controller.api.bindevent();
	},
	edit: function () {
	  Controller.api.bindevent();
	},
	api: {
	  bindevent: function () {
		Form.api.bindevent($("form[role=form]"));
	  }
	}
  };
  return Controller;
});