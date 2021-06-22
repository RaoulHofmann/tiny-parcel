# Tiny Parcel MicroService

## Steps for setting up server
1. run `composer update`
2. Fill in .env file with info (found below)
3. run `php artisan migrate` to create tables.

### Example .env
```
APP_NAME=Lumen
APP_ENV=local
APP_KEY=XXXXXXXXX
APP_DEBUG=true
APP_URL=http://localhost
APP_TIMEZONE=UTC

LOG_CHANNEL=stack
LOG_SLACK_WEBHOOK_URL=

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=tiny_parcel
DB_PASSWORD=password

CACHE_DRIVER=file
QUEUE_CONNECTION=sync

AUTH0_DOMAIN=https://XXXXX.au.auth0.com/
AUTH0_AUD=tiny_parcel_api
```

### Database structure
![image](https://user-images.githubusercontent.com/7591134/122939540-4b856f80-d3a6-11eb-864e-a3aad8ee5805.png)

## Important file location
* `datebase/migrations` contains the migration script to create the new tables
* `app/Models` contains the models for the database
* `app/Traits` contains a trait used to determine the optimum quote (used when updating or create a new parcel)
* `app/Controller` contains the controllers that return the info
* `app/Middleware/Auth0Middleware.php` Is the file that connects to the Auth0 site and checks the key being passed with the request
* `app/Middleware/LogMiddleware.php` Is the file that gets called on every request and create a log entry in the database
* `bootstrap/app.php` Is the application "config" file
* `routes/web.php` is the file responsible for the routing

## available routes
```
GET /api/pricing_models | returns all pricing models
GET /api/pricing_models/parcels | returns all pricing models
GET /api/parcels | returns all parcels
GET /api/parcels/{id} | returns a parcel
GET /api/prices/{parcelIds?} | returns all parcel pricings
DELETE /api/parcels/{id} | deletes a parcel
POST /api/parcels | Add a new parcel
PATCH /api/parcels/{id} | Update a  parcel
```

## Restrictions
The values for the weight, quote, value and volume are all reduced to lower units and are set to integers so I did not have to deal with floats. This is something that can be change although there is a few positives with dealing with full integers instead of with floats

# Things to imporve upon
* Adding tests because time wise I thought it would be more important to have a working demo rather then have testing.
* Clean up the files as there are many default files that are created  when starting a new project
* Create user management and user specific logs
* Rework pricing_model table format
* Figure out a better way of dealing with different units as everything is required as either cents, grams, ... and its too dependent on user
