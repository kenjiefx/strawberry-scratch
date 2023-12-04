import { app } from "../app"
import { EventManagerInterface } from "./EventManager"


/**
 * Handles all the Errors in the application
 */
export interface ErrorHandler {
    InvalidArgumentException: ()=>void
    LogicException:()=>void
    RuntimeException:()=>void
    FatalException:(error:Error)=>void
}

app.service<ErrorHandler>('ErrorHandler',(
    EventManager: EventManagerInterface
)=>{
    EventManager.register('PageErrorEvent')
    return {
        InvalidArgumentException: ()=>{},
        LogicException:()=>{},
        RuntimeException:()=>{},
        FatalException:(error:Error)=>{
            EventManager.dispatch('PageErrorEvent')
            console.error(`FatalException: ${error.message}`)
        }
    }
})
