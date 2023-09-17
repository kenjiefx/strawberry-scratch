import { PatchHelper, ScopeObject, app } from "../../strawberry/app"
import { StateManagerInterface } from "../../strawberry/factories/StateManager"

/** States of the component */
export type COMPONENT_NAMEState = 'loading' | 'active' | 'error'

/** Component Object */
type ComponentScope = {
    state: COMPONENT_NAMEState
}

/** Exportables */
export interface COMPONENT_NAME {
    render:()=>Promise<void>
}

/** Component declarations */
app.component<COMPONENT_NAME>('COMPONENT_NAME',(
    $scope: ScopeObject<ComponentScope>,
    $patch: PatchHelper,
    StateManager: StateManagerInterface<COMPONENT_NAMEState>
)=>{
    StateManager.setScope($scope).setPatcher($patch).register('active').register('error').register('loading')
    return {
        render:()=>{
            return new Promise((resolve,reject)=>{

            })
        }
    }
})