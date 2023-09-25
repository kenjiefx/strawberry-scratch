import { app } from "../app";

export type RequestConfig = {
    url: string,
    data?: {[key:string]:any}
}

export interface HttpRequestHelper {
    post:<TResponse extends {[key:string]:any}>(config:RequestConfig)=>Promise<TResponse>
    get:<TResponse extends {[key:string]:any}>(config:RequestConfig)=>Promise<TResponse>
}

app.service<HttpRequestHelper>('HttpRequestHelper',()=>{
    const runAjax=(config:{
        method: 'GET' | 'POST' | 'PATCH' | 'PUT'
        url: string
        data?: {[key:string]:any}
    })=>{
        return new Promise((resolve:(value:any)=>void,reject)=>{
            $.ajax({
                method: config.method,
                url: config.url,
                contentType: 'application/json',
                data: JSON.stringify(config.data),
                success: resolve,
                error: reject
            })
        })
    }
    return {
        post:(config)=>{
            return new Promise((resolve,reject)=>{
                runAjax({method: 'POST',url: config.url,data: config.data}).then(resolve).catch(reject)
            })
        },
        get:(config)=>{
            return new Promise((resolve,reject)=>{
                runAjax({
                    method: 'GET',
                    url: config.url
                }).then(resolve).catch(reject)
            })
        }
    }
})