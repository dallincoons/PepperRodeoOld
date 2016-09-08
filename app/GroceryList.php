<?php

namespace App;

use App\Traits\Itemable;
use App\Traits\Copyable;
use Illuminate\Database\Eloquent\Model;
use App\Services\ListBuilder;

class GroceryList extends Model
{
    use Itemable, Copyable;

    private $foreignKey = 'grocery_list_id';

    protected $fillable = array('user_id', 'title');

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->morphMany(Item::class, 'itemable');
    }

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class);
    }

    public function addRecipe($recipe)
    {
        $items = ListBuilder::build($recipe->items);
        $this->items()->saveMany($items);
        $this->recipes()->attach($recipe->id);
    }
    public function removeRecipe($recipe)
    {
        $this->recipes()->detach($recipe);
    }

    public function checkOffItem($item)
    {
        $item->isCheckedOff = 1;

        $item->save();
    }

    public function removeItem($item)
    {
        $item->delete();
    }
}
