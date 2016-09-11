<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\GroceryList;
use App\User;
use App\Item;
use App\Recipe;
use Illuminate\Http\Request;

class GroceryListTest extends TestCase
{

    use DatabaseTransactions;
    use testHelpers;

    private $MainUser;
    private $GroceryList;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();

        $this->MainUser = factory(User::class)->create();
        $this->GroceryList = factory(GroceryList::class)->create(['user_id' => $this->MainUser->id]);
    }

    /**
     * @group GroceryList
     * @test
     */
    public function add_item_to_grocery_list_from_array()
    {
        $this->createItem();
        $itemCount = $this->getItemCount();

        $this->GroceryList->addItem([
            'quantity' => 2,
            'name' => 'Ketchup'
        ]);

        $this->assertEquals($this->getItemCount(), ($itemCount + 1));
    }

    /**
     * @group GroceryList
     * @test
     */
    public function adds_recipe_to_grocery_list()
    {
        $recipe = $this->createRecipe();

        $itemCount = $recipe->items->count();

        $this->GroceryList->addRecipe($recipe);

        $this->assertTrue($this->GroceryList->recipes->contains($recipe));
        $this->assertTrue(collect($recipe->fresh()->items->pluck('name'))->contains($this->GroceryList->items->first()->name));
        $this->assertTrue(collect($recipe->fresh()->items->pluck('name'))->contains($this->GroceryList->items->last()->name));
        $this->assertEquals($itemCount, $recipe->fresh()->items->count());
    }

    /**
     * @group GroceryList
     * @test
     */
    public function adds_existing_recipe_to_grocery_list_through_http()
    {
        $recipe = $this->createRecipe();

        $itemCount = $recipe->items->count();

        $request = Request::create('/grocerylist/' . $this->GroceryList->getKey(), 'PUT', ['recipe_id' => $recipe->getKey()]);
        app()->handle($request);

        $this->assertTrue($this->GroceryList->recipes->contains($recipe));
        $this->assertTrue(collect($recipe->fresh()->items->pluck('name'))->contains($this->GroceryList->items->first()->name));
        $this->assertTrue(collect($recipe->fresh()->items->pluck('name'))->contains($this->GroceryList->items->last()->name));
        $this->assertEquals($itemCount, $recipe->fresh()->items->count());

    }

    /**
     * @group GroceryList
     * @test
     */
    public function adds_new_recipe_to_grocery_list_through_http()
    {

        $request = Request::create('/grocerylist/' . $this->GroceryList->getKey(), 'PUT', ['recipe_id' => 0, 'title' => 'Creamy Chicken and Broccoli', 'items' => [['quantity' => 2, 'name' => 'lbs of ground beef'], ['quantity' => 4, 'name' => 'lbs of chicken']]]);
        app()->handle($request);

        $recipe = $this->GroceryList->recipes()->first();
        $this->assertEquals('Creamy Chicken and Broccoli', $recipe->title);
        $this->assertTrue(collect($recipe->fresh()->items->pluck('name'))->contains($this->GroceryList->items->first()->name));
        $this->assertTrue(collect($recipe->fresh()->items->pluck('name'))->contains($this->GroceryList->items->last()->name));
    }

    /**
     * @group GroceryList
     * @test
     */
    public function remove_recipe_from_grocery_list()
    {
        $recipe = $this->createRecipe();

        $this->GroceryList->addRecipe($recipe);

        $this->GroceryList->removeRecipe($recipe);

        $this->assertFalse($this->GroceryList->items->contains($recipe));
    }

    /**
     * @group GroceryList
     * @test
     */
    public function adds_items_to_grocery_list()
    {
        $items = factory(Item::class, 3)->create();

        $this->GroceryList->addItems($items);

        $this->assertCount(3, $this->GroceryList->items);
    }

    /**
     * @group GroceryList
     * @test
     */
    public function copy_grocery_list_to_another_user()
    {
        $newUser = factory(User::class)->create();

        $this->GroceryList->copyTo($newUser);

        $this->assertCount(1, GroceryList::where('user_id', '=', $this->MainUser->id)->get());
        $this->assertCount(1, GroceryList::where('user_id', '=', $newUser->id)->get());
    }

    /**
     * @group GroceryList
     * @test
     */
    public function check_off_item()
    {
        $items = factory(Item::class, 3)->create();
        $this->GroceryList->addItems($items);

        $this->GroceryList->checkOffItem($items[2]);

        $this->assertEquals(1, $items[2]->isCheckedOff);
    }

    /**
     * @group GroceryList
     * @test
     */
     public function remove_item_from_grocery_list()
     {
         $items = factory(Item::class, 3)->create();
         $this->GroceryList->addItems($items);

         $this->GroceryList->removeItem($items[2]);

         $this->assertTrue(!$this->GroceryList->items->contains($items[2]));
     }

    private function createItem()
    {
        $item = factory(Item::class)->create();
        $this->GroceryList->items()->save($item);
    }

    private function getItemCount()
    {
        return $this->GroceryList->items()->get()->count();
    }

    private function createRecipe()
    {
        $recipe = factory(Recipe::class)->create();
        $item = factory(Item::class, 5)->create();

        $recipe->items()->saveMany($item);

        return $recipe;
    }
}
