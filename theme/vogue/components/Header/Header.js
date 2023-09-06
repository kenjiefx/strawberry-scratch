import { app } from "../../strawberry/app";
/** Component declarations */
app.component('Header', ($scope, $patch, StateManagerFactory) => {
    const ComponentState = StateManagerFactory.createNewInstance({
        name: 'component',
        patch: $patch,
        scope: $scope
    });
    ComponentState.register('loading').register('active').register('error');
    return {
        render: () => {
            return new Promise((resolve, reject) => {
            });
        }
    };
});
