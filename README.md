# twi2disbot
Script that uses the Twitter API v2 to post new Tweets  to a Discord Webhook.

It iterates over every Entry in the Database and checks if the new tweetid differs from the one that is saved.


# Database looks like this:

![Database](https://i.imgur.com/RqZFuoO.png)

You could get the twitteruserid by simply using the API but we only have a limited amount of API calls per Month
thats why i use a tool like https://tweeterid.com/ 
