import { app } from "../../app";
import { EventManagerInterface } from "../EventManager";

/**
 * Interface for subscribing to and dispatching toast error messages
 */
export interface ToastInfoEvent {
    /**
     * Subscribe to the toast info event.
     * @param callback - The callback function to be called when the page is activated.
     */
    __subscribe:(callback:()=>void)=>void 
    /**
     * Dispatch the toast info event.
     */
    __dispatch:()=>void
}

app.service<ToastInfoEvent>('ToastInfoEvent',(
    EventManager: EventManagerInterface
)=>{
    const ToastInfoEventName = 'TIE'
    EventManager.__register(ToastInfoEventName)
    return {
        __subscribe:(callback)=>{
            EventManager.__subscribe(ToastInfoEventName,callback)
        },
        __dispatch:()=>{
            EventManager.__dispatch(ToastInfoEventName)
        }
    }
})