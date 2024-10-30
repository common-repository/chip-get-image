=== Chip Get Image ===
Contributors: life.object
Tags: post, posts, automatic, featured, image, images, thumbnail
Requires at least: 2.9
Tested up to: 3.1
Stable tag: 0.3

A flexible image script for adding thumbnails and feature images to the post.

== Description ==

*Chip Get Image* is a plugin that grabs images by using its unique *Short Circuit Speedy Logic*. It is very flexible and easy to use plugin for adding thumbnails, featured images or other images to the blog posts.

*Short Circuit Speedy Logic* is highly flexible that gives you power to grab an image by your choice from custom field input, WordPress post image feature, post attachment or from all as default behaviour.

Visit <a href="http://www.tutorialchip.com/chip-get-image/" title="Chip Get Image">Chip Get Image</a> for usage and more information.

== Installation ==

1. Upload `chip-get-image` folder to your `/wp-contents/plugins` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add the appropriate code to your template files as outlined in the `Plugin URI`.

More detailed instructions are provided in the plugin's URl. It is important to read online article properly to understand all of the options and how the plugin works.

== Frequently Asked Questions ==

= Why was this plugin created? =

Many themes require a lot of work when inputting images to make them look good. This plugin was developed to make that process much easier for the end user. But, at the same time, it needed to be flexible enough to handle anything.

= How does it pull images? =

1. Looks for an image by custom field.
1. If image does not find, check for post image (WordPress 2.9+ feature).
1. If no image is found again, it will find image attached to the post.
1. If attempt is not successful, it will add default image to the post. (You must set default image) .

= What is Short Circuit Speedy Logic? =

You have noticed that plugin by default will make four steps attempt to find image, but you can make it speedy by changing the default behavior of Short Circuit Speedy Logic. It will increase the speed dramatically. For example, When you are very much sure that image will be found from attachment only, than you can tell the plugin to find it from attachment first and than for other options, OR attachment only.

= How do I add it to my theme? =

There are several methods, but in general, you would use this call:

`
<?php
if( function_exists( "chip_get_image" ) ):
$chip_image = chip_get_image();
?>
<a href="<?php echo $chip_image['posturl']; ?>" title="<?php echo $chip_image['alt']; ?>"><img src="<?php echo $chip_image['imageurl']; ?>" alt="<?php echo $chip_image['alt']; ?>" width="150" height="150" /></a>
<?php
endif;
?>
`

== Screenshots ==

You can view this plugin in action on my <a href="http://www.tutorialchip.com/" title="Chip Get Image Demo">tutorial blog</a> (note the thumbnails).

== Changelog ==

= 0.3 =

* A PHP Error has been fixed. Error was observed in WordPress 3.1 release.

= 0.2 =

* A PHP Notice has been fixed. Notice was observed when WordPress runs in WP_DEBUG true mode.

= 0.1 =

Plugin released. I have tested it in different dimensions at my personal blog.