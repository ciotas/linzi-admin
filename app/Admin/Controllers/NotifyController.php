<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Notify;
use App\Models\User;
use Carbon\Carbon;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class NotifyController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Notify(), function (Grid $grid) {
            $grid->id->sortable();
            $grid->user_id->display(function ($val) {
               return User::find($val)->name;
            });
            $grid->duration;
            $grid->switch->switch();
            $grid->updated_at->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');

            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new Notify(), function (Show $show) {
            $show->id;
            $show->duration;
            $show->switch;
            $show->updated_at;
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Notify(), function (Form $form) {
            $form->display('id');
            $form->select('user_id', '用户')->options(User::all()->pluck('name', 'id'));
            $form->text('duration')->rules('required|numeric');
            $form->switch('switch');

            $form->display('updated_at');
        });
    }
}
