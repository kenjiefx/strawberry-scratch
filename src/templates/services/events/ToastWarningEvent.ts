import { app } from "../../app";
import { EventManagerInterface } from "../EventManager";

/**
 * Interface for subscribing to and dispatching toast error messages
 */
export interface ToastWarningEvent {
    /**
     * Subscribe to the toast warning event.
     * @param callback - The callback function to be called when the page is activated.
     */
    __subscribe:(callback:()=>void)=>void 
    /**
     * Dispatch the toast warning event.
     */
    __dispatch:()=>void
}

app.service<ToastWarningEvent>('ToastWarningEvent',(
    EventManager: EventManagerInterface
)=>{
    const ToastWarningEventName = 'TWE'
    EventManager.__register(ToastWarningEventName)
    return {
        __subscribe:(callback)=>{
            EventManager.__subscribe(ToastWarningEventName,callback)
        },
        __dispatch:()=>{
            EventManager.__dispatch(ToastWarningEventName)
        }
    }
})