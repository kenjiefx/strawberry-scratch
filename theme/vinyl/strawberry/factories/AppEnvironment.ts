import { app } from "../app";
import { GlobalWindowObject } from "../interfaces/window";

export interface AppEnvironmentInterface {
    getDeploymentName:()=>'production' | 'default'
}

/**
 * Registers your global variables
 */
export type AppEnvironment= new (...args: any[]) => AppEnvironmentInterface

app.factory('AppEnvironment',()=>{
    class AppEnvironmentHelper implements AppEnvironmentInterface {
        getDeploymentName(){
            // @ts-ignore
            const deployment:'production'|'default' = window['deployment']?.name ?? 'default'
            return deployment
        }
    }
    return AppEnvironmentHelper
})