import { app } from "../../strawberry/app";
/** Component declarations */
app.component('Router', ($scope, $patch, StateManager, $app) => {
    StateManager.setScope($scope).setPatcher($patch).register('active').register('error').register('loading');
    $app.onReady(() => {
        StateManager.switch('active');
    });
    return {
        render: () => {
            return new Promise((resolve, reject) => {
            });
        }
    };
});
