import { Header } from "../components/Header/Header"
import { Profile } from "../components/Profile/Profile"
import { app, ScopeObject, PatchHelper, AppInstance } from "../strawberry/app"
import { BlockManager } from "../strawberry/helpers/BlockManager"
import { ModalManager } from "../strawberry/helpers/ModalManager"
import { StateManager } from "../strawberry/helpers/StateManager"
import { TestHelper } from "../strawberry/helpers/TestHelper"
import { PageActivationEvent } from "../strawberry/services/events/PageActivationEvent"
import { PageErrorEvent } from "../strawberry/services/events/PageErrorEvent"
import { ToastErrorEvent } from "../strawberry/services/events/ToastErrorEvent"


/** States of the component */
type RouterState = 'loading' | 'active' | 'error'

/** Component Object */
type ComponentScope = {
    state: RouterState
}

/** Exportables */
export interface AppRouter {
    /**
     * Serves as a way for direct child components of the Router to listen 
     * to the different events, such as, when the Router updated the state 
     * to `active`, etc
     */
    subscribeEvent:()=>{
        /**
         * Allows you to listen to the `PageActivationEvent`, which is dispatched 
         * when the Router updates the component state to `active`
         * @param listener is called when the Event is dispatched
         */
        pageActive:(listener:()=>Promise<boolean>)=>void
    }
}

/** Component declarations */
app.component<AppRouter>('AppRouter',(
    $scope: ScopeObject<ComponentScope>,
    $patch: PatchHelper,
    StateManager: StateManager,
    $app: AppInstance,
    Header: Header,
    PageActivationEvent: PageActivationEvent,
    PageErrorEvent: PageErrorEvent,
    BlockManager: BlockManager,
    TestHelper: TestHelper,
    ModalManager: ModalManager,
    ToastErrorEvent: ToastErrorEvent,
    Profile: Profile
)=>{
    const TestBlock = BlockManager.__bind('/BlockManager/TestActionBlocks/')
    const BlockWithinModal = BlockManager.__bind('/BlockManager/BlockWithinModal/')
    const TestModal = ModalManager.__bind('/ModalManager/TestModal/')
    TestModal.__events().__whenOpened(()=>{
        BlockWithinModal.__toActive()
    })
    TestBlock.__register('showModal',async (button)=>{
        console.log('show modal was clicked')
        console.log(button)
        await TestModal.__open()
    })
    PageErrorEvent.__subscribe(()=>{
        console.log('error event fired!')
        StateManager.__switch('error')
    })
    ToastErrorEvent.__subscribe(()=>{
        console.log('toaster fired')
    })
    $app.onReady(()=>{
        StateManager.__switch('loading')
        /** Apply your activation logic here */
        if ($scope.state==='error') return
        setTimeout(async ()=>{
            await StateManager.__switch('active')
            await Header.render()
            await Profile.render()
            TestBlock.__toActive()
            BlockWithinModal.__toEmpty()
            PageActivationEvent.__dispatch()
        },500)
    })
    
    return {
        subscribeEvent:()=>{
            return {
                pageActive:(listener)=>{
                    PageActivationEvent.__subscribe(listener)
                }
            }
        }
    }
})