import { app } from "../app";
app.service('ErrorHandler', () => {
    return {
        InvalidArgumentException: () => { },
        LogicException: () => { },
        RuntimeException: () => { }
    };
});
