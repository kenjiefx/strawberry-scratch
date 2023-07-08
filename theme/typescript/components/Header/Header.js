import { app } from '../../strawberry/app';
app.component('Header', ($scope, $patch, $block) => {
    $scope.say_hello = 'Hello World!';
    $block({
        name: '@ErrorMessageBlock',
        each: (element) => {
            element.$element.dataset.helloWorld = '123';
        }
    });
    $scope.events = {
        click: (button) => {
            button.addClass('remove');
        }
    };
    return {
        getNewItem: () => {
            return {};
        }
    };
});
