import { PatchAPI, ScopeObject, app } from "../interfaces/app"


/**
 * Manages states in your component
 */
export interface StateManager {

    /**
     * Registers a callback function that will be invoked when you switch to a specific state. 
     * @NOTE You can only register one callback per state
     * @param name - The name of the state 
     * @param callback - @see StateActivationCallback
     */
    __register:(name: StateNames, callback: StateActivationCallback)=>this

    /**
     * Switch to a specific state
     * @param name - The name of the state
     */
    __switch:(name: StateNames)=>Promise<void>

    /**
     * Returns the current state value
     */
    __getCurrentState:()=>string
    
}

app.helper<StateManager>('StateManager',(
    $scope: ScopeObject<StateInstance>,
    $patch: PatchAPI
)=>{
    class __ManagerHelper implements StateManager {
        private __callbacks:StateCallbackMap = {}
        private __scope: StateInstance
        private __patch: PatchAPI
        private __blockName: string | null
        constructor(){
            this.__blockName = null
        }
        __setScope(scope: ScopeObject<StateInstance>){
            this.__scope = scope
        }
        __setPatch(patch: PatchAPI) {
            this.__patch = patch
        }
        __setBlockName(blockName: string) {
            this.__blockName = blockName
        }
        __register(name: StateNames, callback: StateActivationCallback){
            if (name in this.__callbacks) {
                console.error(`Duplicate callback for component state "${name}"`)
                return this
            }
            this.__callbacks[name] = callback
            return this
        }
        __switch(name: StateNames): Promise<void>{
            return new Promise (async (resolve,reject)=>{
                try {
                    this.__scope.state = name
                    await this.__patch()
                    if (name in this.__callbacks) {
                        await this.__callbacks[name]()
                    }
                    resolve(null)
                } catch (error) {
                    reject(error)
                }
            })
        }
        __getCurrentState(){
            return this.__scope.state
        }
    }
    const manager = new __ManagerHelper()
    manager.__setScope($scope)
    manager.__setPatch($patch)
    return manager
})

type StateNames = 'active' | 'error' | 'loading' | 'empty'

interface StateInstance {
    state: StateNames
}

/** A callback function to execute when this state is activated */
type StateActivationCallback = () => Promise<void>

/** A map of state names and their corresponding activation callback */
type StateCallbackMap = {
    [key: string]: StateActivationCallback
}