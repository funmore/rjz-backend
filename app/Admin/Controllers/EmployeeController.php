<?php

namespace App\Admin\Controllers;

use App\Models\Employee;
use App\Models\Company;
use App\Models\Department;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\AdminController;

class EmployeeController extends Controller
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

            $content->header('员工管理');
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

            $content->header('员工管理');
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

            $content->header('员工管理');
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
        return Admin::grid(Employee::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('姓名');
            $grid->mobilephone('手机号');
            $grid->depart_id('部门')->value(function ($id) {
                return $this->getdepartname($id);
            });
            $grid->privileges('审批权限');
            $grid->second_privileges('所级审批权限');
            $grid->admin('行保管理员');
            $grid->openid('openid');
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
        return Admin::form(Employee::class, function (Form $form) {

            //$form->display('id', 'ID');
            $form->text('name', '姓名');
            $form->mobile('mobilephone', '手机号')->format('999 9999 9999');
            $options = array(0 => '根目录');
            foreach ($this->getsubcate(0, 1) as $key=>$value) {
                $options[$key] = $value;
            }
            $form->select('depart_id', '所属部门')->options($options);
            $form->hidden('openid');
            $states = [
                'on'  => 1,
                'off' => 0,
            ];

            $form->switch('privileges', '审批权限')->states($states);
            $form->switch('second_privileges', '审批权限')->states($states);
            $form->switch('admin', '行保管理员')->states($states);
            //$form->display('created_at', 'Created At');
            //$form->display('updated_at', 'Updated At');
            $form->saving(function(Form $form) {
                while (true) {
                    $form->openid = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'), 0, 6);
                    if ((Company::where('openid', $form->openid)->count() + Employee::where('openid', $form->openid)->count()) == 0) {
                        break;
                    }
                }
            });
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
