import { app } from "../../app";

export interface LocalTokenManager {

}

app.service<LocalTokenManager>('LocalTokenManager',()=>{
    console.log('This is the local token manager')
    return {}
})