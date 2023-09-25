<template xcomponent="@Header">
    <div xif="state=='loading'"></div>
    <div xif="state=='active'">
        This is the header component
    </div>
    <div xif="state=='error'"></div>
</template>