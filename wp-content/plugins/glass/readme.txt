=== Plugin Name ===
Contributors: codeblab
Donate link: http://codeblab.com/
Tags: loupe, magnifying glass, spyglass, looking glass, glass, zoom, enlarge, image, images, gallery, galleries, photo, photos, picture, pictures, simple microscope, hand glass, light microscope, jeweler's loupe
Requires at least: 3.0.0
Tested up to: 3.5
Stable tag: 1.3.2
License: GPL3

Hand your visitors a magnifying glass to scrutinize your artwork.

== Description ==

Glass adds a magnifying glass (loupe/hand glass) for images to your WordPress
site. It needs *no* Flash and *no* CSS3 radius. It works on the iPad and on
many other platforms. (Thanks for all the positive feedback.)

This started out as a challenge to code a round magnifying glass that would
work *without* Flash and *without* the CSS3 radius property. If you are
interested in coding read [the blog
entry](http://codeblab.com/2011/12/squaring-the-circle/ "CodeBlab - Squaring
The Circle"). Also, a user-selectable color for the glass's rim was claimed to
be impossible without Flash. Read [the blog post on color
stacking](http://codeblab.com/2011/12/welsh-incident/) if you are tech
inclined. Otherwise just enjoy the result.

When the cursor hovers over an image a round frame is displayed on top of it,
the frame shows a (round) part of a hires image, creating the illusion of a
magnifying glass. Currently Glass only works on .jpg and .png images. Click
behavior of the image is unaltered, however, double clicking is "hijacked" to
display the GLP license and displaying the homepage of Glass.

Glass is activated for images directly linked to an image, where the link
target is assumed to be a hires version. Glass also is activated for an image
with a size suffix (e.g., -100x100.jpg) linked to a page. The URL of the hires
version is guessed by removing the size. Guessing the hires URL allows Glass
to work on galleries of thumbnails. Since galleries can contain many
thumbnails, Glass can slow down page loading. Therefore the URL guessing can
be altered by specifying a lower resolution, e.g., 800x600. This will make
Glass look for a smaller version. The guessing can also be switched off.

If linking an image is needed but Glass should not be activated, wrap the
image in span begin and end tags. 

= Disclaimer =

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.


== Installation ==

1. Upload glass.1.3.2.zip to the `/wp-content/plugins/` directory.
1. Unpack glass.1.3.2.zip, optionally remove it after unpacking.
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How do I activate Glass on an image? =

Once activated it will add its magnifying glass functionality to any image on
your blog that is wrapped in a link to an image. The linked image is taken to
be the hires version of the displayed image. WordPress will generate such a
link if the [File URL] button is selected in the "Add an image" window.


= How do I activate Glass on a gallery? =

In general it will work by default. Most gallery implementation generate a
set of thumbnail images that link to something, usually not a hires version.
Glass makes an attempt to recognize thumbnails by their -100X100 suffix. If a
thumbnail is found, Glass guesses the URL of the hires image (by removing the
size suffix).


= Does it work on OS X? = 

On OS X, Glass has shown to work on most recent versions of Firefox, Chrome,
Safari, Opera, and Camino.


= Does it work on iOS? =

On iOS, Glass has shown to work on the iPhone 3 and iPhone 4, the iPad, and
the iPod Touch. However, some large images are scaled down, which might render
the resulting enlargement less crispy. Which is a pity, especially on the
retina displays. If you know a magic suffix or something that will make it
use the full hires image, please let me know at coder at codeblab dot com.


= Does it work on UNIX-like desktops? =

On UNIX-like desktop systems like Auburn, FreeBSD, and Solaris, Glass has been
shown to work on recent versions of Firefox, Opera, Chromium, Epiphany,
Midori, SeaMonkey, and many others.


= Does it work on Windows? =

On Windows XP and Windows 7, Glass has shown to work on recent versions of
Firefox, Safari, Opera, Chrome, and Internet Explorer 9. It does work on
Internet Explorer 8, however it has trouble rendering the antialiassed pixels
on the solid colored rim which leads to a noticeable outline of the rim.


= Does it work on Android? =

On Android I can't test it. Unless it supports a iOS compatible touch
interface for JavaScript it won't work. I don't have an Android phone or tablet
and the simulator does not support multitouch. I would *love* to test it on
this platform. If you are a coder and willing to help me, e-mail me: 
coder at codeblab dot com.


= What can I do if it does not work correctly? =

It might not work correct in combination with some themes or other
plugins. It is a lot of work to test it on all combinations. If it
malfunctions, please let me know what theme and plugins you are using
(including version numbers) at: coder at codeblab dot com.


= How do I make my gallery page load faster? =

Because Glass will load a hires version for every thumbnail in your gallery,
load times can go up dramatically. Switch hires guessing for thumbnails off is
a simple remedy. To do that go to the Glass page in the Settings menu and wipe
"Thumb dx dy". An other solution is to set "Thumb dx dy" to a an existing
value, e.g., "800","600", this will force Glass to not use the full resolution
version of the image. Be very sure that the image with the proper suffix,
e.g., -800x600, does exist. 


= How do I customize the rim color? =

There are two ways to customize the rim. The first way is to generate ten
right sized images and add them to a new directory on the Glass plugin
directory. Than select it as the "Glass Rim Path" on the Glass page in the
Settings menu. The second way is to wipe the "Glass Rim Path". This will give
a solid colored rim. The color can be set as a 6 digit hex value in "Glass Rim
RGB" on the same page.


= Why is the solid color rim not properly anti-aliased? =

A lack of proper support of transparency in Microsoft Internet Explorer
version 5,6,7, and 8 will mess up the anti aliasing of the rim. I tried to fix
this, but after spending more time on this than on the rest, I gave up. Please
forgive me. Please try to forgive Microsoft for not getting it right in all
those years, causing thousands of frustrated developers to swindle uncountable
hours that could have been used to make something useful. 


= Are you nuts? Do you know that the iPod has a much better zoom? =

Yes, and I do. Just add iPod, iPhone, iPad to the "Glass Exclude Platforms" on
the Glass Settings page.


= How do I activate Glass only for category 'X'? =

Add the categories you want to the "Glass Categories" on the Glass Settings
page. Leaving it empty means all categories. To exclude all categories use an
non existing category like "NOTONE".


= How do I activate Glass only for page 'Y'? =

Add the page names you want to the "Glass Pages" on the Glass Settings
page. Leaving it empty means all pages. To exclude all pages use an
non existing page name like "NONE".


= Why is the glass garbled on the iOS screen? =

Safari on iOS silently downsizes larger images sometimes leading to up sizing
by the script, which Safari on iOS can't handle, resulting in the garbled
glass image. There are two ways to solve this. One, use smaller upload image,
i.e., downsize your image before you upload it to WordPress. Two, lower the Min
Enlarge factor in the settings.


= Why is glass so sluggish on some pictures on Firefox? =

Really large images are downsized by the script, leading to sluggish behavior
on Firefox but not on other browsers.  There are two ways to solve this. One,
use smaller upload image, i.e., downsize your image before you upload it to
WordPress. Two, up the Max Enlarge factor in the settings.


== Screenshots ==

1. Glass active on a thumbnail using Opera on OS X.

1. Glass active on an image of Bob Floyd rendered using Floyd-Steinberg
dithering. The image is kind of big 2400x3122, yet an older G3 Mac running
Tiger has no trouble processing it in realtime.

1. A rather large gallery of rather large images in Firefox on OS X. This page
works like a charm on most any browser/platform combination I threw at it.

1. Microsoft Internet Explorer version 8 (still widely in use) with a solid
rim. Note the black lines on both the inside and outside of the rim. This is
Microsoft's way of displaying semi-transparent pixels semi-transparently. Note
that all other browsers on the Windows platform know how to handle this, so
does IE v9.

== Changelog ==

= 1.3.2 = 
* Replaced depreciated get_option('home') calls with get_option('siteurl').

= 1.3.1 =
* Replaced depreciated get_settings('home') calls with get_option('home').

= 1.3 =
* Fixed displaced glass image with some browsers and some themes.
* Fixed gray glass with some browsers and some themes.
* A little code clean up.

= 1.2 = 
* All reported themes that failed now work with v1.2.
* Randomly selected themes all worked.
* Refactored to better fit the platform.
* Runs better on IE8, Camino, and iOS devices.

= 1.1 =
* Spelling, less drunk writing. No change in the working code.

= 1.0 =
* Moved to version 1.0 when moving to WordPress hosting.

= 0.9 =
* Home brew version, used to be private until someone talked me into making
it available.

== Upgrade Notice ==

= 1.3.2 =
Fixed problem with non default home location. Again.

= 1.3.1 =
Fixed possible error when using a non default home location. If glass is
working it is only advised to use this version. First timers and if glass
stops working after moving the home location, do update.

= 1.3 =
Fixed displaced (or gray) glass image. Works with all tested browsers and all
tested themes now. Get this update even if the 1.2 version seems ok in your
browsers.

= 1.2 = 
Fixed glass displacements. Fixed cursor displacements. Works with most themes
now. Works better on iOS devices.

= 1.1 =
Mainly spelling corrections. No changes in the code.

= 1.0 =
Get this version. There is no other stable one yet.

