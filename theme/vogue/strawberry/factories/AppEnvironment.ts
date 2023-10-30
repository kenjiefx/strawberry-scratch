import { app } from "../app";
import { GlobalWindowObject } from "../interfaces/window";

export interface AppEnvironmentInterface {
    getDeploymentName:()=>'production' | 'default'
}
app.factory('AppEnvironment',()=>{
    class AppEnvironmentHelper implements AppEnvironmentInterface {
        getDeploymentName(){
            // @ts-ignore
            const deployment:'production'|'default' = window['deployment'].name
            return deployment
        }
    }
    return AppEnvironmentHelper
})