<?php
class rcube_vmail_password
{
    function save($currpass, $passwd)
    {
        $rcmail = rcmail::get_instance();

        $username = $rcmail->user->get_username('local');
        $domain   = $rcmail->user->get_username('domain');
	$hash     = $rcmail->config->get('password_passwd_hash');
	$path     = $rcmail->config->get('password_passwd_path') . "/" . $domain;
        $command  = $rcmail->config->get('password_passwd_comman');

	switch($hash) {
		case 'sha':
			$alg = 'sha1';
			break;
		default:
			$alg = $hash;
	}

	try {
		$cur_passwd = "{" . strtoupper($hash) . "}" . hash($alg, $currpass);
		$hash_passwd = "{" . strtoupper($hash) . "}" . hash($alg, $passwd);
	} catch (Exception $e) {
		return PASSWORD_CRYPT_ERROR;
	}

	$f = popen($command, "w");
	if (! $f)
		return PASSWORD_CONNECT_ERROR;
	fwrite($f, $username . "\n");
	fwrite($f, $domain . "\n");
	fwrite($f, $hash . "\n");
	fwrite($f, $path . "\n");
	fwrite($f, $cur_passwd . "\n");
	fwrite($f, $hash_passwd . "\n");
	$ret = pclose($f);
	if ($ret == 0)
		return PASSWORD_SUCCESS;
	else
		return PASSWORD_ERROR;
	}
}
?>
