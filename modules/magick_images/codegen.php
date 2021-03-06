<?

$models = $__wicked['modules']['activerecord']['models'];
foreach($models as $model_klass)
{
  $t = singularize(tableize($model_klass));
  $bt = eval("return $model_klass::\$belongs_to;");
  foreach($bt as $k=>$arr)
  {
    if ($arr[0]=='Attachment')
    { 
      foreach($config['sizes'] as $size_name=>$size)
      {
        $code = <<<PHP
  function {$t}_{$size_name}_{$k}_url__d(\$o, \$params=array())
  {
    return get_magick_url(\$o, '$k', '$size_name', \$params);
  }

  function {$t}_get_{$size_name}_{$k}_url__d(\$o)
  {
    return \$o->{$size_name}_{$k}_url();
  }

  function {$t}_{$size_name}_{$k}_img__d(\$o, \$params=array())
  {
    return image_tag(\$o->{$size_name}_{$k}_url(\$params));
  }

  function {$t}_get_{$size_name}_{$k}_img__d(\$o)
  {
    return \$o->{$size_name}_{$k}_img();
  }

PHP;
        $__wicked['codegen'][] = $code;

      }
    }
  }
}

foreach($config['sizes'] as $size_name=>$size)
{
  $code = <<<PHP
    function attachment_{$size_name}_img__d(\$a, \$params=array())
    {
      return image_tag(magick_img_url('$size_name', \$a->vpath, \$params));
    }

    function attachment_get_{$size_name}_img__d(\$a)
    {
      return \$a->{$size_name}_img(array());
    }

PHP;
  $__wicked['codegen'][] = $code;
}
