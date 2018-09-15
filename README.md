# Check_Free_Domains
Поиск свободных доменов второго уровня в зоне .com перемножением двух списков слов
Реализовано в духе MVC

Использует .htaccess
Требует включенного mod_rewrite. Для Apache2+ достаточно выполнить в терминале Linux
команды sudo a2enmod rewrite && sudo service apache2 restart

Проблемы с правами доступа к файлам решаются chmod 755 на папку с сайтом.
