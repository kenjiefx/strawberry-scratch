import { BlockElement, PatchHelper, ScopeObject, app } from "../app";
import { FatalException } from "../factories/exceptions/FatalException";
import { InvalidArgumentException } from "../factories/exceptions/InvalidArgumentException";
import { EventManagerInterface } from "../services/EventManager";

export interface ModalManager {

    /**
     * Opens the modal
     */
    __open:()=>Promise<void>

    /**
     * Closes the modal
     */
    __close:()=>Promise<void>

    __events:()=>ModalManagerEvents

    /**
     * Binds a block to the Manager
     * @param namespace - block namespace in the format `/ModalManager/${TName}/`
     */
    __bind:<TName extends string>(namespace:ModalNamespace<TName>) => ModalManager
}

app.helper<ModalManager>('ModalManager',(
    $scope: ScopeObject<StateInstance>,
    $patch: PatchHelper,
    $block: BlockElement,
    FatalException: FatalException,
    InvalidArgumentException: InvalidArgumentException,
    EventManager: EventManagerInterface
)=>{
    let ManagerInstanceId = 0
    const modalmanager = 'ModalManager'
    function assertDialogElement (namespace:string, element:unknown): asserts element is HTMLDialogElement {
        if (!(element instanceof HTMLDialogElement)) {
            throw new InvalidArgumentException(`Block element ${namespace} must be instanceof HTMLDialogElement`)
        }
    }
    const makeEventUniqueId = <TName extends string>(namespace:ModalNamespace<TName>,type:'open'|'close') => {
        ManagerInstanceId++
        return `${namespace}${ManagerInstanceId}/${type}`
    }
    class __ManagerHelper implements ModalManager {
        private __scope: StateInstance
        private __patch: PatchHelper
        private __namespace: string
        private __name: string
        private __eventName: {
            __whenOpenedEvent: string,
            __whenClosedEvent: string
        }

        __open():Promise<void>{
            return new Promise(async (resolve,reject)=>{
                $block(this.__namespace,(element)=>{
                    try {
                        assertDialogElement(this.__namespace,element.$element)
                        EventManager.__dispatch(this.__eventName.__whenOpenedEvent)
                        element.$element.show()
                    } catch (error) {
                        reject(error)
                    }
                })
            })
        }

        __close():Promise<void>{
            return new Promise(async (resolve,reject)=>{
                $block(this.__namespace,(element)=>{
                    try {
                        assertDialogElement(this.__namespace,element.$element)
                        element.$element.close()
                        EventManager.__dispatch(this.__eventName.__whenClosedEvent)
                    } catch (error) {
                        reject(error)
                    }
                })
            })
        }

        __events(): ModalManagerEvents {
            return {
                __whenOpened:(callback)=>{
                    EventManager.__subscribe(this.__eventName.__whenOpenedEvent,callback)
                },
                __whenClosed:(callback)=>{
                    EventManager.__subscribe(this.__eventName.__whenClosedEvent,callback)
                }
            }
        }

        __bind<TName extends string>(modalname: ModalNamespace<TName>): __ManagerHelper {
            const manager = new __ManagerHelper()
            manager.__scope = $scope
            manager.__patch = $patch
            const tokens = modalname.split('/')
            if (tokens[0]!==''||tokens[1]!==modalmanager||tokens[2].length===0||tokens[3]!=='') {
                new FatalException(`Invalid modal namespace structure "${modalname}"`)
                return manager
            }
            manager.__name = tokens[2]
            if (!(modalmanager in manager.__scope)) {
                manager.__scope[modalmanager] = {}
            }
            if (manager.__name in manager.__scope[modalmanager]) {
                new FatalException(`Duplicate modal registration "${modalname}"`)
                return manager
            }
            manager.__scope[modalmanager][manager.__name] = {
                close: ()=>{
                    return manager.__close()
                }
            }
            manager.__eventName = {
                __whenOpenedEvent: makeEventUniqueId(modalname,'open'),
                __whenClosedEvent: makeEventUniqueId(modalname,'close')
            }
            EventManager.__register(manager.__eventName.__whenOpenedEvent)
            EventManager.__register(manager.__eventName.__whenClosedEvent)
            manager.__namespace = modalname
            return manager
        }
    }
    const manager = new __ManagerHelper()
    return manager
})

interface StateInstance {
    
}

type ModalManagerEvents = {
    __whenOpened:(callback:()=>any)=> void
    __whenClosed:(callback:()=>any)=> void
}

type ModalNamespace<TName extends string> = `/ModalManager/${TName}/`