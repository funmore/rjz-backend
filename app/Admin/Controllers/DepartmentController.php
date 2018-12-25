<?php

namespace App\Admin\Controllers;

use App\Models\Department;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\AdminController;

class DepartmentController extends Controller
{
    use AdminController;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('部门管理');
            //$content->description('description');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('部门管理');
            //$content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('部门管理');
            //$content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Department::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('部门名称');
            $grid->up_id('上级部门')->value(function ($id) {
                return $this->getdepartname($id);
            });
            $grid->created_at('新建时间');
            $grid->updated_at('更新时间');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Department::class, function (Form $form) {

            //$form->display('id', 'ID');
            $form->text('name', '部门名称');

            $options = array(0 => '根目录');
            foreach ($this->getsubcate(0, 1) as $key=>$value) {
                $options[$key] = $value;
            }
            $form->select('up_id', '上级部门')->options($options);
            //$form->display('created_at', 'Created At');
            //$form->display('updated_at', 'Updated At');
        });
    }
    private function getsubcate($id, $tag) {
        $departs = Department::where('up_id', $id)->get();
        $options = array();
        foreach ($departs as $depart) {
            $prefix = '';
            for ($i=0; $i<$tag; $i+=1) {
                $prefix .= '*   ';
            }
            $options[$depart->id] = $prefix.$depart->name;
            foreach ($this->getsubcate($depart->id, $tag+1) as $key => $value) {
                $options[$key] = $value;
            }
        }
        return $options;
    }

    private function getdepartname($id) {

        if ($id == 0) {
            return '根部门';
        }
        else {
            $depart = Department::where('id', $id)->first();
            return $this->getdepartname($depart->up_id).'->'.$depart->name;
        }
    }
}
