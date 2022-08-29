<?php

namespace App\Admin\Controllers;

use App\Models\Item;
use App\Models\Category;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ItemController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Товары';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Item());
        $grid->column('id', __('ID'))->sortable();
        $grid->column('name', __('Название'))->sortable();
        $grid->column('preview_image', __('Фото'))->image('', 100,100);
        $grid->column('price', __('Цена'))->sortable();
        $grid->column('article', __('Артикул'));
        $grid->column('categories', __('Категории'))->display(function () {
            return implode("<br>", $this->categories()->pluck('name')->toArray());
        });
        $grid->column('created_at', __('Создано'))->display(function () {
            return $this->created_at->toDateTimeString();
        })->sortable();
        $grid->column('updated_at', __('Обновлено'))->display(function () {
            return $this->created_at->toDateTimeString();
        })->sortable();

        $grid->filter(function($filter){
            $categories = Category::all()->pluck('name', 'id')->toArray();
            $filter->in('categories.category_id', 'Категория')->multipleSelect($categories);
            $filter->like('name', 'Название');
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
        $show = new Show(Item::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('name', __('Название'));
        $show->field('description', __('Описание'));
        $show->field('categories', __('Категории'))->as(function (){
            return implode(', ', $this->categories()->pluck('name')->toArray());
        });
        $show->field('price', __('Цена'));
        $show->field('preview_image', __('Фото'))->image();
        $show->field('images', __('Дополнительные фото'))->image();
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
        $form = new Form(new Item());
        $categories = Category::all()->pluck('name', 'id')->toArray();
        $form->display('id', __('ID'));
        $form->text('name', __('Название'))->required();
        $form->textarea('description', __('Описание'))->required();
        $form->multipleSelect('categories', __('Категория'))->options($categories)->required();
        $form->image('preview_image', __('Основное фото'))->uniqueName()->required();
        $form->text('article', __('Артикул'))->rules('unique:items,article,NULL,id,deleted_at,NULL')->required();
        $form->currency('price', __('Цена'))->required()->symbol('₽');
        $form->multipleImage('images', __('Дополнительные фото'))->uniqueName()->removable()->sortable();
        $form->datetime('created_at', __('Создано'))->readonly();
        $form->datetime('updated_at', __('Обновлено'))->readonly();

        return $form;
    }
}
