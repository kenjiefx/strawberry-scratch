import { PatchHelper, ScopeObject, app } from "../app";
import { ErrorHandler } from "../services/ErrorHandler";

interface StateInstance {
    state: string
}

type StateActivationCallback= null | (()=>void)

type StatesMap = {
    [key: string]: (()=>void)
}

/**
 * Manages the state of your component or sub-component.
 */
interface StateManager<TStateNames extends string> {
    /**
     * Registers a specific state
     * @param name - The name of the state
     * @param callback - (optional) Any callback function to run when this state is activated
     */
    register:(name:TStateNames,callback?:StateActivationCallback)=>this

    /**
     * Switch to a specific state
     * @param name - The name of the state
     */
    switch:(name:TStateNames)=>void

    getCurrentState:()=>string
}

/**
 * Creates the State Manager object
 */
export interface StateManagerFactory {
    createNewInstance:<TStateNames extends string,TScope extends {StateManager:{}}>({name,scope,patch}:{
        name: string,
        scope: ScopeObject<TScope>,
        patch: PatchHelper
    })=>StateManager<TStateNames>
}

app.factory<StateManagerFactory>('StateManagerFactory',(
    ErrorHandler: ErrorHandler
)=>{
    class StateInstance implements StateInstance {
        state = ''
    }
    class StateManager implements StateManager {
        private states:StatesMap = {}
        private reference: StateInstance
        private patchFn: PatchHelper
        constructor({reference,patchFn}:{reference:StateInstance,patchFn:PatchHelper}){
            this.reference = reference;
            if (undefined!==patchFn) this.patchFn = patchFn
        }
        register(name:string,callback=()=>{}){
            if (this.states.hasOwnProperty(name)) {
                throw new ErrorHandler.InvalidArgumentException()
            }
            this.states[name] = callback
            return this
        }
        switch(name){
            if (!this.states.hasOwnProperty(name)) {
                throw new ErrorHandler.InvalidArgumentException()
            }
            this.reference.state = name
            this.states[name]()
            this.patchFn()
        }
        getCurrentState(){
            return this.reference.state
        }
    }
    return {
        createNewInstance:({name,scope,patch})=>{
            if (!scope.hasOwnProperty('StateManager')) {
                scope.StateManager = {}
            }
            scope.StateManager[name] = new StateInstance
            return new StateManager({
                reference: scope.StateManager[name],
                patchFn: patch
            })
        }
    }
})