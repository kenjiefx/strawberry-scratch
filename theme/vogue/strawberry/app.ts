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
    factory:<TFactory>(name:string,callback:FactoryCallbackFunction<any[]>)=>void

    /**
     * Registers a helper in your application. You can pass the type or interface
     * of the service `<THelper>`
     * @param name  - The name of the service
     * @param callback - The callback function that returns methods and properties implemented by `THelper`
     */
    helper:<THelper>(name:string,callback:CallbackFunction<unknown[],THelper>)=>void
}

type CallbackFunction<TDependecies extends unknown[],TObject> = (...args: TDependecies) => TObject
type FactoryCallbackFunction<TDependecies extends unknown[]> = (...args: TDependecies) => new (...args: any[]) => any

/**
 * The `ParentComponentHelper` needs to be used with `$parent`.
 * You must specify the interface or type of your Parent component 
 * by passing it to the generic `TComponent`
 */
export type ParentComponentHelper <TComponent> = {
    get:()=>TComponent
}

/**
 * The `ChildComponnetsHelper` needs to be used with `$children`. 
* To use this type, you will need to specify a map of all child components:
 * ```
 * type TChildren = {
 *      ChildComponentName: ChildComponentInterface
 * }
 * ```
 * Depending on the logic you were building your component (build phase) on, 
 * you may or you may not have certain components included as child components. This is
 * a great use-case when, there are certain pages where this component will not be using
 * a certain child component. As strawberry.js will throw an error when you inject child components
 * that are not present within the parent component, $children services will be a great help. 
 */
export type ChildComponentsHelper <TChildren,T extends keyof TChildren> = {
    get: <K extends T>(tChild: K) => TChildren[K] | null
}
export type InjectableDependency = {[key:string]: any} | (()=>void)

export type ScopeObject<TScope extends {[key: string]: any}> = TScope 
export type PatchHelper = (elementName?:string) => Promise<null>
export type AppInstance = {
    /**
     * Registers a function that executes when the App is ready
     * @param callback - Function to call after the app is set to ready
     */
    onReady:(callback:()=>unknown)=>void
}

/** An element represented by xblock="@name" */


/** An HTML element wrapped inside Strawberry-defined object */
export type StrawberryElement<TElement> = {
    constructor:(element:TElement,treeCount:null)=>void
    addClass:(className:string)=>void
    removeClass:(className:string)=>void
    $element: TElement
}

export type BlockElement=<TElement>(
    elementName: string,
    callbackFunction:(element:StrawberryElement<TElement>)=>unknown
)=>void

export const app:StrawberryApp = {
    component:()=>{},
    service:()=>{},
    factory:()=>{},
    helper:()=>{}
}