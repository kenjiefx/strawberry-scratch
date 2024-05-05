import { AppInstance, ChildComponentsHelper, PatchHelper, ScopeObject, app } from "../../strawberry/app"
import { StateManager } from "../../strawberry/helpers/StateManager"

/**
 * Declare all the component props here
 */
type ComponentScope = {
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
    StateManager: StateManager,
    $children: ChildComponentsHelper<ComponentChildren,keyof ComponentChildren>
)=>{
    StateManager.__register('active',()=>{
        return new Promise (async (resolve,reject)=>{
            /** Anything you want to do afer the component is activated */
            resolve()
        })
    })
    return {
        render:()=>{
            return new Promise(async (resolve,reject)=>{
                await StateManager.__switch('active')
                resolve(null)
            })
        }
    }
})