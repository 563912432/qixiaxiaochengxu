define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'study/exam/index',
                    add_url: 'study/exam/add',
                    edit_url: 'study/exam/edit',
                    del_url: 'study/exam/del',
                    multi_url: 'study/exam/multi',
                    table: 'exam',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'title', title: __('Title')},
                        // {field: 'subject_id', title: __('Subject_id')},
                        // {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        // {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'start', title: __('Start')},
                        {field: 'end', title: __('End')},
                        {field: 'timelong', title: __('Timelong')},
                        // {field: 'subject.id', title: __('Subject.id')},
                        {field: 'subject.title', title: __('Subject.title')},
                        // {field: 'subject.createtime', title: __('Subject.createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        // {field: 'subject.updatetime', title: __('Subject.updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        // {field: 'subject.weigh', title: __('Subject.weigh')},
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                              {
                                name:'edit',
                                text:'',
                                title:'编辑',
                                icon: 'fa fa-pencil',
                                extend: 'data-toggle="tooltip"',
                                classname: 'btn btn-xs btn-success btn-editone',
                                url: 'study/exam/edit',
                                hidden:function(row){
                                  if(row.overtime === 1){
                                    return true;
                                  }
                                }
                              },
                              {
                                name:'del',
                                text:'',
                                title:'删除',
                                icon: 'fa fa-trash',
                                extend: 'data-toggle="tooltip"',
                                classname: 'btn btn-xs btn-danger btn-delone',
                                url: 'study/exam/del',
                                hidden:function(row){
                                  if(row.overtime === 1){
                                    return true;
                                  }
                                }
                              }
                            ],
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