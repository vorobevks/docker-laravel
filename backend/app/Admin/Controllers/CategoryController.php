<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;


class CategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Категории';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Category());

        $grid->column('id', __('ID'))->sortable();
        $grid->column('name', __('Название'));
        $grid->column('parent_id', __('Родительская категория'))->display(function (){
            return $this->parent ? $this->parent->name : null;
        });
        $grid->column('image', __('Изображение'))->image();

        $grid->column('created_at', __('Создано'))->display(function () {
            return $this->created_at->toDateTimeString();
        });
        $grid->column('updated_at', __('Обновлено'))->display(function () {
            return $this->created_at->toDateTimeString();
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Category::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('name', __('Название'));
        $show->field('image', __('Изображение'))->image();
        $show->field('created_at', __('Создано'));
        $show->field('updated_at', __('Обновлено'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($id = null)
    {
        if ($id) {
            $parentCategories = Category::where('id', '<>', $id)->pluck('name', 'id')->all();
        } else {
            $parentCategories = Category::pluck('name', 'id')->all();
        }
        $form = new Form(new Category());
        $form->display('id', __('ID'));
        $form->text('name', __('Название'))->required();
        $form->select('parent_id', __('Родительская категория'))->options($parentCategories);
        $form->file('image', __('Изображение'))->required();
        $form->datetime('created_at', __('Создано'))->readonly();
        $form->datetime('updated_at', __('Обновлено'))->readonly();

        return $form;
    }


    /**
     * Переопределим метод родителя
     * @param $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description['edit'] ?? trans('admin.edit'))
            ->body($this->form($id)->edit($id));
    }
}
