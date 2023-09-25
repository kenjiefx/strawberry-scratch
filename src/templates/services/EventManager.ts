import { app } from "../app";

export interface EventManagerInterface {
    register:(name:string)=>void
    subscribe:(name:string,listener:()=>any) => void
    dispatch:(name:string)=>void
}

app.service<EventManagerInterface>('EventManager',()=>{
    class EventManager implements EventManagerInterface {
        private events: {[key:string]:Event}
        constructor(){
            this.events = {}
        }
        register(name: string){
            const event = new Event 
            event.setName(name)
            if (!this.events.hasOwnProperty(name)) {
                this.events[name] = event
            }
        }
        subscribe(name: string, listener: () => any){
            if (!this.events.hasOwnProperty(name)) {
                this.register(name)
            } 
            this.events[name].addListener(listener)
        }
        dispatch(name: string){
            if (!this.events.hasOwnProperty(name)) return
            this.events[name].getListeners().forEach(async (callback)=>{
                await Promise.resolve(callback())
            })
        }
    }
    class Event {
        private name: string
        private listeners: Array<()=>any>
        constructor(){
            this.name = '',
            this.listeners = []
        }
        setName(name:string){
            this.name = name 
        }
        addListener(listener:()=>any){
            this.listeners.push(listener)
        }
        getListeners(){
            return this.listeners
        }
    }
    const eventManager = new EventManager

    return eventManager
})