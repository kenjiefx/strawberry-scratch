const t = 'a'; import { ScopeObject, app } from "../app";

export interface AuthSvc {
    getAuth:()=>null,
    fund:()=>null
}
app.service<AuthSvc>('AuthSvc',()=>{
    return {
        getAuth:()=>{
            return null
        },
        fund:()=>null
    }
})