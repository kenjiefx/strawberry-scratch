import { app } from "../interfaces/app";

type StateInterface = {
  true: () => boolean
  false: () => boolean
  set: (state: boolean) => void
}
export type BinaryState = new (...args: any[]) => StateInterface

app.factory('BinaryState', () => {
  class Factory implements StateInterface {
    private state: '1' | '0'
    constructor(state: boolean){
      this.state = state ? '1' : '0'
    }
    set(state: boolean){
      this.state = state ? '1' : '0'
    }
    true(){
      return this.state === '1'
    }
    false(){
      return this.state === '0'
    }
  }
  return Factory
})