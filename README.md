# Convergence

The ticketing system Elettric 80's customers raised issues through, built
2015 to 2016. A Laravel 5.1 helpdesk: a queue with statuses, priorities,
divisions and escalation profiles, the companies and the machines the tickets
are about, and the statistics the team was measured on.

## Running it now

Nothing it was written for still exists on a current machine. PHP 8 will not
parse Laravel 5.1, and mcrypt, which its encrypter defaulted to, went with PHP
7.2. So it runs in containers, at the versions of the day, and Docker is the
only thing the host needs:

```bash
docker compose -f docker-compose.demo.yml up
```

When the log says `Convergence is on http://localhost:8080`, it is: `demo` /
`demo`. Ctrl-C stops it, and `docker compose -f docker-compose.demo.yml down`
takes the database with it.

That one command is the whole procedure, because every step that used to be run
by hand is a service in `docker-compose.demo.yml`. MySQL 5.7 comes up; the
composer and node images fill `vendor/` and rebuild `public/css`,
`public/javascript` and `public/fonts`; then PHP 7.1 migrates, seeds and serves,
which is `etc/demo-entrypoint.sh`. Apache starts last, after the seed, so the
line above is the demo being ready rather than a container being up. The first
run pulls four images and does both installs, so give it a few minutes; the log
in front of you is the progress. Later runs skip the installs and are quick, and
the seed is skipped too if the tickets are still there, so stopping and starting
keeps whatever was clicked through.

The app's own configuration is in the compose file rather than a `.env`: Laravel
5.1 reads it through `env()`, and the container environment is enough, because
its `DetectEnvironment` swallows the missing file.

On an arm64 host (Apple silicon) `db` is pinned to `platform: linux/amd64`,
because `mysql:5.7` was only ever published for amd64 and the pull otherwise
fails with `no matching manifest for linux/arm64/v8`. It runs under Docker's
emulation, which is slower to start but works; the other three images have
arm64 variants and run natively.

`composer install` happens in the composer image because the PHP 7.1 image
carries no composer, and two settings in `composer.json` make that safe:
`platform.php` pins resolution to 7.1.33 whatever PHP is running composer, and
`platform-check` is off because several of the loosely pinned 2016 dependencies
now resolve to releases that declare 7.2, though the app runs on 7.1 regardless.

### What had to change to get here

Two lines, and neither of them is a rewrite:

- **`config/app.php`** asked for `MCRYPT_RIJNDAEL_128` and now asks for
  `AES-256-CBC`. Laravel 5.1 already prefers its OpenSSL encrypter and only
  falls back to mcrypt, so this takes the path the framework wanted anyway.
- **`APP_KEY` is 32 raw characters**, not a `base64:` string. That prefix
  arrived after 5.1, which uses the value as it finds it; the demo's key is in
  `docker-compose.demo.yml`.

### The assets

`public/css`, `public/javascript` and `public/fonts` are gitignored because
they were built by bower and gulp, and all of that is gone: the bower registry
has shut down, the `git://` URLs in `bower.json` are refused by GitHub, and
gulp 3 with node-sass will not install on a current node.

`etc/build-demo-assets.sh` rebuilds them from npm instead. Every one of the 23
bower packages is published there, and `resources/assets/sass/style.scss`
imports bootstrap by path, so a `bower_components` symlink into `node_modules`
lets the stylesheet compile unchanged with the modern `sass`. Three plugins
(`bootstrap-multiEmail`, `responsive-paginate`, `bootstrap3-typeahead`) have no
npm home and are written out as empty files: the views load them
unconditionally and the pages do not need them.

## The demo data

`database/seeds/DemoSeeder.php`. The repository's own `DatabaseSeeder` builds
the RBAC scaffolding and one sample company, which leaves every screen empty.
This one fills the domain: five invented plants, the people on both sides of
the helpdesk, their machines, and 75 tickets over six months with the posts and
the status history behind them. Companies and people are invented; the
machinery is not, because an E80 line is laser guided vehicles, palletisers and
a warehouse controller, and that is what the tickets are about.

Three things it has to get right, all of them learned the hard way:

- **`config/constants.php` pins ids** and the app reads them rather than
  looking anything up. Company 1 is the vendor, statuses are 1 to 8 in the
  order `Status::icon()` switches on, divisions are the eight numbered there,
  and file 10000 is the default profile picture `Person::profile_picture()`
  falls back to. Get one wrong and pages fatal on null.
- **The charts read `tickets_history`, never `tickets`.** A row is a snapshot
  and the chain through `previous_id` is what the statistics join to themselves
  to find the moment a status changed. One row is not a history: it takes two
  to make a transition, and without them every chart is a flat line.
- **Several migrations insert as well as create**, so the seeder truncates
  first. That is also what makes it repeatable.

The seeder is deterministic, so the same run gives the same data. Its generator
takes the high bits of a linear congruential sequence rather than the low ones,
which have almost no period: `% 4` on the raw seed put 41 of 75 tickets in a
single status.

## Recording it

`etc/record-demo.mjs` drives a browser through the app and writes a video:

```bash
node etc/record-demo.mjs        # needs playwright on NODE_PATH
```

Roughly forty seconds, paced rather than instant, through the queue, a ticket
and its thread, the customer it came from and the machines they run, the
statistics, and the back office. It holds each screen long enough to be read
and scrolls the long ones, because a recording that moves as fast as the
browser can render reads as a slideshow of unrelated pages.

## Layout

```
app/Http/Controllers   one per resource, plus LoginController and the statistics
app/Models             Eloquent models; Status and CompanyPerson carry the logic
app/Libraries          MenuBuilder, StatisticsManager, the importer, Slack, email
config/constants.php   the pinned ids, icons and thresholds the whole app reads
database/migrations    38 of them
resources/views        Blade, one folder per resource, layouts/default is the shell
resources/assets       the sass and javascript that build into public/
etc                    the demo harness: entrypoint, apache vhost, assets, recording
```
