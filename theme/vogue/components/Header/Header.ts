import { AppInstance, PatchHelper, ScopeObject, app } from "../../strawberry/app"
import { StateManager } from "../../strawberry/factories/StateManager"
import { ProfileCard } from "../ProfileCard/ProfileCard"

/** States of the component */
export type HeaderState = 'loading' | 'active' | 'error'

/** Component Object */
type ComponentScope = {
    state: HeaderState,
    hello: string
}

/** Exportables */
export interface Header {
    render:()=>Promise<void>
}

/** Component declarations */
app.component<Header>('Header',(
    $scope: ScopeObject<ComponentScope>,
    $patch: PatchHelper,
    StateManager: StateManager<HeaderState>,
    $app: AppInstance,
    ProfileCard: ProfileCard
)=>{
    StateManager.setScope($scope).setPatcher($patch).register('active').register('error').register('loading')
    $scope.hello = 'world'
    $app.onReady(()=>{
        StateManager.switch('active')
        ProfileCard.render()
    })
    return {
        render:()=>{
            return new Promise((resolve,reject)=>{

            })
        }
    }
})