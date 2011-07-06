<html>
<head>
<script type='text/javascript'>
function Goto(Url) {
parent.location = Url;
}
</script>
<style>
body {
font-size: 12px;
font-family: sans-serif;
}
a {
color: #FFF;
text-decoration: none;
}
</style>
</head>
<body onload='Goto("<?php echo $_REQUEST['url']; ?>")'>
<p><a href='<?php echo $_REQUEST['url']; ?>' target='_top'>Click here to continue.</a></p>
</body>
</html>