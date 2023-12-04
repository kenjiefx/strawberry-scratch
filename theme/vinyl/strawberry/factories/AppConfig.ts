import { app } from "../app";
import { AppEnvironment, AppEnvironmentInterface } from "./AppEnvironment";

export interface AppConfigInterface {

}

export type AppConfig = new (...args: any[]) => AppConfigInterface

app.factory('AppConfig',(
    AppEnvironment: AppEnvironment
)=>{
    class AppConfig implements AppConfigInterface {
        env:AppEnvironmentInterface
        constructor(){
            this.env = new AppEnvironment()
        }
    }
    return AppConfig
})