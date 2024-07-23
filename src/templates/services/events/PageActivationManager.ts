import { app } from "../../interfaces/app";
import { EventManagerInterface } from "../EventManager";

/**
 * Interface for subscribing to and dispatching page activation events.
 */
export interface PageActivationManager {
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

app.service<PageActivationManager>('PageActivationManager',(
    EventManager: EventManagerInterface
)=>{
    const PageActivationEventName = 'PAE'
    EventManager.__register(PageActivationEventName)
    return {
        __subscribe:(callback)=>{
            EventManager.__subscribe(PageActivationEventName,callback)
        },
        __dispatch:()=>{
            EventManager.__dispatch(PageActivationEventName)
        }
    }
})