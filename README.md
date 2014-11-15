# HTML to PDF Converter for Laravel 4

[![Build Status](https://travis-ci.org/cangelis/l4pdf.png?branch=master)](https://travis-ci.org/cangelis/l4pdf)

This is a yet another html to pdf converter for Laravel 4. This package uses [wkhtmltopdf](https://github.com/antialize/wkhtmltopdf) as a third-party tool so `proc_*()` functions has to be enabled in your php configurations and `wkhtmltopdf` tool should be installed in your machine (You can download it from [here](https://code.google.com/p/wkhtmltopdf/downloads/list)).

**If you are not a Laravel user, check out [here](https://github.com/cangelis/php-pdf)**

## Installation

### Step 1

Add this to your `composer.json`

    {
        "require": {
            "cangelis/l4pdf": "1.1.*"
        }
    }

### Step 2

Add this line to `providers` array in your `app/config/app.php`

    'CanGelis\L4pdf\ServiceProvider'

### Step 3

Add this line to `aliases` array in your `app/config/app.php`

    'PDF' => 'CanGelis\L4pdf\PDFFacade'

### Step 4

Run this command to publish the configurations of this package

    php artisan config:publish cangelis/l4pdf

### Step 5

Configure your `wkhtmltopdf` executable path under `app/config/packages/cangelis/l4pdf/config.php`

    'executable' => '/usr/bin/wkhtmltopdf'

## Some examples

    PDF::loadView('pdf.invoice')->download('invoice.pdf');

    PDF::loadURL('http://www.laravel.com')->grayscale()->pageSize('A3')->orientation('Landscape')->stream('laravel.pdf')

    Route::get('/', function() {
        return PDF::loadHTML('<strong>Hello World</strong>')->lowquality()->pageSize('A2')->download();
    });

## Saving the PDF

l4pdf uses [League\Flysystem](https://github.com/thephpleague/flysystem) to save the file to the local or remote filesystems.

### Usage

    $pdfObject->save(string $filename, League\Flysystem\AdapterInterface $adapter, $overwrite)

`filename`: the name of the file you want to save with

`adapter`: FlySystem Adapter

`overwrite`: If set to `true` and the file exists it will be overwritten, otherwise an Exception will be thrown.

### Examples

    // Save the pdf to the local file system
    PDF::loadHTML('<b>Hello World</b>')
        ->save("invoice.pdf", new League\Flysystem\Adapter\Local(__DIR__.'/path/to/root'));

    // Save to AWS S3
    $client = S3Client::factory([
        'key'    => '[your key]',
        'secret' => '[your secret]',
    ]);
    PDF::loadHTML('<b>Hello World</b>')
        ->save("invoice.pdf", new League\Flysystem\Adapter\AwsS3($client, 'bucket-name', 'optional-prefix'));

    // Save to FTP
    $ftpConf = [
        'host' => 'ftp.example.com',
        'username' => 'username',
        'password' => 'password',

        /** optional config settings */
        'port' => 21,
        'root' => '/path/to/root',
        'passive' => true,
        'ssl' => true,
        'timeout' => 30,
    ];
    PDF::loadHTML('<b>Hello World</b>')
        ->save("invoice.pdf", new League\Flysystem\Adapter\Ftp($ftpConf));

    // Save to the multiple locations and stream it
    return PDF::loadHTML('<b>Hello World</b>')
            ->save("invoice.pdf", new League\Flysystem\Adapter\Ftp($ftpConf))
            ->save("invoice.pdf", new League\Flysystem\Adapter\AwsS3($client, 'bucket-name', 'optional-prefix'))
            ->save("invoice.pdf", new League\Flysystem\Adapter\Local(__DIR__.'/path/to/root'))
            ->download();

Please see all the available adapters on the [League\Flysystem](https://github.com/thephpleague/flysystem)'s documentation

## Documentation

You can see all the available methods in the full [documentation](https://github.com/cangelis/l4pdf/blob/master/DOCUMENTATION.md) file

## Contribution

Feel free to contribute!
