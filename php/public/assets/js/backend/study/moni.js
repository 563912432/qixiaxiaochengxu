define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'study/moni/index',
                    add_url: 'study/moni/add',
                    edit_url: 'study/moni/edit',
                    del_url: 'study/moni/del',
                    multi_url: 'study/moni/multi',
                    table: 'moni',
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
                        // {field: 'subject_id', title: __('Subject_id')},
                        // {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        // {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'danxuan', title: __('Danxuan')},
                        {field: 'danxuanscore', title: __('Danxuanscore')},
                        {field: 'duoxuan', title: __('Duoxuan')},
                        {field: 'duoxuanscore', title: __('Duoxuanscore')},
                        {field: 'panduan', title: __('Panduan')},
                        {field: 'panduanscore', title: __('Panduanscore')},
                        // {field: 'subject.id', title: __('Subject.id')},
                        {field: 'subject.title', title: __('Subject.title')},
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