import { PatchHelper, ScopeObject, app } from "../../strawberry/app"
import { StateManagerInterface } from "../../strawberry/factories/StateManager"

/** States of the component */
export type HeaderTestState = 'loading' | 'active' | 'error'

/** Component Object */
type ComponentScope = {
    state: HeaderTestState
}

/** Exportables */
export interface HeaderTest {
    render:()=>Promise<void>
}

/** Component declarations */
app.component<HeaderTest>('HeaderTest',(
    $scope: ScopeObject<ComponentScope>,
    $patch: PatchHelper,
    StateManager: StateManagerInterface<HeaderTestState>
)=>{
    StateManager.setScope($scope).setPatcher($patch).register('active').register('error').register('loading')
    return {
        render:()=>{
            return new Promise((resolve,reject)=>{

            })
        }
    }
})