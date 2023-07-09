import { app } from '../../strawberry/app';
app.component('Header', ($scope, $patch, $block) => {
    $scope.say_hello = 'This is header component';
    return {
        getNewItem: () => {
            return {};
        },
        findElement: () => {
            return {};
        },
        checkMate: () => null
    };
});
