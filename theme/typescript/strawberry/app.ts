type CallbackFunction<T extends any[],TComponent> = (...args: T) => TComponent;

export interface StrawberryApp {
    component:<TComponent,T extends any[]>(name:string,callback:CallbackFunction<T,TComponent>)=>null
}

export type ScopeObject = {[key: string]: any}
export type PatchHelper = () => void;
export type BlockElement = {
    name: string,
    each:(element:StrawberryElement)=>void
}
export type StrawberryElement = {
    addClass:(className:string)=>void,
    removeClass:(className:string)=>void,
    $element: HTMLElement
}
export type BlockElements<TBlockElement extends BlockElement> = (blockElement:TBlockElement) => void;

export const app:StrawberryApp = {
    component:()=>{
        return null;
    }
}