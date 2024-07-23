import { StateManager } from "../../helpers/StateManager"
import { ApplicationAPI, PatchAPI, ScopeObject, app } from "../../interfaces/app"
import { PageActivationManager } from "../../services/events/PageActivationManager"
import { PageErrorManager } from "../../services/events/PageErrorManager"

/**
 * These are the different states of `COMPONENT_NAME` component. These states
 * are fed into the component's StateManager.
 */
type COMPONENT_NAMEState = 'loading' | 'active' | 'error'

/** Component Object */
type ComponentScope = {
    state: COMPONENT_NAMEState,
    firstName: string,
    lastName: string
}

/**
 * All the methods that are exposed for use by other components.
 */
export interface COMPONENT_NAME {
    /**
     * Allows parent component to explicitly render the 
     * `COMPONENT_NAME` component.
     */
    __render:()=>Promise<void>
}


/** Component declarations */
app.component<COMPONENT_NAME>('COMPONENT_NAME',(
    $scope: ScopeObject<ComponentScope>,
    $patch: PatchAPI,
    PageErrorManager: PageErrorManager,
    PageActivationManager: PageActivationManager,
    StateManager: StateManager
)=>{
    /** 
     * You can self-activate this component by subscribing to the `PageActivationEvent`. 
     * This event is fired after the @AppRouter component signals the page activation.
     */
    PageActivationManager.__subscribe(async ()=>{
        await StateManager.__switch('active')
    })
    return {
        __render:()=>{
            return new Promise(async (resolve,reject)=>{
                await StateManager.__switch('active')
                resolve(null)
            })
        }
    }
})