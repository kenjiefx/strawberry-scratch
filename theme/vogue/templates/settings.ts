import { app, ScopeObject, PatchHelper, AppInstance } from "../strawberry/app"
import { StateManager } from "../strawberry/helpers/StateManager"
import { EventManagerInterface } from "../strawberry/services/EventManager"
import { PageActivationEvent } from "../strawberry/services/events/PageActivationEvent"
import { PageErrorEvent } from "../strawberry/services/events/PageErrorEvent"


/** States of the component */
type RouterState = 'loading' | 'active' | 'error'

/** Component Object */
type ComponentScope = {
    state: RouterState
}

/** Exportables */
export interface AppRouter {
    /**
     * Serves as a way for direct child components of the Router to listen 
     * to the different events, such as, when the Router updated the state 
     * to `active`, etc
     */
    subscribeEvent:()=>{
        /**
         * Allows you to listen to the `PageActivationEvent`, which is dispatched 
         * when the Router updates the component state to `active`
         * @param listener is called when the Event is dispatched
         */
        pageActive:(listener:()=>Promise<boolean>)=>void
    }
}

/** Component declarations */
app.component<AppRouter>('AppRouter',(
    $scope: ScopeObject<ComponentScope>,
    $patch: PatchHelper,
    StateManager: StateManager,
    $app: AppInstance,
    EventManager: EventManagerInterface,
    PageActivationEvent: PageActivationEvent,
    PageErrorEvent: PageErrorEvent
)=>{
    PageErrorEvent.__subscribe(()=>{
        StateManager.__switch('error')
    })
    $app.onReady(()=>{
        StateManager.__switch('loading')
        /** Apply your activation logic here */
        if ($scope.state==='error') return
        setTimeout(async ()=>{
            await StateManager.__switch('active')
            PageActivationEvent.__dispatch()
        },2000)
    })
    
    return {
        subscribeEvent:()=>{
            return {
                pageActive:(listener)=>{
                    PageActivationEvent.__subscribe(listener)
                }
            }
        }
    }
})