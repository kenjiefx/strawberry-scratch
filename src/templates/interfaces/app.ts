/** All configuration you can pass when creating an instance of the app. */
export type AppConfiguration = {
    /**
     * Prefix for Plunc-related HTML attributes.
     * By default, the application assumes using `plunc-`,
     * i.e., `plunc-if`, `plunc-repeat`.
     **/
    prefix?: string;
    /**
     * You can hook a function that will execute before the app kernel
     * starts booting. The function should return a Promise,
     * and must return `true`, otherwise, the kernel will not start.
     * @returns Promise that resolves to boolean value
     */
    startFn?: () => Promise<boolean>;
    /**
     * Hook a function that executes after the app completes
     * rendering.
     */
    endFn?: () => Promise<void>;
  };
  
  export type RequireAllFields<T> = {
    [K in keyof T]-?: T[K];
  };
  
  export type PluncAppInstance = {
    /**
     * Registers a component in your application. You can pass the type or interface of the
     * component `<TComponent>`.
     * @param name - The name of the component
     * @param handler - The callback function that returns methods and properties implemented by `TComponent`
     */
    component: <TComponent>(
      name: string,
      handler: HandlerFunction<unknown[], TComponent>
    ) => void;
  
    /**
     * Registers a service in your application. You can pass the type or interface
     * of the service `<TService>`
     * @param name  - The name of the service
     * @param handler - The callback function that returns methods and properties implemented by `TService`
     */
    service: <TService>(
      name: string,
      handler: HandlerFunction<unknown[], TService>
    ) => void;
  
    /**
     * Registers a factory in your application. You can pass the type or interface
     * of the factory `<TFactory>`
     * @param name - The name of the factory
     * @param handler - The callback function that returns methods and properties implemented by `TFactory`
     */
    factory: <TFactory>(
      name: string,
      handler: FactoryHandlerFunction<any[]>
    ) => void;
  
    /**
     * Registers a helper in your application. You can pass the type or interface
     * of the service `<THelper>`
     * @param name  - The name of the service
     * @param handler - The callback function that returns methods and properties implemented by `THelper`
     */
    helper: <THelper>(
      name: string,
      handler: HelperHandlerFunction<unknown[], THelper>
    ) => void;
  };
  
  /**
   * All expected return types of handler functions
   */
  export type ResolvedHandlers =
    | null
    | (new (...args: any[]) => any)
    | { [key: string]: any }
    | void;
  
  /** Service and component handler functions */  
  export type HandlerFunction<TDependecies extends unknown[], TObject> = (
    ...args: TDependecies
  ) => TObject;
  
  /** Factory handler function */
  export type FactoryHandlerFunction<TDependecies extends unknown[]> = (
    ...args: TDependecies
  ) => new (...args: any[]) => any;
  
  /** Helper handler function */
  export type HelperHandlerFunction<
    TDependecies extends unknown[],
    TObject
  > = HandlerFunction<TDependecies, TObject | void>;
  
  /** All types of handlers */
  export type PluncHandlers = {
    [K in keyof PluncAppInstance]: PluncAppInstance[K] extends (
      ...args: any[]
    ) => any
      ? K
      : never;
  }[keyof PluncAppInstance];
  
  export type ComponentId = string & { separator: "." };
  
  export type PluncAttributeKey = string & { plunc_prefix: true };
  
  /** 
   * Holds a map of component's parents and keys, each represented 
   * by its own component id
   **/
  export type ComponentFamilyTree = {
    [key: ComponentId]: {
      parent: ComponentId | null;
      children: Array<ComponentId>;
    };
  };
  
  export type HTML5Date = string & { format: "YYYY-MM-DD" };
  
  export type HTML5Time = string & { format: "HH:MM" };
  
  export type SupportedEvents = "click" | "change" | "keyup";
  
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
  
  /** Block API requires call back function */
  export type BlockCallback<TElement extends Element> = (
    element: PluncElementInterface<TElement>
  ) => void;
  
  export type BlockAPI = <TElement extends Element>(
    elementName: string,
    callback: BlockCallback<TElement>
  ) => void;

  export type ApplicationAPI = {
    /**
     * Registers a function that executes when the App is ready
     * @param callback - Function to call after the app is set to ready
     */
    ready:(callback:()=>unknown)=>void
  }

  export type ScopeObject<TScope extends {[key: string]: any}> = TScope 
  export type PatchAPI = (elementName?:string) => Promise<null>

  export const app: PluncAppInstance = {
   component:()=>{},
   service:()=>{},
   factory:()=>{},
   helper:()=>{}
 }