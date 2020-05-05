# WordPress Jetpack Valet Driver
A [Laravel Valet][valet] driver that routes missing images to your public site, via [Jetpack's Site Accelerator][site-accelerator] (formerly "Photon") CDN, so you don't have to sync the `wp-content/uploads` folder for local development.

## Installation
1. Place `LocalValetDriver.php` into the root directory of your site. Do not change its name.
2. Edit `LocalValetDriver.php` and change `PUBLIC_DOMAIN` on line 5 to be the public domain of your site (just the domain, like `yoursite.com`).
3. Laravel Valet should now use this driver automatically, and any missing images will load from your public site, via the Jetpack Site Accelerator CDN.

## Why
When you are doing local development on a WordPress site, you need three things: the codebase, a copy of the database, and the `wp-content/uploads` directory. But some sites have many GB of uploads, and it is tedious and wasteful to download and keep a copy of those locally. This driver for [Valet][valet] lets you leave all those files on your production server. And then, for any image that is missing locally, Valet will serve a 302 redirect to that image on your public site, via [Jetpack's Site Accelerator][site-accelerator] CDN. Additionally, if you request a resized version of a WordPress upload (like `some-image-500x500.jpg`), the original image will be requested (`some-image.jpg`) and resizing will be handled dynamically by the Site Accelerator CDN. This means that the specific crop you request doesn't have to actually exist on your production server. This can be useful when you're adding new image sizes in local code, but you haven't regenerated the crops on the production server yet.

## Laravel? Isn't this for WordPress?
[Laravel Valet][valet] is a minimalist PHP development environment for macOS, made by some of the people behind [Laravel][laravel]. It works for Laravel, but it also works for WordPress, Drupal, Joomla, Typo3, Magento, [and more][drivers]! Valet is what I personally use for most of my WordPress and Laravel development, due to its speed, low memory usage, and always-on nature (versus a slow, large virtual machine I have to remember to start up). If you're on a Mac and you do WordPress, Laravel, or other PHP development, you should give it a try!

## Warnings
1. Your WordPress site **must** have the Jetpack plugin installed and connected, otherwise you will be in breach of the Jetpack Terms of Service, and you could lose access to Jetpack and other WordPress.com services for your site.
2. I have no official connection to Automattic, Jetpack, or WordPress.com, and all support questions about Site Accelerator should be [directed to Jetpack support][site-accelerator].

[laravel]: https://laravel.com/
[valet]: https://laravel.com/docs/valet
[site-accelerator]: https://jetpack.com/support/site-accelerator/
[drivers]: https://github.com/laravel/valet/tree/master/cli/drivers