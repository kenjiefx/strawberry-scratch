import { app } from "../app";
app.factory('StateManager', (ErrorHandler) => {
    class StateInstance {
        constructor() {
            this.state = '';
        }
    }
    class StateManager {
        constructor() {
            this.states = {};
        }
        setScope(reference) {
            this.reference = reference;
            return this;
        }
        setPatcher(patchFn) {
            this.patchFn = patchFn;
            return this;
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
            return this.patchFn();
        }
        getCurrentState() {
            return this.reference.state;
        }
    }
    return StateManager;
});
