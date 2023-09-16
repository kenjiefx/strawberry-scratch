<template xcomponent="@ProfileCard">
    <div xif="state=='loading'"></div>
    <div xif="state=='active'">
        ProfileCard is active
    </div>
    <div xif="state=='error'"></div>
</template>