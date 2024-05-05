import { app } from "../../app";
import { ToastErrorEvent } from "../../services/events/ToastErrorEvent";


export type InvalidArgumentException = new (...args: any[]) => InvalidArgumentException

app.factory('InvalidArgumentException',(
    ToastErrorEvent: ToastErrorEvent
)=>{
    class InvalidArgumentException extends Error {
        constructor(message:string){
            super(message)
            console.error(message)
            ToastErrorEvent.__dispatch()
        }
    }
    return InvalidArgumentException
})