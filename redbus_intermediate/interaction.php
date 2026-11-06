<?php
require_once "config/database.php";
include "includes/header.php";
?>
<section class="container">
    <h1>JavaScript interaction demonstration</h1>
    <div class="demo-grid">
        <article class="card">
            <h2>Mouse coordinates</h2>
            <p id="mousePosition">Move the mouse anywhere on this page.</p>
        </article>
        <article class="card">
            <h2>Keyboard event</h2>
            <p id="keyInfo">Press a key on your keyboard.</p>
        </article>
        <article class="card">
            <h2>Contextual help</h2>
            <input placeholder="Focus this field" onfocus="showHelp()" onblur="hideHelp()">
            <p id="helpBox" class="notice"></p>
        </article>
        <article class="card">
            <h2>Dynamic filtering</h2>
            <input id="busFilter" placeholder="Type a bus name">
            <ul id="busList">
                <li>VRL Travels</li><li>SRS Travels</li><li>Orange Travels</li>
                <li>KPN Travels</li><li>KSRTC</li>
            </ul>
        </article>
    </div>
    <h2>Hover a panel to bring it to the front</h2>
    <div class="overlap-area">
        <div class="overlap-panel panel-red" onmouseenter="bringToFront(this)">Panel One</div>
        <div class="overlap-panel panel-blue" onmouseenter="bringToFront(this)">Panel Two</div>
    </div>
</section>
<?php include "includes/footer.php"; ?>