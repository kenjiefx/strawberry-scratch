import { PatchHelper, ScopeObject, app } from "../../strawberry/app"
import { StateManagerInterface } from "../../strawberry/factories/StateManager"

/** States of the component */
export type HeaderState = 'loading' | 'active' | 'error'

/** Component Object */
type ComponentScope = {
    state: HeaderState
}

/** Exportables */
export interface Header {
    render:()=>Promise<void>
}

/** Component declarations */
app.component<Header>('Header',(
    $scope: ScopeObject<ComponentScope>,
    $patch: PatchHelper,
    StateManager: StateManagerInterface<HeaderState>
)=>{
    StateManager.setScope($scope).setPatcher($patch).register('active').register('error').register('loading')
    return {
        render:()=>{
            return new Promise((resolve,reject)=>{

            })
        }
    }
})