import { PatchHelper, ScopeObject, app } from "../../strawberry/app"
import { StateManagerFactory } from "../../strawberry/factories/StateManagerFactory"

/** States of the component */
export type HeaderState = 'loading' | 'active' | 'error'
type ComponentStateType = {component:{state:HeaderState}}

/** Component Object */
type ComponentScope = {
    StateManager: ComponentStateType
}

/** Exportables */
export interface Header {
    render:()=>Promise<void>
}

/** Component declarations */
app.component<Header>('Header',(
    $scope: ScopeObject<ComponentScope>,
    $patch: PatchHelper,
    StateManagerFactory: StateManagerFactory
)=>{
    const ComponentState = StateManagerFactory.createNewInstance<HeaderState,ComponentScope>({
        name: 'component',
        patch: $patch,
        scope: $scope
    })
    ComponentState.register('loading').register('active').register('error')
    return {
        render:()=>{
            return new Promise((resolve,reject)=>{

            })
        }
    }
})