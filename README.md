Signal Plugin for Kanboard
===========================
*(Based on Hipchat and Telegram Plugin)*

Receive Kanboard notifications on Signal (via signal-cli).

Developed using [Kanboard](https://kanboard.org) Version 1.2.5

Author
------

- Benedikt Hopmann
- License MIT
- Signal Logo from https://raw.githubusercontent.com/signalapp/Signal-Android/master/artwork/logo-512.png

Requirements
------------

- Kanboard >= 1.0.37
- Existing [signal-cli](https://github.com/AsamK/signal-cli) instance

Installation
------------

You have the choice between two methods:

1. Download the zip file and decompress everything under the directory `plugins/Signal`
2. Clone this repository into the folder `plugins/Signal`

Note: Plugin folder is case-sensitive.

Configuration
-------------

### Signal Plugin Settings

Firstly, you need to set up the Signal Plugin, then configure Kanboard.

Go to **Settings > Integrations > Signal** and fill the forms:

- **Temp Dir**: Temporary directory (for processing attachments), e.g. `/tmp`
- **Java**: Path to local Java JDK installation (JAVA_HOME environment variable)
- **signal-cli**: Path to local signal-cli interface (with optional commands like --dbus or --dbus-system), e.g. `/usr/local/bin/signal-cli`
- **signal-cli config directory**: Global path to signal-cli config directory, e.g. `/home/[USER]/.config/signal`
- **Signal username**: Global Signal username (i.e. registered signal number with country calling code like +4915151111111)

### Receive individual user notifications

- Enter phone number with country calling code here (i.e. the number must start with a "+" sign like +4915152222222): **Your user profile > Integrations > Signal > Signal recipient**
- *Optionally* set divergent path to signal-cli config directory and signal username
- Then enable Signal notifications in your profile: **Notifications > Select Signal**

### Receive project notifications to a group

- Go to the project settings then choose **Integrations > Signal**
- Enter Signal recipient group (Group ID in base64 encoding)
- *Optionally* set divergent path to signal-cli config directory and signal username
- Then enable Signal notifications in your profile: **Notifications > Select Signal**

Troubleshooting
---------------

- Enable the PHP debug mode
- All errors are recorded in the logs
