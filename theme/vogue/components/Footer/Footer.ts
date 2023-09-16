import { PatchHelper, ScopeObject, app } from "../../strawberry/app"
import { StateManager } from "../../strawberry/factories/StateManager"

/** States of the component */
export type FooterState = 'loading' | 'active' | 'error'

/** Component Object */
type ComponentScope = {
    state: FooterState
}

/** Exportables */
export interface Footer {
    render:()=>Promise<void>
}

/** Component declarations */
app.component<Footer>('Footer',(
    $scope: ScopeObject<ComponentScope>,
    $patch: PatchHelper,
    StateManager: StateManager<FooterState>
)=>{
    StateManager.setScope($scope).setPatcher($patch).register('active').register('error').register('loading')
    return {
        render:()=>{
            return new Promise((resolve,reject)=>{

            })
        }
    }
})