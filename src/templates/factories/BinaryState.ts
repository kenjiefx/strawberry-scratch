import { app } from "../interfaces/app";

export interface BinaryStateInterface {
    true:()=>boolean
    false:()=>boolean
    set:(state:boolean)=>void
}

export type BinaryState = new (...args: any[]) => BinaryStateInterface

app.factory('BinaryState', () => {
    class __BINObject implements BinaryStateInterface {
        private __state: '1' | '0'
        constructor(state: boolean){
            this.__state = state ? '1' : '0'
        }
        set(state: boolean){
            this.__state = state ? '1' : '0'
        }
        true(){
            return this.__state === '1'
        }
        false(){
            return this.__state === '0'
        }
    }
    return __BINObject
})