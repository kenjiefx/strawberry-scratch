import { StateManager } from "../../helpers/StateManager"
import { ApplicationAPI, PatchAPI, PluncAppInstance, ScopeObject, app } from "../../interfaces/app"
import { PageActivationManager } from "../../services/events/PageActivationManager"
import { ErrorDispatchReport, PageErrorManager } from "../../services/events/PageErrorManager"

/** States of the component */
type RouterState = 'loading' | 'active' | 'error'

/** Component Object */
type ComponentScope = {
    state: RouterState
    error: ErrorDispatchReport
}

/** Exportables */
export interface AppRouter {
    
}

/** Component declarations */
app.component<AppRouter>('AppRouter',(
    $scope: ScopeObject<ComponentScope>,
    $patch: PatchAPI,
    $app: ApplicationAPI,
    StateManager: StateManager,
    PageErrorManager: PageErrorManager,
    PageActivationManager: PageActivationManager
)=>{
    $scope.state = 'loading'

    /**
     * Apply your page activation logic here
     */
    $app.ready(async ()=>{
        if (!PageErrorManager.__hasRenderClearance()) {
            $scope.error = PageErrorManager.__getReport()
            await StateManager.__switch('error')
            return
        }
        await ActivatePage()
    })

    /**
     * @NOTE
     * Activation executes in this specific order: 
     * - Setting the state to active
     * - Patching the component 
     * - Dispatching the Page activation event for other 
     * components to catch on
     */
    const ActivatePage = async () => {
        await StateManager.__switch('active');
        PageActivationManager.__dispatch()
    }

    return {}
})