# A simple mock application that uses the Yii 1.1 framework.

This repository contains a web application that has a login page containing four slide on the right-hand side and a product updates page.
The login functionality is not implemented and does not need to be; nothing currently requires authorization. It's simply meant to represent a real-world user interface.
 
## Getting Started

Either fork this repository and pull to your local development environment, or download the source code and initialize your own repository (please make an initial commit before making any changes). When complete, commit your changes and push your repository to GitHub.
Please send us the repository link.

## Your Tasks
There are four slides on the login page that use static content. 
Two of the slides need to be updated to use content from two RSS feeds, and a change needs to be made to the updates page.
The below steps are not in any particular order.

### Task: Slide #3 
The current content of this slide should be replaced. 
Instead, it should display the title and short description of the 
most recent product update *only* from https://supereval.com/blog/category/supereval-updates/feed.

### Task: Slide #4
The current content of this slide should be replaced. 
Instead, it should display the title and description of the most recent blog post 
from https://supereval.com/blog/feed.

### Task: Updates Page
We are already processing the recent product update feed in HelpController (/help/updates).
This page should be changed to display only the latest 5 product updates.

### Note:
Feel free to refactor anything as needed for clean code!

## Setting Up the Project

This project does not make use of a database. 
The only thing needed is to configure your local web server to point to the document root which is `apps/pls/www`.
For example, the virtual host configuration might look like:

### Apache
```apacheconf
<VirtualHost *:80>
    ServerName pls.local
    ServerAlias pls.local
    DocumentRoot "/<project-path>/apps/pls/www"
    <Directory "/<project-path>/apps/pls/www">
        Options FollowSymLinks
        AllowOverride All
    </Directory>
</VirtualHost>
```

### Nginx

```nginxconf
server {
    listen 80;
    server_name    pls.local;
    root           /<project-path>/apps/pls/www;
    index          index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
        include conf.d/includes/php.conf;
    }
}
```