import { ScopeObject, app } from "../app";

app.helper<TestHelper>('TestHelper',(
    $scope: ScopeObject<TestHelperScope>
)=>{
    $scope.TestHelper = {
        exec: (button)=>{
            console.log(button)
            console.log($scope)
            console.log('button was clicked!')
        }
    }
    return {
        test:()=>{
            console.log('tested')
        }
    }
})

type TestHelperScope = {
    TestHelper: {
        exec:(button)=>void
    }
}

export interface TestHelper {
    test:()=>void
}