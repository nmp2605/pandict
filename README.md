<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Pandict

Pandict's purpose is to offer a "universal dictionary", that's the reason for the "pan" prefix in its name. When it comes for searching for complex or arcaic words, a researcher might need to look at more than one dictionary to have a solid understanding of its meaning, sometimes even needing to check encyclopedias. A universal, free dictionary for the portuguese language might make this process more enjoyable for those who really need to go deep into that research. 
## About Laravel

Laravel is a web application framework with expressive, elegant syntax. It was chosen for this project due to the simplicity it offers for necessary web application features.

## About Web-Scraping

Some of the sources that are used offer an API and some require the use of web-scraping techniques. That means we need to parse the HTML of the page containing the result, converting it to an understandable format. In this project, PHP's [DOMDocument](https://www.php.net/manual/en/class.domdocument.php) is used to parse, and some XPath knowledge is required.

## About Parallelism

Pandict is a perfect case for parallelism. Several searches and parsing processes need to be done, so why not do it at the same time? The [parallel](https://www.php.net/manual/en/book.parallel.php) PHP library is used to achieve this result.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
