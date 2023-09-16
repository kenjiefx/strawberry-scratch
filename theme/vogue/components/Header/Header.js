import { app } from "../../strawberry/app";
/** Component declarations */
app.component('Header', ($scope, $patch, StateManager, $app, ProfileCard) => {
    StateManager.setScope($scope).setPatcher($patch).register('active').register('error').register('loading');
    $scope.hello = 'world';
    $app.onReady(() => {
        StateManager.switch('active');
        ProfileCard.render();
    });
    return {
        render: () => {
            return new Promise((resolve, reject) => {
            });
        }
    };
});
