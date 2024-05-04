import { ScopeObject, app } from "../app";

app.helper<TestHelper>('TestHelper',(
    $scope: ScopeObject<TestHelperScope>
)=>{
    $scope.TestHelper = {
        exec: ()=>{
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
        exec:()=>void
    }
}

export interface TestHelper {
    test:()=>void
}