# Sendgrid Event Notifications

Sendgrid Events is a PHP script which integrates a MySQL Database with the
[SendGrid Event API](http://sendgrid.com/docs/API_Reference/Webhooks/event.html). It can be used with all types of usauge delivery reporting needs.

This process will log any of the following: delivered, bounced, delayed, clicked, etc., to a database. SendGrid will post events to a server of your choosing.

Note that for performance reasons, you'll probably want to configure your Event API to batch events, this is not required. The bad news is that SendGrid POSTs newline-separate JSON objects, rather than a JSON array.

If you would like to be notified via email regarding any bounced, blocked or erroneous delivery attempt; setup the _cron_ process below, to execute the ```bounces_monitor.php``` script.  


## Installation

1. Execute the ```sendgrid_events.sql``` file.

2. You will need to configure your SendGrid account to [enable the Event API](http://sendgrid.com/app).

3. Update database credentials found in the ```sendgird-json.php``` file.

**(optional)**

4. Setup a _cron_ process to send out an email if there are any events SendGrid might need to have cleared. If a message is bouncing SendGrid will log this status and not attempt to deliver the email again until it is cleared.
```20 10 * * 0-6 /usr/bin/php /path/to/cron/bounces_monitor.php > /dev/null 2>&1```


5. **Enjoy!**


## Contributing

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Added some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request