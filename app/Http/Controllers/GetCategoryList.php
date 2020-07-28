<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Category;
use Arr;
use Cache;

trait GetCategoryList
{
    protected function getList()
    {
        return Category::whereNull('category_id')->with(['sub_cats'])->get();
    }
}
