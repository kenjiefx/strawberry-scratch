import { app } from "../../app";
import { EventManagerInterface } from "../EventManager";

/**
 * Interface for subscribing to and dispatching toast error messages
 */
export interface ToastErrorEvent {
    /**
     * Subscribe to the page activation event.
     * @param callback - The callback function to be called when the page is activated.
     */
    __subscribe:(callback:()=>void)=>void 
    /**
     * Dispatch the page activation event.
     */
    __dispatch:()=>void
}

app.service<ToastErrorEvent>('ToastErrorEvent',(
    EventManager: EventManagerInterface
)=>{
    const ToastErrorEventName = 'TEE'
    EventManager.__register(ToastErrorEventName)
    return {
        __subscribe:(callback)=>{
            EventManager.__subscribe(ToastErrorEventName,callback)
        },
        __dispatch:()=>{
            EventManager.__dispatch(ToastErrorEventName)
        }
    }
})