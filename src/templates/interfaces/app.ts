
export const app = {
  /**
   * Registers a component in your application. You can pass the type or interface of the
   * component `<TComponent>`.
   * @param name - The name of the component
   * @param handler - The callback function that returns methods and properties implemented by `TComponent`
   */
  component: <TComponent extends {[key: string]: (...args: any[]) => Promise<any>}>(
    name: string,
    handler: (...args: any[]) => TComponent
  ) => {},
  
  /**
   * Registers a service in your application. You can pass the type or interface
   * of the service `<TService>`
   * @param name  - The name of the service
   * @param handler - The callback function that returns methods and properties implemented by `TService`
   */
  service: <TService extends {[key: string]: (...args: any[]) => any}>(
    name: string,
    handler: (...args: any[]) => TService
  ) => {},
  
  /**
   * Registers a factory in your application. You can pass the type or interface
   * of the factory `<TFactory>`
   * @param name - The name of the factory
   * @param handler - The callback function that returns methods and properties implemented by `TFactory`
   */
  factory: <TFactory extends new (...args: any[]) => any>(
    name: string,
    handler: (...args: any[]) => TFactory
  )=>{},
  
  /**
   * Registers a helper in your application. You can pass the type or interface
   * of the service `<THelper>`
   * @param name  - The name of the service
   * @param handler - The callback function that returns methods and properties implemented by `THelper`
   */
  helper: <THelper>(
    name: string,
    handler: (...args: any[]) => THelper
  ) => {}

}

export interface PluncElementInterface<TElement extends Element> {
  /**
   * A reference to the element itself.
   * (Shouldn't be minified, as publicly-accessible)
   */
  $element: TElement;
  /**
   * A reference to parent element, wrapped in this `PluncElement` object
   * (Shouldn't be minified, as publicly-accessible)
   */
  $parent: PluncElementInterface<TElement>;
  /** Retrieves the $element */
  get(): TElement;
  /** Retrieves the state */
  getState(): string | null;
  /** Sets the state */
  setState(state: string): void;
  /** Adds a class */
  addClass(className: string): void;
  /** List existing classes */
  listClass(): Array<string>;
  /** Removes a class */
  removeClass(className: string): void;
  /** Toggle class names */
  toggleClass(className: string): void;
}

export type ApplicationAPI = {
  /**
   * Registers a function that executes when the App is ready
   * @param callback - Function to call after the app is set to ready
   */
  ready:(callback:()=>unknown)=>void
}

/** Block API requires call back function */
export type BlockCallback<TElement extends Element> = (
  element: PluncElementInterface<TElement>
) => void;

export type BlockAPI = <TElement extends Element>(
  elementName: string,
  callback: BlockCallback<TElement>
) => void;

export type PatchAPI = (elementName?:string) => Promise<null>
export type ScopeObject<TScope extends {[key: string]: any}> = TScope 
export type AppService = {
  bootstrap: () => Promise<void>
}