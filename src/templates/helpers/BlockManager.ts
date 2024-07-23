import { PatchAPI, ScopeObject, app } from "../interfaces/app"


/**
 * Manages blocks within your component
 */
export interface BlockManager {

    /**
     * Switch the block to the `active` state
     */
    __toActive:()=>Promise<void>

    /**
     * Switch the block to the `error` state
     */
    __toError:()=>Promise<void>

    /**
     * Switch the block to the `loading` state
     */
    __toLoading:()=>Promise<void>

    /**
     * Switch the block to the `empty` state
     */
    __toEmpty:()=>Promise<void>

    /**
     * Switch the block to the `ready` state
     */
    __toReady:()=>Promise<void>

    /**
     * Switch the block to the `completed` state
     */
    __toCompleted:()=>Promise<void>

    /**
     * Returns the current state value
     */
    __getCurrentState:()=>string

    /**
     * Registers data into the block scope
     * @param name 
     * @param data 
     * @returns 
     */
    __register:(name:string,data:((...args:any)=>void)|string|number|boolean|{[key:string]:any})=>void

    /**
     * Binds a block to the Manager
     * @param namespace - block namespace in the format `/BlockManager/${TName}/`
     */
    __bind:<TName extends string>(namespace:BlockNamespace<TName>) => BlockManager
}

app.helper<BlockManager>('BlockManager',(
    $scope: ScopeObject<StateInstance>,
    $patch: PatchAPI
)=>{
    const blockmanager = 'BlockManager'
    class __ManagerHelper implements BlockManager {
        private __scope: StateInstance
        private __patch: PatchAPI
        private __namespace: string
        private __name: string
        constructor(){
        }

        private __setState(state:StateNames):Promise<void>{
            return new Promise(async (resolve,reject)=>{
                this.__scope[blockmanager][this.__name].state = state
                await this.__patch(this.__namespace)
                resolve(null)
            })
        }

        /**
         * Switch the block to the `active` state
         */
        __toActive():Promise<void>{
            return this.__setState('active')
        }

        /**
         * Switch the block to the `error` state
         */
        __toError():Promise<void>{
            return this.__setState('error')
        }

        /**
         * Switch the block to the `loading` state
         */
        __toLoading():Promise<void>{
            return this.__setState('loading')
        }

        /**
         * Switch the block to the `empty` state
         */
        __toEmpty():Promise<void>{
            return this.__setState('empty')
        }

        /**
         * Switch the block to the `ready` state
         */
        __toReady():Promise<void>{
            return this.__setState('ready')
        }

        /**
         * Switch the block to the `completed` state
         */
        __toCompleted():Promise<void>{
            return this.__setState('completed')
        }
        
        __getCurrentState(){
            return this.__scope[blockmanager][this.__name]
        }

        __register(name: string, callback: ((...args:any)=>void)|string|number|boolean|{[key:string]:any}){
            if (name==='state') return 
            this.__scope[blockmanager][this.__name][name] = callback
        }

        __bind<TName extends string>(blockname: BlockNamespace<TName>): BlockManager {
            const manager = new __ManagerHelper()
            manager.__scope = $scope
            manager.__patch = $patch
            manager.__namespace = blockname
            const tokens = blockname.split('/')
            if (tokens[0]!==''||tokens[1]!==blockmanager||tokens[2].length===0||tokens[3]!=='') {
                console.error(`Invalid block namespace structure "${blockname}"`)
                return manager
            }
            manager.__name = tokens[2]
            if (!(blockmanager in manager.__scope)) {
                manager.__scope[blockmanager] = {}
            }
            if (manager.__name in manager.__scope[blockmanager]) {
                console.error(`Duplicate block registration "${blockname}"`)
                return manager
            }
            manager.__scope[blockmanager][manager.__name] = {
                state: 'empty'
            }
            return manager
        }
    }
    const manager = new __ManagerHelper()
    return manager
})

type StateNames = 'active' | 'error' | 'loading' | 'empty' | 'ready' | 'completed'

interface StateInstance {
    state: StateNames
}

type BlockNamespace<TName extends string> = `/BlockManager/${TName}/`