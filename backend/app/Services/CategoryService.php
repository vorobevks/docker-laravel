<?php

namespace App\Services;

use App\DTO\CategoryDTO;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    private $imageService;
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }
    public function create(CategoryDTO $categoryDTO): ?Category
    {
        $category = new Category();
        $category->name = $categoryDTO->getName();
        $image = $this->imageService->upload($categoryDTO->getImage());
        $category->image = $image;
        $category->parent_id = $categoryDTO->getParentId();
        if (!$category->save()) {
            return null;
        }
        return $category;
    }

    public function getAll(): Collection
    {
        return Category::all();
    }

}
