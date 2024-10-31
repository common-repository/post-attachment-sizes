=== Post Attachment Sizes ===
Contributors: TyB
Tags: Post Attachments, Post Images, Attachments, Images, File Size, Post Size
Requires at least: 3.2
Tested up to: 4.7
Stable tag: 1.0.0

Post Attachment Size calculates the total size of all attachments attached to a post and provides a sortable column in the wp-admin post list based on total post size. It also allows you to pull the file size for individual attachments via the shortcode provided.

== Description ==

This plugin provides an additional sortable column in the wp-admin Posts screen that allows you to sort the posts by total size of all attachments. If your post has 10 images attached, the number shown will be the total file size of all 10 images added together. This is useful for WordPress websites with a lot of photo galleries.

== Installation ==

1. Upload the `post-attachment-sizes` directory to your `/wp-content/plugins/` directory.
2. Activate the plugin.

== Usage ==
1. Simply install the plugin and activate using the directions above. The sortable wp-admin column will be added automatically.
2. You can also use the shortcode provided to return the file sizes. Usage is as follows:
	* `[pas_display_post_size post_id=false single=false return="both"]`
		* `post_id` = The post ID (if `single = true` then use the attachment ID) [OPTIONAL]
		* `single` = Set to `true` if you only want the size of ONE attachment (MUST set the `post_id` to the attachment ID if this is true)
		* `return` = Default `both`: returns both the raw byte size of the file AND the formatted value.
			* Use `byte` to return JUST the byte value.
			* Use `formatted` to return JUST the formatted file size

== Changelog ==

= 1.0 =
* Initial Release
