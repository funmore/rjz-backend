<?php

namespace App\Admin\Controllers;

use App\Models\Company;

use App\Models\Employee;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\AdminController;

class CompanyController extends Controller
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

            $content->header('租赁公司管理');
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

            $content->header('租赁公司管理');
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

            $content->header('租赁公司管理');
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
        return Admin::grid(Company::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('公司名称');
            $grid->linkman('联系人');
            $grid->phone('联系电话');
            $grid->openid('OpenId');
            $grid->created_at('创建时间');
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
        return Admin::form(Company::class, function (Form $form) {

            //$form->display('id', 'ID');
            $form->text('name', '公司名称');
            $form->text('linkman', '联系人');
            $form->mobile('phone', '联系电话')->format('999 9999 9999');
            $form->hidden('openid');
            $form->saving(function(Form $form) {
                while (true) {
                    $form->openid = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'), 0, 6);
                    if ((Company::where('openid', $form->openid)->count() + Employee::where('openid', $form->openid)->count()) == 0) {
                        break;
                    }
                }
            });
            //$form->display('created_at', '创建时间');
            //$form->display('updated_at', '更新时间');
        });
    }
}
