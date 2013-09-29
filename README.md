# Sendgrid Events

Sendgrid Events is a PHP script which integrates a MySQL Database with the
[SendGrid Event API](http://docs.sendgrid.com/documentation/api/event-api/). It can be used with all types of usauge delivery reporting needs.

This process will log any of the following: delivered, bounced, delayed, clicked, etc., to a database. SendGrid will post events to a server of your choosing.

Note that for performance reasons, you'll probably want to configure your Event API to batch events. The bad news is that SendGrid POSTs newline-separate JSON objects, rather than a JSON array.


## Installation

Execute the ```sendgrid_events.sql``` file.

You'll need to configure your SendGrid account to [enable the Event API](http://sendgrid.com/docs/API_Reference/Webhooks/event.html).

Update the ```db.class.php``` file with your database credentials so the data posted from SendGrid can be logged.

Enjoy!


## Contributing

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Added some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request