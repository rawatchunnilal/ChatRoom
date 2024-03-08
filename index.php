<?php
session_start();
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $_SESSION['name'] = $name;
}
?>

<?php
if (!isset($_SESSION['name'])) {
?>

    <form method="post">
        <input type="text" name="name" placeholder="Enter Your Name">
        <button type="submit" name="submit">Log in</button>
    </form>


<?php
} else {
?>

    <input type="text" id="msg" onkeypress="handleKeyPress(event)">
    <input type="button" name="btn" value="⤵️" onclick="myFunc()">

    <div id="msgBox">

    </div>

    <script>
        var conn = new WebSocket('ws://localhost:8080');
        conn.onopen = function(e) {
            console.log("Connection established!");
        };

        conn.onmessage = function(e) {
            var getContent = JSON.parse(e.data);
            console.log(getContent.userName + " says " + getContent.msg);

            var messageBox = document.getElementById("msgBox");
            var messageDiv = document.createElement("div");
            messageDiv.innerHTML = '<b>' +
                getContent.name + ": </b>" + getContent.msg;
            messageBox.appendChild(messageDiv);
        };
    </script>

    <script>
        function myFunc() {
            var msg = document.getElementById("msg").value;
            var userName = "<?php echo $_SESSION["name"] ?>";
            var content = {
                name: userName,
                msg: msg
            };
            conn.send(JSON.stringify(content));
            console.log(content);

            // Update the message box with the sent message
            var messageBox = document.getElementById("msgBox");
            var messageDiv = document.createElement("div");
            messageDiv.innerHTML = '<b>You :</b> ' + content.msg;
            messageBox.appendChild(messageDiv);
            document.getElementById("msg").value = "";
        };

        function handleKeyPress(event) {
            if (event.key === "Enter") {
                myFunc();
            }
        }
    </script>

<?php
}
?>