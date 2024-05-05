import { app } from "../../app";
import { EventManagerInterface } from "../EventManager";

/**
 * Interface for subscribing to and dispatching page activation events.
 */
export interface PageErrorEvent {
    /**
     * Subscribe to the page activation event.
     * @param callback - The callback function to be called when the page is activated.
     */
    __subscribe:(callback:()=>void)=>void 

    __hasRenderClearance:()=>boolean

    /**
     * Dispatch the page activation event.
     */
    __dispatch:()=>void
}

app.service<PageErrorEvent>('PageErrorEvent',(
    EventManager: EventManagerInterface
)=>{
    const PageErrorEventName = 'PEE'
    EventManager.__register(PageErrorEventName)
    let renderingCleared = true
    return {
        __subscribe:(callback)=>{
            EventManager.__subscribe(PageErrorEventName,callback)
        },
        __dispatch:()=>{
            renderingCleared = false
        },
        __hasRenderClearance:()=>{
            return renderingCleared
        }
    }
})