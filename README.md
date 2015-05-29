# Simple Tab Groups

**Author**            [Steven Slack](https://github.com/S2web)
**Contributors**       [Chris Ferdinandi](https://github.com/cferdinandi)
**License:**           GPLv2 or later
**License URI:**       [http://www.gnu.org/licenses/gpl-2.0.html](http://www.gnu.org/licenses/gpl-2.0.html)

## Description

Create tabs and group them together in tab groups. Display a tab group on any post or page without using opening and closing shortcode brackets. Add as many tabs as you want to a page.

## Features

The idea behind the plugin was to remove the complex shortcode functions that many tab plugins use. This leaves the user subject to break the tab layout by accidentally deleting an opening or closing shortcode while editing a page. Rather than ask the user to basically do some simple programming **simple tab groups** provides an easier UX to the process.

Simple Tab Groups was inspired by Chris Ferdinandi's simple toggle tabs plugin [Tabby](https://github.com/cferdinandi/tabby). Tabby was written in pure javascript (no jQuery).

All tabs are posts in a custom post type. They are grouped together with a custom taxonomy and can be ordered using the post attributes.

Tabs are also uncoupled from post content which makes them reusable across the site. **Simple Tab Groups** introduces a button above the text editor which opens a modal window where you can select the tab group you'd like to display and insert it anywhere inside the content.

## Installation

1. Upload the `simple-tab-groups` directory to the `wp-content/plugins` directory.
2. Activate Simple Tab Groups through the plugins menu in WordPress.

## Usage

1. Add or edit tabs in the `Tabs` menu item found in the WordPress admin menu.
2. Create new tab groups in the submenu page `Tab Groups` or directly in a tabs page.
3. To display tabs you can either use the shortcode button or a template tag.

### Display Tabs using the shortcode button

The shortcode button will automatically generate the shortcode for you. Click on the **Add Tabs** shortcode button while editing any post or page. The button is located next to the **Add Media** button above the text editor.

A modal window will pop up. Here you can choose which tab group to display. If you have not made a tab group yet you will be provided with a link to create some and assign some tabs to it. Otherwise you can insert all the tabs by clicking **insert tabs**.

**Tab Options**

There are currently a few options that you will see in the shortcode button modal.

1. Tab Groups select a tab group via dropdown

underneath you will see **Tab Options**

2. Show tabs as `<buttons>`. Displays as list elements by default.
3. Use the legacy jQuery version of the plugin for older browsers.

By clicking **Insert Tabs** you will insert the shortcode `[simple-tab-groups]` and it's available attributes into the page.

You can also put the shortcode directly onto your page. The shortcode currently accepts three parameters as shown in the example:
```
[simple-tab-groups group="Tab Group Name or Slug" buttons="true" jquery="true"]
```

If `buttons` or `jquery` are set to true it will display the buttons instead of list items and use the legacy jquery version.

### Display tabs using a template function

You can also display tabs anywhere on your site by adding the template tag. Add the template tag like the example below:
```php
if ( ! function_exists( 'simple_tab_groups' ) ) {
    simple_tab_groups( 'your-tab-group' );
}
```
The function also accepts the same parameters as the shortcode with one additional echo or return parameter.

```php
simple_tab_groups( $group = '', $buttons = false, $jquery = false, $echo = true );
```




