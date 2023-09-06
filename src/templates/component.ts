import { PatchHelper, ScopeObject, app } from "../../strawberry/app"
import { StateManagerFactory } from "../../strawberry/factories/StateManagerFactory"

/** States of the component */
export type COMPONENT_NAMEState = 'loading' | 'active' | 'error'
type ComponentStateType = {component:{state:COMPONENT_NAMEState}}

/** Component Object */
type ComponentScope = {
    StateManager: ComponentStateType
}

/** Exportables */
export interface COMPONENT_NAME {
    render:()=>Promise<void>
}

/** Component declarations */
app.component<COMPONENT_NAME>('COMPONENT_NAME',(
    $scope: ScopeObject<ComponentScope>,
    $patch: PatchHelper,
    StateManagerFactory: StateManagerFactory
)=>{
    const ComponentState = StateManagerFactory.createNewInstance<COMPONENT_NAMEState,ComponentScope>({
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