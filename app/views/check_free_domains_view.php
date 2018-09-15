<p>ВНИМАНИЕ! Проверка доменных имен занимает значительное время!<br>
Для 800 адресов это около 5 минут!<br></p>

<form action="/check_free_domains" method="post">

<p>
Список 1:<br>
<textarea name="list1" cols="100" rows="5">
<?php
if (isset($list1))
  echo implode(' ', $list1);
?>
</textarea>
</p>

<p>
Список 2:<br>
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

<br>

<p><input type='submit' value='Проверить (займет время, ждите!)'></p>
  
</form>

<?php
if (isset($freenames)) {
  echo '<br><p>Всего проверено доменных имен: ', $names_counter;
  $fnames_counter = count($freenames);
  echo '<br>Из них доступны и не заняты: ', $fnames_counter;
  if ($fnames_counter > 0) {
    echo '<br><br>Найденные доменные имена:<br><br>';
    echo implode('<br>', $freenames);
  }
  echo '</p>';
}
?>

