export default {
    data    : function () {
        return {
            items          : PepperRodeo.items,
            title          : '',
            addedRecipes   : PepperRodeo.addedRecipes,
            unaddedRecipes : Object.assign({}, PepperRodeo.recipes),
            showRecipes    : false,
            recipesToAdd   : [],
            addAnItem      : false
        }
    },
    methods : {
        setShowRecipes($bool) {
            this.showRecipes = $bool;
        },
        setAddAnItem($bool){
            this.addAnItem = $bool;
        },
        addItem(){
            var newItem = {
                quantity         : this.newItemQty,
                name             : this.newItemName,
                item_category_id : this.newItemCategoryId
            };
            this.items.push(newItem);

            this.newItemQty        = '';
            this.newItemName       = '';
            this.newItemCategoryId = '';
        },
        removeItem(itemIndex){
            this.items.splice(itemIndex, 1);
        },
        removeAddedRecipe(recipeIndex){
            this.addedRecipes.splice(recipeIndex, 1);
        },
        addRecipes(recipeIds){
            var self = this;
            recipeIds.forEach(function (recipeId) {
                self.addedRecipes.push(self.unaddedRecipes[recipeId]);
                var recipe = self.unaddedRecipes[recipeId];
                Array.prototype.push.apply(self.items, recipe.items);
                self.recipesToAdd = [];
                delete self.unaddedRecipes[recipeId];
            });

            this.setShowRecipes(false);
        },
        removeRecipe(recipeId, index){
            var self        = this;
            var itemIndexes = [];
            self.items.forEach(function (item) {
                if (item.recipe_id == recipeId) {
                    itemIndexes.push(self.items.indexOf(item));
                }
            });
            itemIndexes = itemIndexes.sort(function (a, b) {
                return b - a
            });
            itemIndexes.forEach(function (index) {
                self.items.splice(index, 1);
            });
            self.removeAddedRecipe(index);
        }
    }
}
