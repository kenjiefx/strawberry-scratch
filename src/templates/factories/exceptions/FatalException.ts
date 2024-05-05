import { app } from "../../app";
import { EventManagerInterface } from "../../services/EventManager";
import { PageErrorEvent } from "../../services/events/PageErrorEvent";

export interface FatalExceptionInterface {

}

export type FatalException = new (...args: any[]) => FatalExceptionInterface

app.factory('FatalException',(
    PageErrorEvent: PageErrorEvent
)=>{
    class FatalException {
        constructor(message:string){
            PageErrorEvent.__dispatch()
            console.error(message)
        }
    }
    return FatalException
})