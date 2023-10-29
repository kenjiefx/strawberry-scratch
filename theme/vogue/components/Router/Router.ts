import { AppInstance, PatchHelper, ScopeObject, app } from "../../strawberry/app"
import { StateManagerFactory } from "../../strawberry/factories/StateManager"

/** States of the component */
export type RouterState = 'loading' | 'active' | 'error'

/** Component Object */
type ComponentScope = {
    state: RouterState
}

/** Exportables */
export interface Router {
    render:()=>Promise<void>
}

/** Component declarations */
app.component<Router>('Router',(
    $scope: ScopeObject<ComponentScope>,
    $patch: PatchHelper,
    StateManager: StateManagerFactory<RouterState>,
    $app: AppInstance
)=>{
    const ComponentState = new StateManager
    ComponentState.setScope($scope).setPatcher($patch).register('active').register('error').register('loading')

    $app.onReady(()=>{
        ComponentState.switch('active')
    })
    
    return {
        render:()=>{
            return new Promise((resolve,reject)=>{

            })
        }
    }
})