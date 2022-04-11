<?php
//Реализуйте автозагрузчик классов согласно следующим правилам:
//Разделитель пространства имён преобразуется в разделитель папок:
// / для Linux и MacOS или \ для Windows.
//Знак _ в имени класса преобразуется в разделитель папок.
//Файл с кодом класса имеет расширение .php.
//Примеры:
//\Doctrine\Common\ClassLoader ⇒ /some/path/Doctrine/Common/ClassLoader.php.
//\my\package\Class_Name ⇒ /some/path/namespace/package/Class/Name.php.
//\my\package_name\Class_Name ⇒ /some/path/my/package_name/Class/Name.php.

spl_autoload_register(function ($className) {
    $directories = explode('\\', $className);

    $name = str_replace('-', DIRECTORY_SEPARATOR, end($directories));

    var_dump($name);

    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $file = '/some/path' . $className . 'php';
    if (file_exists($className)) {
        require $file;
    }
});

function xz($className)
{
    $directories = explode('\\', $className);

    $name = str_replace('-', DIRECTORY_SEPARATOR, end($directories));
    $file = sprintf('%s/%s.php', __DIR__, implode(DIRECTORY_SEPARATOR, array_merge($directories, [$name])));

    $test = array_merge($directories, [$name]);
    var_dump($name);
}

echo xz('\my\package_name\Class_Name');

/*
 Подскажите как заменить именно последнее вхождение
 $className = str_replace(array('_','\\'), DIRECTORY_SEPARATOR, $className);
 заменит все вхождения.
 Используя цифровой индекс $className = str_replace('_', '\\', $className, 1);
 отсчет будет с лева на право и соответственно замена произойдет
 в первом вхождении. С регулярными выражениями не дружу еще.
 */

