<section xcomponent="@Footer">
    <div xif="StateManager.component.state=='loading'"></div>
    <div xif="StateManager.component.state=='active'"></div>
    <div xif="StateManager.component.state=='error'"></div>
</section>