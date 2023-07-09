import{ PatchHelper, ScopeObject, app } from "../../strawberry/app"
import { IHeader } from "../Header/Header";

app.component('ProfileCard',(
    $scope: ScopeObject,
    $patch: PatchHelper,
    Header: IHeader
)=>{
    $scope.say_hello = 'import World!';
    Header.findElement();
    return {
        
    }
});