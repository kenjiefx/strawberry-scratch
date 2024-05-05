import { app } from "../../app";
import { EventManagerInterface } from "../EventManager";

/**
 * Interface for subscribing to and dispatching toast error messages
 */
export interface ToastSuccessEvent {
    /**
     * Subscribe to the toast success event.
     * @param callback - The callback function to be called when the page is activated.
     */
    __subscribe:(callback:()=>void)=>void 
    /**
     * Dispatch the toast success event.
     */
    __dispatch:()=>void
}

app.service<ToastSuccessEvent>('ToastSuccessEvent',(
    EventManager: EventManagerInterface
)=>{
    const ToastSuccessEventName = 'TSE'
    EventManager.__register(ToastSuccessEventName)
    return {
        __subscribe:(callback)=>{
            EventManager.__subscribe(ToastSuccessEventName,callback)
        },
        __dispatch:()=>{
            EventManager.__dispatch(ToastSuccessEventName)
        }
    }
})