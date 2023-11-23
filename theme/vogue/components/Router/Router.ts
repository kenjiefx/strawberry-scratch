import { AppInstance, PatchHelper, ScopeObject, app } from "../../strawberry/app"
import { StateManagerFactory } from "../../strawberry/factories/StateManager"
import { EventManagerInterface } from "../../strawberry/services/EventManager"

/** States of the component */
type RouterState = 'loading' | 'active' | 'error'

/** Component Object */
type ComponentScope = {
    state: RouterState
}

/** Exportables */
export interface Router {
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
app.component<Router>('Router',(
    $scope: ScopeObject<ComponentScope>,
    $patch: PatchHelper,
    StateManager: StateManagerFactory,
    $app: AppInstance,
    EventManager: EventManagerInterface
)=>{
    const ComponentState = new StateManager<RouterState>
    ComponentState.setScope($scope).setPatcher($patch).register('active').register('error').register('loading')
    EventManager.register('PageActivationEvent')
    EventManager.subscribe('PageErrorEvent',()=>{
        $scope.state = 'error'
        $patch()
    })
    $app.onReady(()=>{
        ComponentState.switch('loading')
        /** Apply your activation logic here */
        if ($scope.state==='error') return
        setTimeout(async ()=>{
            await ComponentState.switch('active')
            EventManager.dispatch('PageActivationEvent')
        },2000)
    })
    
    return {
        subscribeEvent:()=>{
            return {
                pageActive:(listener)=>{
                    EventManager.subscribe('PageActivationEvent',listener)
                }
            }
        }
    }
})