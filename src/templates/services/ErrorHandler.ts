import { app } from "../app"


/**
 * Handles all the Errors in the application
 */
export interface ErrorHandler {
    InvalidArgumentException: ()=>void
    LogicException:()=>void
    RuntimeException:()=>void
}

app.service<ErrorHandler>('ErrorHandler',()=>{
    return {
        InvalidArgumentException: ()=>{},
        LogicException:()=>{},
        RuntimeException:()=>{}
    }
})
