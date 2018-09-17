<p>ВНИМАНИЕ! Проверка доменных имен через WhoIs и DNS занимает значительное время!<br>
Для 800 адресов это около 5 минут!<br></p>

<form action="/check_free_domains" method="post">

<p>
Список 1 (не более 50 слов):<br>
<textarea name="list1" cols="100" rows="5">
<?php
if (isset($list1))
  echo implode(' ', $list1);
?>
</textarea>
</p>

<p>
Список 2 (не более 50 слов):<br>
<textarea name="list2" cols="100" rows="5">
<?php
if (isset($list2))
  echo implode(' ', $list2);
?>
</textarea>
</p>

<p>
Кроме простого слияния, использовать слияние через дефис: 
<input type="checkbox" name="box1"
<?php
if (isset($defis) && $defis)
  echo ' checked';
?>
>
</p>
<?php if (!isset($method)) $method = 'whois'; ?>

Способ проверки доменных имен:<br>
<input name="method" type="radio" value="whois"
<?php
if ($method == 'whois')
  echo ' checked';
?>
> Через WhoIs (медленно, но надежно)<br>
<input name="method" type="radio" value="dns"
<?php
if ($method == 'dns')
  echo ' checked';
?>
> Через DNS (быстрее, но есть ложные результаты)<br>
<input name="method" type="radio" value="api"
<?php
if ($method == 'api')
  echo ' checked';
?>
> Через API GoDaddy (надежно и очень быстро, но не более 30000 адресов в минуту)<br>

<br><br>

<p><input type='submit' value='Проверить (займет время, ждите!)'></p>
  
</form>

<?php
if (isset($freenames)) {
  echo '<br><p>Всего проверено доменных имен: ', $names_counter;
  $fnames_counter = count($freenames);
  echo '<br>Из них свободны: ', $fnames_counter;
  if ($fnames_counter > 0) {
    echo '<br><br>Найденные доменные имена:<br><br>';
    echo implode('<br>', $freenames);
  }
  echo '</p>';
}
?>

