<template xcomponent="@Header">
    Hello World! {{hello}}
    <div xif="state=='loading'"></div>
    <div xif="state=='active'">This is active! <div xcomponent="@ProfileCard"></div></div>
    <div xif="state=='error'"></div>
</template>