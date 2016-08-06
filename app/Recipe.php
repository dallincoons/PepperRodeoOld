<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Item;
use App\RecipeCategory;
use App\Traits\Itemable;
use App\Traits\Copyable;

class Recipe extends Model
{
    use Itemable, Copyable;

    private $foreignKey = 'recipe_id';

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function category()
    {
        return $this->belongsTo(RecipeCategory::class);
    }

    public function groceryLists()
    {
        return $this->belongsToMany(GroceryList::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignCategory($category_id)
    {
        $this->category_id = $category_id;

        $this->save();
    }
}
