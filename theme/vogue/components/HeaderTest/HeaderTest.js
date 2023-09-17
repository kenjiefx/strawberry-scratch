import { app } from "../../strawberry/app";
/** Component declarations */
app.component('HeaderTest', ($scope, $patch, StateManager) => {
    StateManager.setScope($scope).setPatcher($patch).register('active').register('error').register('loading');
    return {
        render: () => {
            return new Promise((resolve, reject) => {
            });
        }
    };
});
