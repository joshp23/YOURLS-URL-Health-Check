# YOURLS-URL-Health-Check
A YOURLS plugin that checks submitted URL's for validity, reachability, and redirection

- If a submitted URL does not meet [RFC 2396](http://www.faqs.org/rfcs/rfc2396.html) standards, it is rejected
- If a submitted http(s) URL is unreachable (times out after 3 seconds), it is rejected
- If a submitted http(s) URL is a redirect, the final destination is stored to avoid nested redirects

### Requirements
- A working [YOURLS](https://github.com/YOURLS/YOURLS) installation
- php-curl installed and activated

### INSTALLATION

1. Place the url-health-check folder in YOURLS/user/plugins/
2. Activate in the Admin interface

### TODO

- Make timeout limit adjustable
- Check old links for reachability?

### Support Dev
All of my published code is developed and maintained in spare time, if you would like to support development of this, or any of my published code, I have set up a Liberpay account for just this purpose. Thank you.

<noscript><a href="https://liberapay.com/joshu42/donate"><img alt="Donate using Liberapay" src="https://liberapay.com/assets/widgets/donate.svg"></a></noscript>

===========================

    Copyright (C) 2019 Josh Panter

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
