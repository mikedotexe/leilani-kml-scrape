# simple kml scraping
---

`keepgrabbing.php` will loop through the CSV that Dane created, scraping for information about the owner, and saving a URL that will be useful later for gathering the KML.

It outputs `aloha-honua.csv`

That was one evening of work and left off there. There's no reason these can't be combined.

The column Web_URL in `aloha-honua.csv`, if directly fetched, will be a page that loads a bunch of javascript to truly populate. (See `why-we-need-selenium.html` which is committed just for informative purposes.)

You can run the PHP files from a web browser is just on the command line with 

`php keepgrabbing.php`

The file `keepgrabbing2.php` is the one that downloads the KML file, explained below. These filesnames are, of course, in honor of Aaron Schwartz.

Because of the Map_URL issue, we'll use Selenium to open and render the page, then drill down into the Google Earth link. Then we pass that back from Selenium (using Python) to PHP. For whatever reason, using PHP code to download the KML download doens't work. But a simple `wget` does, so we execute that call.

Seems pretty convoluted, but it works.

This will output individual files in the form of `{TMK}.kml`
We may want to combine these into a single file, and do simple manipulations like adding scraped info contained in `aloha-honua.csv` or even changing the KML tags so they're polygons.

[Download ChromeDriver](https://sites.google.com/a/chromium.org/chromedriver/downloads) and place it in the same folder. At the time of this writing it's 12 megs.