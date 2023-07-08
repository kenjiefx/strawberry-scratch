import { PatchHelper, ScopeObject, app } from "../../strawberry/app";
import { HeaderComponent } from "../Header/Header";

app.component('ProfileCard',(
    $scope: ScopeObject,
    $patch: PatchHelper,
    Header: HeaderComponent
)=>{
    $scope.say_hello = 'Hello World!';
    Header.getNewItem();
    return {
        
    }
});