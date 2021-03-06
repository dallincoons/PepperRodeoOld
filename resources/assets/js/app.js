import Vue from 'vue';
import AllGroceryLists from './views/grocerylists/all-grocery-lists.js';
import SingleList from './views/grocerylists/single-list.js';
import SingleRecipe from './views/recipe/single-recipe.js';
import CreateRecipe from './views/recipe/create-recipe.js';
import EditGroceryList from './views/grocerylists/edit-grocery-list.js';
import ShowAllRecipes from './views/recipe/show-all-recipes.js';
import EditRecipe from './views/recipe/edit-single.js';
import CreateGroceryList from './views/grocerylists/create-grocery-list.js';

Vue.use(require('vue-resource'));
Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');

new Vue({
    el: '#PepperRodeoApp',
    components: { AllGroceryLists, SingleRecipe, CreateRecipe, CreateGroceryList, ShowAllRecipes, EditRecipe, SingleList, EditGroceryList }
});

