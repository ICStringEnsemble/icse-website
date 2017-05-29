# ICSE Website

[![Build Status](https://drone.io/github.com/ICStringEnsemble/icse-website/status.png)](https://drone.io/github.com/ICStringEnsemble/icse-website/latest)

This is the source code for Imperial College String Ensemble's website. Feel free to report issues or suggestions here.


## Deploying to the Union server

### Initial setup

Start by cloning this repository to your local machine.

```bash
mkdir ~/web_devel
cd ~/web_devel
git clone https://github.com/ICStringEnsemble/icse-website.git
cd icse-website/Symfony
```

You'll need to have php-7.1 or greater installed, as well as a few extensions.
For Ubuntu 16.04:

```bash
sudo add-apt-repository ppa:ondrej/php
sudo apt-get update
sudo apt-get install php7.1 php7.1-mbstring php7.1-zip php7.1-dom
```

Install Composer, either by following [the instructions on its
website](https://getcomposer.org/download/), or for Ubuntu 16.04 or greater
you can run:

```bash
sudo apt-get install composer
```

Then, use composer to install all of the project's dependencies.

```bash
composer install
# When it asks you for missing parameter values, just press enter for each one
# to use the default values. They can always be changed later.
```

The ICSE website uses a tool called `gicosf` to manage deployment to the Union
webserver. To install it from Ubuntu 16.04:

```bash
sudo apt-get install python3-pip libssh2-1-dev libssl-dev zlib1g-dev
pip3 install --user dphoyes.gicosf
```

Optional: To remove the need to repeatedly type in passwords, you can set up
integration with the [pass](https://www.passwordstore.org/) password manager:

```bash
gicosf pass-setup
# Provide your Imperial login details when prompted
```

Optional: Connecting to the Union webserver requires you to either be inside
Imperial's network, or to connect via VPN. If you have ssh shell access to an
externally visible machine on the Imperial network (eg. if you are a member of
DoC), then another alternative is to set up ssh to use it as a proxy. If you
have DoC access, you can add the following into ~/.ssh/config (substituting
my_username for your Imperial username) in order to remove the need to connect
via VPN:

```
Host shell1.doc.ic.ac.uk
  user my_username

Host dougal.union.ic.ac.uk
  user my_username
  ProxyCommand ssh shell1.doc.ic.ac.uk nc %h %p 2> /dev/null
```

### Deploying

If necessary, pull in any updates to the ICSE website from github:

```bash
git pull --rebase
composer install
```

Connect to the Imperial VPN if needed, then run:

```bash
gicosf deploy
```
