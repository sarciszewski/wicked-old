<?


function attachment_save_file($attachment, $arr, $is_committed = true)
{
  global $__wicked;
  $config = $__wicked['modules']['attachment']['config'];
  
  $attachment->committed_at =$is_committed ? time() : null;
	$attachment->original_file_name = $arr['name'];
	$attachment->mime_type = $arr['type'];
	$tmp_name = $arr['tmp_name'];
	$ext = substr($attachment->original_file_name, strrpos($attachment->original_file_name, '.'), strlen($attachment->original_file_name));
	$attachment->local_file_name = md5($attachment->original_file_name . rand()).$ext;
	$attachment->vpath = "/data/attachments/$attachment->local_file_name";
	$attachment->fpath = $config['data_fpath'] . "/$attachment->local_file_name";
	ensure_writable_folder($config['data_fpath']);
	copy($tmp_name, $attachment->fpath);
	$attachment->save();
	return $attachment;
}


function attachment_get_thumb($attachment, $width=75,$height=75)
{
	return image_thumb($attachment->vpath, $width, $height);
}


function attachment_get_is_image($a)
{
  return startswith($a->mime_type, "image/");
}

function attachment_get_name($a)
{
  return $a->original_file_name;
}

function attachment_get_size($a)
{
  return filesize($a->fpath);
}