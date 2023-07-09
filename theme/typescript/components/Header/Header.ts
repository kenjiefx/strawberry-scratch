import {ScopeObject, PatchHelper, app, BlockElements, BlockElement, StrawberryApp, StrawberryElement} from '../../strawberry/app'
import { AuthSvc } from '../../strawberry/services/AuthSvc'
interface ErrorBlock {
    name: '@ErrorMessageBlock',
    each:(element:StrawberryElement)=>void
} 

interface SuccessBlock {
    name: '@SuccessMessageBlock'
    each:()=>void
}

export interface IHeader {
    getNewItem:()=>{},
    findElement:()=>{},
    checkMate:()=>null
}


app.component<IHeader>('Header',(
    $scope: ScopeObject,
    $patch: PatchHelper,
    $block: BlockElements<ErrorBlock|SuccessBlock>
)=>{
    $scope.say_hello = 'This is header component';
    return {
        getNewItem:()=>{
            return {}
        },
        findElement:()=>{
            return {}
        },
        checkMate:()=>null
    } 
});