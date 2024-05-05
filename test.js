import {app} from "../app";
app.helper('BlockManager', ($scope, $patch, FatalException, AppConfig) => {
    class __ManagerHelper {
        constructor() {
        }
        __setScope(scope) {
            this.__scope = scope;
        }
        __setPatch(patch) {
            this.__patch = patch;
        }
        __setBlockNamespace(blockNamespace) {
            this.__blockNamespace = blockNamespace;
            const tokens = blockNamespace.split('/');
            if (tokens[0] !== '' || tokens[1] !== 'BlockManager' || tokens[2].length === 0 || tokens[3] !== '') {
                throw new FatalException(`invalid block name structure ${blockNamespace}`);
            }
        }
        __switch(name) {
            return new Promise(async (resolve, reject) => {
                try {
                    this.__scope.state = name;
                    resolve(null);
                }
                catch (error) {
                    reject(error);
                }
            });
        }
        __getCurrentState() {
            return this.__scope.state;
        }
        __bind(blockName) {
            const manager = new __ManagerHelper();
            manager.__setScope($scope);
            manager.__setPatch($patch);
            manager.__setBlockNamespace(blockName);
            return manager;
        }
    }
    const manager = new __ManagerHelper();
    return manager;
});
