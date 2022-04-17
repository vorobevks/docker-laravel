<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $description
 * @property string $name
 * @property double $price
 * @property string $preview_image
 * @property string $images
 * @property integer $category_id
 */
class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'images' => 'array',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
