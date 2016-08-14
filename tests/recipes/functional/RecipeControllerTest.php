<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Recipe;
use App\User;
use App\Item;

class RecipeControllerTest extends TestCase
{
    use DatabaseTransactions;
    use testHelpers;

    protected $Recipes;
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();

    }

    /**
     * @group recipe-controller
     * @test
     */
    public function views_recipe_dashboard_and_views_links_for_recipes()
    {
        $this->buildSampleRecipe();

        $firstRecipe = $this->Recipes->first();
        $lastRecipe = $this->Recipes->last();

        $this->visit('recipe')
             ->see($firstRecipe->title)
             ->see($lastRecipe->title);
    }

    /**
     * @group recipe-controller
     * @test
     */
    public function click_recipe_link_and_visit_individual_recipe_page()
    {
        $this->buildSampleRecipe();

        $firstRecipe = $this->Recipes->first();

        $this->visit('recipe')
            ->click($firstRecipe->title)
            ->see($firstRecipe->title);
    }

    /**
     * @group recipe-controller
     * @test
     */
    public function submit_from_to_create_a_new_recipe()
    {
        $recipeTitle = 'Creamy Chicken and Rice';
        $recipeItems = [['quantity' => 2, 'name' => 'lbs of ground beef'], ['quantity' => 4, 'name' => 'lbs of chicken']];

        $this->visit('recipe/create');

        //@todo create dynamic form

        $this->json('POST', 'recipe', ['title' => $recipeTitle, 'recipeFields' => $recipeItems]);

        $recipe = $this->user->recipes()->first();

        $this->assertEquals($recipeTitle, $recipe->title);
        $this->assertEquals($recipeItems[0]['name'], $recipe->items()->first()->name);
        $this->assertEquals($recipeItems[1]['name'], $recipe->items()->find(2)->name);
    }

    private function buildSampleRecipe()
    {
        $this->user->recipes()->save(factory(Recipe::class)->make());
        $this->Recipes = factory(Recipe::class, 3)
            ->create(['user_id' => $this->user->id])
            ->each(function($recipe){
                $recipe->items()->saveMany(factory(Item::class, 5)->make());
            });

    }
}