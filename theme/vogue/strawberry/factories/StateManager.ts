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
export interface StateManagerInterface<TStateNames extends string> {

    /**
     * Registers the scope object where the states are bind to
     * @param reference - The scope object
     * @returns 
     */
    setScope:(reference:StateInstance)=>this

    /**
     * Registers the Patch function, which would automatically
     * execute after calling the switch method
     * @param patchFn - PatchHelper
     * @returns 
     */
    setPatcher:(patchFn:PatchHelper)=>this

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

type TStates = string
app.factory<StateManagerInterface<TStates>>('StateManager',(
    ErrorHandler: ErrorHandler
)=>{
    class StateInstance implements StateInstance {
        state = ''
    }
    class StateManager implements StateManagerInterface<TStates> {
        private states:StatesMap = {}
        private reference: StateInstance
        private patchFn: PatchHelper
        constructor(){
        }
        setScope(reference:StateInstance){
            this.reference = reference
            return this
        }
        setPatcher(patchFn:PatchHelper){
            this.patchFn = patchFn
            return this
        }
        register(name:string,callback=()=>{}){
            if (this.states.hasOwnProperty(name)) {
                throw new ErrorHandler.InvalidArgumentException()
            }
            this.states[name] = callback
            return this
        }
        switch(name:string){
            if (!this.states.hasOwnProperty(name)) {
                throw new ErrorHandler.InvalidArgumentException()
            }
            this.reference.state = name
            this.states[name]()
            return this.patchFn()
        }
        getCurrentState(){
            return this.reference.state
        }
    }
    return StateManager
})