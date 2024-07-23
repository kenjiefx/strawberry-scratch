import { BinaryState } from "../../factories/BinaryState"
import { app } from "../../interfaces/app"
import { EventManagerInterface } from "../EventManager"


/**
 * Interface for subscribing to and dispatching page activation events.
 */
export interface PageErrorManager {
    /**
     * Subscribe to the page activation event.
     * @param callback - The callback function to be called when the page is activated.
     */
    __subscribe:(callback:()=>void)=>void 

    /**
     * Tells whether initial rendering was cleared.
     */
    __hasRenderClearance:()=>boolean

    /**
     * Dispatch the page activation event.
     */
    __dispatch:(error: ErrorDispatchReport)=>void

    /**
     * Returns a report about what error was thrown
     */
    __getReport:() => ErrorDispatchReport
}

/**
 * The data that was passed to the PageErrorManager dispatch
 * function that details the error.
 */
export type ErrorDispatchReport = {
    code: number,
    message: string,
    dispatcher: string
}

app.service<PageErrorManager>('PageErrorManager',(
    EventManager: EventManagerInterface,
    BinaryState: BinaryState
)=>{
    const EVENT_NAME = 'PEE'
    EventManager.__register(EVENT_NAME)
    let ErrorDispatch: ErrorDispatchReport = {
        code: 500,
        message: 'Unknown error',
        dispatcher: 'PageErrorEvent'
    }
    const HasRenderClearance = new BinaryState(true)
    return {
        __subscribe:(callback)=>{
            EventManager.__subscribe(EVENT_NAME,callback)
        },
        __dispatch:(error)=>{
            if (HasRenderClearance.false()) return
            ErrorDispatch = error
            HasRenderClearance.set(false)
            console.error(`[${error.dispatcher}] ${error.message}`)
            EventManager.__dispatch(EVENT_NAME)
        },
        __hasRenderClearance:()=>{
            return HasRenderClearance.true()
        },
        __getReport:()=>{
            return ErrorDispatch
        }
    }
})