import { AppInstance, ChildComponentsHelper, PatchHelper, ScopeObject, app } from "../../strawberry/app"
import { StateManagerFactory } from "../../strawberry/factories/StateManager"

/**
 * These are the different states of `COMPONENT_NAME` component. These states
 * are fed into the component's StateManager.
 */
type COMPONENT_NAMEState = 'loading' | 'active' | 'error'

/** Component Object */
type ComponentScope = {
    state: COMPONENT_NAMEState
}


/**
 * This is the interface of the `COMPONENT_NAME` component. It contains
 * all the methods that are available for use by other components. 
 */
export interface COMPONENT_NAME {
    /**
     * Renders the `COMPONENT_NAME` component
     */
    render:()=>Promise<void>
}

/**
 * @example To use this type, you will want to declare all your optional components below: 
 * ```
 * type ComponentChildren = {
 *      ChildComponentName: ChildComponentInterface
 * }
 * ```
 */
type ComponentChildren = {}

/** Component declarations */
app.component<COMPONENT_NAME>('COMPONENT_NAME',(
    $scope: ScopeObject<ComponentScope>,
    $patch: PatchHelper,
    StateManager: StateManagerFactory,
    $children: ChildComponentsHelper<ComponentChildren,keyof ComponentChildren>
)=>{
    const ComponentState = new StateManager<COMPONENT_NAMEState>
    ComponentState.setScope($scope).setPatcher($patch).register('active').register('error').register('loading')

    return {
        render:()=>{
            return new Promise(async (resolve,reject)=>{
                await ComponentState.switch('active')
                resolve(null)
            })
        }
    }
})