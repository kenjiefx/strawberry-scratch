import { app } from "../../app";
import { EventManagerInterface } from "../EventManager";
import { PageErrorEvent } from "./PageErrorEvent";

/**
 * Interface for subscribing to and dispatching page activation events.
 */
export interface PageActivationEvent {
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

app.service<PageActivationEvent>('PageActivationEvent',(
    EventManager: EventManagerInterface,
    PageErrorEvent: PageErrorEvent
)=>{
    const PageActivationEventName = 'PAE'
    EventManager.__register(PageActivationEventName)
    return {
        __subscribe:(callback)=>{
            EventManager.__subscribe(PageActivationEventName,callback)
        },
        __dispatch:()=>{
            if (!PageErrorEvent.__hasRenderClearance()){
                EventManager.__dispatch('PEE')
                return
            }
            EventManager.__dispatch(PageActivationEventName)
        }
    }
})