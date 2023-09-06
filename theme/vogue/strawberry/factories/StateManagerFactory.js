import { app } from "../app";
app.factory('StateManagerFactory', (ErrorHandler) => {
    class StateInstance {
        constructor() {
            this.state = '';
        }
    }
    class StateManager {
        constructor({ reference, patchFn }) {
            this.states = {};
            this.reference = reference;
            if (undefined !== patchFn)
                this.patchFn = patchFn;
        }
        register(name, callback = () => { }) {
            if (this.states.hasOwnProperty(name)) {
                throw new ErrorHandler.InvalidArgumentException();
            }
            this.states[name] = callback;
            return this;
        }
        switch(name) {
            if (!this.states.hasOwnProperty(name)) {
                throw new ErrorHandler.InvalidArgumentException();
            }
            this.reference.state = name;
            this.states[name]();
            this.patchFn();
        }
        getCurrentState() {
            return this.reference.state;
        }
    }
    return {
        createNewInstance: ({ name, scope, patch }) => {
            if (!scope.hasOwnProperty('StateManager')) {
                scope.StateManager = {};
            }
            scope.StateManager[name] = new StateInstance;
            return new StateManager({
                reference: scope.StateManager[name],
                patchFn: patch
            });
        }
    };
});
