define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'study/course/index',
                    add_url: 'study/course/add',
                    edit_url: 'study/course/edit',
                    del_url: 'study/course/del',
                    multi_url: 'study/course/multi',
                    table: 'course',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'subject.title', title: __('Subject.title')},
                        {field: 'title', title: __('Title')},
                        // {field: 'subject_id', title: __('Subject_id')},
                        {field: 'filetype', title: __('Filetype'), searchList: {"1":__('Filetype 1'),"2":__('Filetype 2'),"3":__('Filetype 3')}, formatter: Table.api.formatter.normal},
                        // {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        // {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        // {field: 'mp3file', title: __('Mp3file')},
                        // {field: 'videofile', title: __('Videofile')},
                        {field: 'weigh', title: __('Weigh')},
                        // {field: 'subject.id', title: __('Subject.id')},
                        // {field: 'subject.createtime', title: __('Subject.createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        // {field: 'subject.updatetime', title: __('Subject.updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        // {field: 'subject.weigh', title: __('Subject.weigh')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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