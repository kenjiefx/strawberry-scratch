import { app } from "../../strawberry/app";
app.component('ProfileCard', ($scope, $patch, Header) => {
    $scope.say_hello = 'import World!';
    Header.findElement();
    return {};
});
