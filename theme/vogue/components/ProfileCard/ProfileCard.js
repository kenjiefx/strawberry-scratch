import { app } from "../../strawberry/app";
/** Component declarations */
app.component('ProfileCard', ($scope, $patch, StateManager) => {
    StateManager.setScope($scope).setPatcher($patch).register('active').register('error').register('loading');
    return {
        render: () => {
            return new Promise((resolve, reject) => {
                StateManager.switch('active');
            });
        }
    };
});
