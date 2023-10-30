import { app } from "../app";
import { AppEnvironmentInterface } from "./AppEnvironment";

export interface AppConfigInterface {

}

app.factory('AppConfig',(
    AppEnvironment: AppEnvironmentInterface
)=>{
    class AppConfig implements AppConfigInterface {

    }
    return AppConfig
})