@extends('layouts.app', ['vue' => 'edit-grocery-list'])

@section('content')
    <div class="create-list" v-if="!showRecipes">
        <h2 class="page-title">Edit List</h2>
        {!! Form::model($grocerylist, ['method' => 'POST', 'route' => ['grocerylist.update', $grocerylist->id]]) !!}
            {!! method_field('patch') !!}
            @include('grocerylists.includes.list-form')
        {{Form::close()}}
    </div>
    <div v-if="showRecipes" class="choose-recipe">
        <h3 class="page-title">My Recipes</h3>

        <div class="category-wrapper">
            <ul class="category recipes">
                <li v-for="recipe in unaddedRecipes" class="add-recipe-options">
                    <label class="control control--checkbox"><a>@{{recipe.title}}</a>
                        <input type="checkbox" value="@{{ recipe.id }}" v-model="recipesToAdd" class="recipe-check"/>
                        <div class="control__indicator"></div>
                    </label>
                <li>
            </ul>

            <div class="add-recipe-buttons">
                <button v-on:click="setShowRecipes(false)" class="pr-button save-button"><i class="fa fa-chevron-circle-left"></i> Back</button>
                <button v-on:click="addRecipes(recipesToAdd)" class="pr-button save-button"> Add <i class="fa fa-plus-circle"></i></button>
            </div>
        </div>
    </div>

@endsection
