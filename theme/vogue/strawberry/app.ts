export interface StrawberryApp {

    /**
     * Registers a component in your application. You can pass the type or interface of the 
     * component `<TComponent>`.
     * @param name - The name of the component
     * @param callback - The callback function that returns methods and properties implemented by `TComponent`
     */
    component:<TComponent>(name:string,callback:CallbackFunction<unknown[],TComponent>)=>void

    /**
     * Registers a service in your application. You can pass the type or interface
     * of the service `<TService>`
     * @param name  - The name of the service
     * @param callback - The callback function that returns methods and properties implemented by `TService`
     */
    service:<TService>(name:string,callback:CallbackFunction<unknown[],TService>)=>void
    
    /**
     * Registers a factory in your application. You can pass the type or interface
     * of the factory `<TFactory>` 
     * @param name - The name of the factory
     * @param callback - The callback function that returns methods and properties implemented by `TFactory`
     */
    factory:<TFactory>(name:string,callback:CallbackFunction<unknown[],TFactory>)=>void
}

type CallbackFunction<TDependecies extends unknown[],TObject> = (...args: TDependecies) => TObject;

export type InjectableDependency = {[key:string]: any} | (()=>void)

export type ScopeObject<TScope extends {[key: string]: any}> = TScope 
export type PatchHelper = () => void;

/** An element represented by xblock="@name" */
export type BlockElement = {
    name: string,
    each:(element:StrawberryElement)=>void
}

/** An HTML element wrapped inside Strawberry-defined object */
export type StrawberryElement = {
    constructor:(element:HTMLElement,treeCount:null)=>void
    get:()=>HTMLElement
    addClass:(className:string)=>void
    removeClass:(className:string)=>void
    $element: HTMLElement
}

/** 
 * A wrapper to a DOM query function that selects only element with xblock attribute,
 * and name defined by the name of the TBlockElement passed into this function
 */
export type BlockElements<TBlockElement extends BlockElement> = (blockElement:TBlockElement) => void;

export const app:StrawberryApp = {
    component:()=>{},
    service:()=>{},
    factory:()=>{}
}