#!/bin/bash
# Rebuild public/css and public/javascript, which are gitignored because in 2016
# they came out of bower and gulp. Neither runs now: the bower registry is gone,
# the git:// URLs in bower.json are refused by GitHub, and gulp 3 with node-sass
# will not install on a current node. Everything bower.json asked for is on npm,
# so this fetches it there, at the versions of the day, and compiles the app's
# own two stylesheets with the modern sass.
#
# The bower_components symlink is what lets style.scss keep its own import path
# untouched: it asks for bower_components/bootstrap-sass/assets/stylesheets.
set -euo pipefail
cd "$(dirname "$0")/.."
ROOT=$(pwd)

mkdir -p public/css public/javascript public/fonts etc/assets
cd etc/assets

if [ ! -d node_modules ]; then
  cat > package.json <<'JSON'
{
  "name": "convergence-demo-assets",
  "private": true,
  "dependencies": {
    "bootstrap-sass": "^3.3.7",
    "bootstrap": "^3.3.7",
    "font-awesome": "^4.7.0",
    "jquery": "^2.2.4",
    "jquery-migrate": "^1.4.1",
    "bootstrap-select": "^1.10.0",
    "bootstrap-tagsinput": "^0.7.1",
    "bootstrap-duallistbox": "^3.0.6",
    "bootstrap-switch": "^3.3.4",
    "bootstrap-slider": "^9.10.0",
    "bootstrap-datepicker": "^1.6.4",
    "bootstrap-markdown": "^2.10.0",
    "devbridge-autocomplete": "^1.2.26",
    "dropzone": "^4.3.0",
    "blueimp-gallery": "^2.21.3",
    "highcharts": "^4.2.7",
    "js-cookie": "^2.1.4",
    "to-markdown": "^3.1.1",
    "intl-tel-input": "^11.0.0",
    "sass": "1.77.8"
  }
}
JSON
  # --legacy-peer-deps: bootstrap-switch 3.4 declares a peer on bootstrap 4 it
  # does not need, and these are bootstrap 3 plugins by definition
  npm install --no-audit --no-fund --legacy-peer-deps --loglevel=error
fi

cd "$ROOT"
ln -sfn etc/assets/node_modules bower_components

# his own, straight across
cp resources/assets/javascript/*.js public/javascript/
cp resources/assets/css/*.css public/css/

N=etc/assets/node_modules
copy() { # copy <source> <destination name under public/>
  if [ -f "$N/$1" ]; then cp "$N/$1" "public/$2"; else echo "  missing: $1"; fi
}

# vendor css, named as the views ask for them
copy font-awesome/css/font-awesome.css                     css/font-awesome.css
copy bootstrap-select/dist/css/bootstrap-select.css        css/bootstrap-select.css
copy bootstrap-tagsinput/dist/bootstrap-tagsinput.css      css/bootstrap-tagsinput.css
copy bootstrap-duallistbox/dist/bootstrap-duallistbox.css  css/bootstrap-duallistbox.css
copy bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css css/bootstrap-switch.css
copy bootstrap-slider/dist/css/bootstrap-slider.css        css/bootstrap-slider.css
copy bootstrap-markdown/css/bootstrap-markdown.min.css     css/bootstrap-markdown.min.css
copy intl-tel-input/build/css/intlTelInput.css             css/intlTelInput.css

# vendor js
copy jquery/dist/jquery.js                                 javascript/jquery.js
copy jquery-migrate/dist/jquery-migrate.js                 javascript/jquery-migrate.js
copy bootstrap/dist/js/bootstrap.js                        javascript/bootstrap.js
copy bootstrap-select/dist/js/bootstrap-select.js          javascript/bootstrap-select.js
copy bootstrap-tagsinput/dist/bootstrap-tagsinput.js       javascript/bootstrap-tagsinput.js
copy bootstrap-duallistbox/dist/jquery.bootstrap-duallistbox.js javascript/jquery.bootstrap-duallistbox.js
copy bootstrap-switch/dist/js/bootstrap-switch.js          javascript/bootstrap-switch.js
copy bootstrap-datepicker/dist/js/bootstrap-datepicker.js  javascript/bootstrap-datepicker.js
copy bootstrap-markdown/js/bootstrap-markdown.js           javascript/bootstrap-markdown.js
copy devbridge-autocomplete/dist/jquery.autocomplete.js    javascript/jquery.autocomplete.js
copy dropzone/dist/dropzone.js                             javascript/dropzone.js
copy blueimp-gallery/js/blueimp-gallery.js                 javascript/blueimp-gallery.js
copy blueimp-gallery/js/jquery.blueimp-gallery.js          javascript/jquery.blueimp-gallery.js
copy highcharts/highstock.js                               javascript/highstock.js
copy highcharts/highcharts-3d.js                           javascript/highcharts-3d.js
copy js-cookie/src/js.cookie.js                            javascript/js.cookie.js
copy to-markdown/dist/to-markdown.js                       javascript/to-markdown.js
copy intl-tel-input/build/js/intlTelInput.js               javascript/intlTelInput.js

# font-awesome's own font files, which its stylesheet asks for as ../fonts
cp -r "$N"/font-awesome/fonts/. public/fonts/ 2>/dev/null || true
cp -r "$N"/bootstrap-sass/assets/fonts/bootstrap/. public/fonts/ 2>/dev/null || true

# two of the plugins have no npm counterpart under the name bower used, and the
# views load them unconditionally: an empty file keeps the console quiet without
# pretending the plugin is there.
for f in javascript/bootstrap-multiEmail.js javascript/responsive-paginate.js \
         javascript/bootstrap3-typeahead.js; do
  [ -f "public/$f" ] || : > "public/$f"
done
[ -f public/css/bootstrap-multiEmail.css ] || : > public/css/bootstrap-multiEmail.css

# the app's own two stylesheets
SASS=$N/sass/sass.js
node "$SASS" --load-path=. --quiet resources/assets/sass/style.scss public/css/style.css
node "$SASS" --load-path=. --quiet \
  resources/assets/sass/bootstrap-and-bootstrap-grid-ms.scss \
  public/css/bootstrap-and-bootstrap-grid-ms.css

echo "css:  $(ls public/css | wc -l) files"
echo "js:   $(ls public/javascript | wc -l) files"
echo "font: $(ls public/fonts | wc -l) files"
