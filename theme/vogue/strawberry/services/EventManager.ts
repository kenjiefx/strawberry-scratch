import { app } from "../app";

/**
 * Interface for managing events within your application
 */
export interface EventManagerInterface {

    /**
     * Register a new event name.
     * @param name - The name of the event to register.
     */
    __register:(name:string)=>void

    /**
     * Subscribe to an event.
     * @param name - The name of the event to subscribe to.
     * @param listener - The listener function to be called when the event is dispatched.
     */
    __subscribe:(name:string,listener:()=>any) => void

    /**
     * Dispatch an event.
     * @param name - The name of the event to dispatch.
     */
    __dispatch:(name:string)=>void
}

app.service<EventManagerInterface>('EventManager',()=>{
    class __EvManager implements EventManagerInterface {
        private __events: {[key:string]:__Event}
        constructor(){
            this.__events = {}
        }
        __register(name: string){
            const event = new __Event 
            event.__setName(name)
            if (!(name in this.__events)) {
                this.__events[name] = event
            }
        }
        __subscribe(name: string, listener: () => any){
            if (!(name in this.__events)) {
                this.__register(name)
            } 
            this.__events[name].__addListener(listener)
        }
        __dispatch(name: string){
            if (!(name in this.__events)) return
            const events = this.__events[name].__getListeners()
            this.__events[name].__getListeners().forEach((callback)=>{
                Promise.resolve(callback())
            })
        }
    }
    class __Event {
        private __name: string
        private __listeners: Array<()=>any>
        constructor(){
            this.__name = '',
            this.__listeners = []
        }
        __setName(name:string){
            this.__name = name 
        }
        __addListener(listener:()=>any){
            this.__listeners.push(listener)
        }
        __getListeners(){
            return this.__listeners
        }
    }
    return new __EvManager
})