<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

  <title>CMS Installer - Step 1</title>
<style>
body {
font-family:Helvetica,Arial,sans-serif;
}
</style>
</head>
<body>

<h1>CMS
Installer</h1>


<h3>Step
1 - Database and User Setup</h3>

<form method="post" action="install_step2.php" name="settings">
  <table style="text-align: left; width: 75%;" border="0" cellpadding="2" cellspacing="2">

    <tbody>

      <tr>

        <td style="width: 540px;">Database Server Address<br>

(normally localhost, don't
change unless you are certain this is different </td>

        <td style="width: 200px;"><input name="DB_HOSTNAME" value="localhost"></td>

      </tr>

      <tr>

        <td style="width: 540px;">Database Name</td>

        <td style="width: 200px;"><input name="DB_DATABASE"></td>

      </tr>

      <tr>

        <td style="width: 540px;">Database Username</td>

        <td style="width: 200px;"><input name="DB_USERNAME"></td>

      </tr>

      <tr>

        <td style="width: 540px;">Database Password</td>

        <td style="width: 200px;"><input type="password" name="DB_PASSWORD"></td>

      </tr>

      <tr>

        <td style="width: 540px;">Database Prefix<br>

(not required, only needed if sharing a database with other software)</td>

        <td style="width: 200px;"><input name="DB_PREFIX"></td>

      </tr>

      <tr>

        <td style="width: 540px;"></td>

        <td style="width: 200px;"></td>

      </tr>
			
			<tr>

        <td style="width: 540px;">Admin E-Mail Address (Login name)</td>

        <td style="width: 200px;"><input name="HostUN"></td>

      </tr>
			
			<tr>

        <td style="width: 540px;">Admin Password</td>

        <td style="width: 200px;"><input type="password" name="HostPW"></td>

      </tr>
			
			<tr>

        <td style="width: 540px;">Repeat Password</td>

        <td style="width: 200px;"><input type="password" name="HostPW2"></td>

      </tr>

    </tbody>
  </table>

  <br>

  <div style="text-align: right;"><input value="Go to Step 2" type="submit">
  </div>

</form>

</body>
</html>
