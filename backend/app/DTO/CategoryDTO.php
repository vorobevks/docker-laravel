<?php

namespace App\DTO;

use Illuminate\Http\UploadedFile;

class CategoryDTO
{
    private $name;
    private $image;
    private $parent_id;

    public function __construct(string $name, UploadedFile $image, int $parent_id = null)
    {
        $this->name = $name;
        $this->image = $image;
        $this->parent_id = $parent_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImage(): UploadedFile
    {
        return $this->image;
    }

    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

}
