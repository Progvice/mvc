<form method="post" action="/setup/post/send_config.php">
    <input type="text" name="sname" placeholder="Site name" required/><br>
    <input type="text" name="host" placeholder="Host (ex. domain.com)" required/><br>
    <p>Environment</p>
    <div class="row">
        <input type="radio" id="development" name="environment" value="development"/>
        <label for="development">Development</label>
        <input type="radio" id="production" name="environment" value="production"/>
        <label for="production">Production</label>
    </div>
    <button type="submit">Set configuration</button>
</form>