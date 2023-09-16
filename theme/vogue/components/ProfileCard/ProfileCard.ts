import { PatchHelper, ScopeObject, app } from "../../strawberry/app"
import { StateManager } from "../../strawberry/factories/StateManager"

/** States of the component */
export type ProfileCardState = 'loading' | 'active' | 'error'

/** Component Object */
type ComponentScope = {
    state: ProfileCardState
}

/** Exportables */
export interface ProfileCard {
    render:()=>Promise<void>
}

/** Component declarations */
app.component<ProfileCard>('ProfileCard',(
    $scope: ScopeObject<ComponentScope>,
    $patch: PatchHelper,
    StateManager: StateManager<ProfileCardState>
)=>{
    StateManager.setScope($scope).setPatcher($patch).register('active').register('error').register('loading')
    return {
        render:()=>{
            return new Promise((resolve,reject)=>{
                StateManager.switch('active')
            })
        }
    }
})