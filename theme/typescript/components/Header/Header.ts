import {ScopeObject, PatchHelper, app, BlockElements, BlockElement, StrawberryApp, StrawberryElement} from '../../strawberry/app'
interface ErrorBlock {
    name: '@ErrorMessageBlock',
    each:(element:StrawberryElement)=>void
} 

interface SuccessBlock {
    name: '@SuccessMessageBlock'
    each:()=>void
}

export interface HeaderComponent {
    getNewItem:()=>{

    }
}

app.component('Header',(
    $scope: ScopeObject,
    $patch: PatchHelper,
    $block: BlockElements<ErrorBlock|SuccessBlock>
)=>{
    $scope.say_hello = 'Hello World!';
    $block({
        name:'@ErrorMessageBlock',
        each:(element)=>{
            element.$element.dataset.helloWorld = '123'
        }
    })
    $scope.events = {
        click:(button:StrawberryElement)=>{
            button.addClass('remove');
        }
    }
    return {
        getNewItem:()=>{
            return {}
        }
    } satisfies HeaderComponent
});