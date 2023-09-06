import { PatchHelper, ScopeObject, app } from "../../strawberry/app"
import { StateManagerFactory } from "../../strawberry/factories/StateManagerFactory"

/** States of the component */
export type FooterState = 'loading' | 'active' | 'error'
type ComponentStateType = {component:{state:FooterState}}

/** Component Object */
type ComponentScope = {
    StateManager: ComponentStateType
}

/** Exportables */
export interface Footer {
    render:()=>Promise<void>
}

/** Component declarations */
app.component<Footer>('Footer',(
    $scope: ScopeObject<ComponentScope>,
    $patch: PatchHelper,
    StateManagerFactory: StateManagerFactory
)=>{
    const ComponentState = StateManagerFactory.createNewInstance<FooterState,ComponentScope>({
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